<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\router\RouterInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\model\merchant\Repository AS MerchantRepository;
use asbamboo\http\ServerRequestInterface;
use asbamboo\http\TextResponse;
use asbamboo\qirifu\common\alipay\WwwAuth AS AlipayWwwAuth;
use asbamboo\http\RedirectResponse;
use asbamboo\http\Constant AS HttpConstant;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\model\merchantChannel\Repository AS MerchantChannelRepository;
use asbamboo\qirifu\common\model\trade\Manager AS TradeManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\qirifu\common\asbamboo\tradePay\TradePayNotify;
use asbamboo\qirifu\common\model\trade\Repository AS TradeRepository;
use asbamboo\qirifu\common\model\trade\Code AS TradeCode;
use asbamboo\qirifu\common\exception\SystemException;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\qirifu\common\asbamboo\tradePay\TradePayRequest;
use asbamboo\qirifu\common\wxpay\WwwAuth AS WxpayWwwAuth;
use asbamboo\qirifu\common\model\trade\Entity AS TradeEntity;

class Qrcode extends ControllerAbstract
{
    public function getData()
    {
        /**
         *
         * @var RouterInterface $Router
         * @var UserTokenInterface $UserToken
         */
        $Router         = $this->Container->get(RouterInterface::class);
        $UserToken      = $this->Container->get(UserTokenInterface::class);
        $User           = $UserToken->getUser();

        return $this->successJson('success', [
            'qrcode'        => $Router->generateAbsoluteUrl('qrcode_trade', ['user_id' => $User->getUserId()]),
            'faciltator'    => \Parameter::instance()->get('SYSTEM_FACILTATOR'),
        ]);
    }

    public function trade(string $user_id, ServerRequestInterface $Request)
    {
        try{
            $http_user_agent    = $Request->getServerParams()['HTTP_USER_AGENT'] ?? '';

            if(preg_match('@MicroMessenger@i', $http_user_agent)){
                return $this->wxpay($user_id, $Request);
            }else if(preg_match('@AlipayClient@i', $http_user_agent)){
                return $this->alipay($user_id, $Request);
            }

            throw new MessageException('系统暂不支持您选择的支付方式。');
        }catch(MessageException $e){
            return new TextResponse($e->getMessage());
        }
    }

    private function wxpay(string $user_id, ServerRequestInterface $Request)
    {
        /**
         *
         * @var AlipayWwwAuth $AlipayWwwAuth
         */
        $WxpayWwwAuth               = $this->Container->get(WxpayWwwAuth::class);
        $assign_data                = $this->getPageViewInfo($user_id, MerchantChannelCode::TYPE_WXPAY);
        $assign_data['auth_info']   = [];

        if($Request->getMethod() == HttpConstant::METHOD_POST){
            $trade_price                = $Request->getPostParam('trade_price');
            $auth_info                  = $Request->getPostParam('auth_info');
            $TradeEntity                = $this->tradeToDatabase('wxpay', $user_id, $trade_price);
            $merchant_channel_key_info  = $assign_data['MerchantChannelEntity']->getKeyInfo();

            /**
             *
             * @var RouterInterface $Router
             * @var TradePayRequest $TradePayRequest
             */
            $Router                         = $this->Container->get(RouterInterface::class);
            $TradePayRequest                = $this->Container->get(TradePayRequest::class);
            $TradePayRequest->channel       = 'WXPAY_ONECD';
            $TradePayRequest->client_ip     = $Request->getClientIp();
            $TradePayRequest->out_trade_no  = $TradeEntity->getQirifuTradeNo();
            $TradePayRequest->title         = $assign_data['merchant_name'] . '-' . $assign_data['system_name'];
            $TradePayRequest->total_fee     = bcmul($trade_price, 100);
            $TradePayRequest->notify_url    = $Router->generateAbsoluteUrl('qrcode_notify');

            if($merchant_channel_key_info['sub_mch_id'] == \Parameter::instance()->get('WXPAY_OWNER_MCH_ID')){
                $TradePayRequest->byOwnerWxpay();
                $TradePayRequest->third_part    = json_encode([
                    'openid'                    => $auth_info['openid'],
                ]);
            }else{
                $TradePayRequest->third_part    = json_encode([
                    'sub_mch_id'                => $merchant_channel_key_info['sub_mch_id'],
                    'openid'                    => $auth_info['openid'],
                ]);
            }

            $TradePayResponse               = $TradePayRequest->exec();
            $onecd_pay_json                 = json_decode($TradePayResponse->onecd_pay_json, true);

            $assign_data['onecd_pay_json']  = $onecd_pay_json;
        }

        if(!$Request->getRequestParam('code')){
            $redirect_url   = $Request->getUri()->__toString();
            if(substr($redirect_url, 0, 2) == '//'){
                $redirect_url   = 'http:' . $redirect_url;
            }
            $auth_url       = $WxpayWwwAuth->getAuthUrl(\Parameter::instance()->get('WXPAY_APPID'), $redirect_url);
            return new RedirectResponse($auth_url);
        }

        if(!$Request->getRequestParam('auth_info')){
            $auth_code                  = $WxpayWwwAuth->getAuthCode($Request);
            $assign_data['auth_info']   = $WxpayWwwAuth->getAuthTokenInfo(\Parameter::instance()->get('WXPAY_APPID'), \Parameter::instance()->get('WXPAY_APPSECRET'), $auth_code);
        }else{
            $assign_data['auth_info']   = $Request->getRequestParam('auth_info');
        }

        return $this->view($assign_data, 'qrcode/wxpay.html.tpl');
    }

    private function alipay(string $user_id, ServerRequestInterface $Request)
    {
        /**
         *
         * @var AlipayWwwAuth $AlipayWwwAuth
         */
        $AlipayWwwAuth  = $this->Container->get(AlipayWwwAuth::class);
        $assign_data    = $this->getPageViewInfo($user_id, MerchantChannelCode::TYPE_ALIPAY);

        if($Request->getMethod() == HttpConstant::METHOD_POST){
            $auth_code      = $AlipayWwwAuth->getAuthCode($Request);
            $auth_info      = $AlipayWwwAuth->getAuthTokenInfo($auth_code);
            $trade_price    = $Request->getPostParam('trade_price');

            if($auth_info['user_id'] == $user_id){
                throw new MessageException('不能把钱支付给自己。');
            }

            $TradeEntity                = $this->tradeToDatabase('alipay', $user_id, $trade_price);
            $merchant_channel_key_info  = $assign_data['MerchantChannelEntity']->getKeyInfo();

            /**
             *
             * @var RouterInterface $Router
             * @var TradePayRequest $TradePayRequest
             */
            $Router                         = $this->Container->get(RouterInterface::class);
            $TradePayRequest                = $this->Container->get(TradePayRequest::class);
            $TradePayRequest->channel       = 'ALIPAY_ONECD';
            $TradePayRequest->client_ip     = $Request->getClientIp();
            $TradePayRequest->out_trade_no  = $TradeEntity->getQirifuTradeNo();
            $TradePayRequest->title         = $assign_data['merchant_name'] . '-' . $assign_data['system_name'];
            $TradePayRequest->total_fee     = bcmul($trade_price, 100);
            $TradePayRequest->notify_url    = $Router->generateAbsoluteUrl('qrcode_notify');
            $TradePayRequest->third_part    = json_encode([
                'app_auth_token'            => $merchant_channel_key_info['app_auth_token'],
                'seller_id'                 => $merchant_channel_key_info['user_id'],
                'buyer_id'                  => $auth_info['user_id'],
            ]);

            $TradePayResponse               = $TradePayRequest->exec();
            $onecd_pay_json                 = json_decode($TradePayResponse->onecd_pay_json, true);

            $channel_trade_no               = $onecd_pay_json['trade_no'];

            $this->tradeUpdateChannelNo($TradeEntity, $channel_trade_no);
            $assign_data['onecd_pay_json']  = $onecd_pay_json;
        }

        if(!$Request->getRequestParam('auth_code')){
            $redirect_url   = $Request->getUri()->__toString();
            if(substr($redirect_url, 0, 2) == '//'){
                $redirect_url   = 'http:' . $redirect_url;
            }
            $auth_url       = $AlipayWwwAuth->getAuthUrl(\Parameter::instance()->get('ALIPAY_APPID'), $redirect_url);
            return new RedirectResponse($auth_url);
        }

        return $this->view($assign_data, 'qrcode/alipay.html.tpl');
    }

    public function notify(ServerRequestInterface $Request)
    {
        try{
            /**
             *
             * @var TradePayNotify $TradePayNotify
             */
            $TradePayNotify     = $this->Container->get(TradePayNotify::class);

            $qirifu_trade_no    = $TradePayNotify->out_trade_no;

            /**
             * @var TradeManager $TradeManager
             * @var TradeRepository $TradeRepository
             * @var DbFactoryInterface $Db
             */
            $TradeManager       = $this->Container->get(TradeManager::class);
            $TradeRepository    = $this->Container->get(TradeRepository::class);
            $Db                 = $this->Container->get(DbFactoryInterface::class);
            $TradeEnity         = $TradeRepository->findOneByQirifuTradeNo($qirifu_trade_no);
            $TradeManager->load($TradeEnity);

            if($TradePayNotify->trade_status == 'CANCLE' && $TradeEnity->getStatus() != TradeCode::STATUS_CANCLE){
                $TradeManager->updateCancel();
                $Db->getManager()->flush();
            }elseif($TradePayNotify->trade_status == 'PAYFAILED' && $TradeEnity->getStatus() != TradeCode::STATUS_PAYFAILED){
                $TradeManager->updatePayfailed();
                $Db->getManager()->flush();
            }elseif($TradePayNotify->trade_status == 'PAYING' && $TradeEnity->getStatus() != TradeCode::STATUS_PAYING){
                $TradeManager->updatePaying();
                $Db->getManager()->flush();
            }elseif($TradePayNotify->trade_status == 'PAYOK' && $TradeEnity->getStatus() != TradeCode::STATUS_PAYOK){
                if($TradePayNotify->total_fee < bcmul($TradeEnity->getPrice(), 100)){
                    throw new SystemException('非法操作,实际支付金额小于交易应该支付金额1');
                }
                $TradeManager->updateChannelTradeNo($TradePayNotify->third_trade_no);
                $TradeManager->updatePayok();
                $Db->getManager()->flush();
            }elseif($TradePayNotify->trade_status == 'PAYED' && $TradeEnity->getStatus() != TradeCode::STATUS_PAYED){
                if($TradePayNotify->total_fee < bcmul($TradeEnity->getPrice(), 100)){
                    throw new SystemException('非法操作,实际支付金额小于交易应该支付金额2');
                }
                $TradeManager->updateChannelTradeNo($TradePayNotify->third_trade_no);
                $TradeManager->updatePayed();
                $Db->getManager()->flush();
            }

            return new TextResponse('SUCCESS');
        }catch(MessageException $e){
            return new TextResponse($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return new TextResponse((string) $e);
            }
            return new TextResponse('处理失败');
        }
    }

    /**
     *
     * @param string $pay_type
     * @param string $user_id
     * @param float $trade_price
     * @return TradeEntity
     */
    private function tradeToDatabase(string $pay_type, string $user_id, $trade_price) : TradeEntity
    {
        /**
         *
         * @var TradeManager $TradeManager
         * @var DbFactoryInterface $Db
         * @var MerchantChannelRepository $MerchantChannelRepository
         * @var \asbamboo\qirifu\common\model\trade\Entity TradeEntity
         */
        $merchant_channel_type      = array_search($pay_type, MerchantChannelCode::TYPE_NAMES);
        $TradeManager               = $this->Container->get(TradeManager::class);
        $Db                         = $this->Container->get(DbFactoryInterface::class);
        $TradeEntity                = $TradeManager->load();
        $TradeManager->create($user_id, $merchant_channel_type, $trade_price);
        $Db->getManager()->flush();

        return $TradeEntity;
    }

    /**
     *
     * @param TradeEntity $TradeEntity
     * @param string $channel_trade_no
     */
    private function tradeUpdateChannelNo(TradeEntity $TradeEntity, string $channel_trade_no) : void
    {
        /**
         *
         * @var DbFactoryInterface $Db
         * @var TradeManager $TradeManager
         */
        $Db             = $this->Container->get(DbFactoryInterface::class);
        $TradeManager   = $this->Container->get(TradeManager::class);
        $TradeManager->load($TradeEntity);
        $TradeManager->updateChannelTradeNo($channel_trade_no);
        $Db->getManager()->flush();
    }

    /**
     *
     * @param string $user_id
     * @param int $merchant_channel_type
     * @throws MessageException
     * @return array
     */
    private function getPageViewInfo(string $user_id, int $merchant_channel_type) : array
    {
        /**
         *
         * @var MerchantRepository $MerchantRepository
         */
        $MerchantRepository = $this->Container->get(MerchantRepository::class);
        $MerchantEntity     = $MerchantRepository->findOneByUserId($user_id);

        if(empty($MerchantEntity)){
            throw new MessageException('该商户的二维码尚未正式开通，不能使用');
        }

        /**
         *
         * @var MerchantChannelRepository $MerchantChannelRepository
         */
        $MerchantChannelRepository  = $this->Container->get(MerchantChannelRepository::class);
        $MerchantChannelEntity      = $MerchantChannelRepository->findOneByTypeAndUserId($user_id, $merchant_channel_type);

        if(empty($MerchantChannelEntity) || $MerchantChannelEntity->getStatus() != MerchantChannelCode::STATUS_OK){
            throw new MessageException('此商户该支付通道尚未开通。');
        }

        return [
            'merchant_name'         => $MerchantEntity->getName(),
            'system_name'           => \Parameter::instance()->get('SYSTEM_NAME'),
            'MerchantChannelEntity' => $MerchantChannelEntity,
        ];
    }
}