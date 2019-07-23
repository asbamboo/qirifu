<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\alipay\WwwAuth AS AlipayWwwAuth;
use asbamboo\qirifu\common\asbamboo\tradePay\TradePayRequest;
use asbamboo\qirifu\common\model\merchantChannel\Repository AS MerchantChannelRepository;
use asbamboo\qirifu\common\model\trade\Manager AS TradeManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\qirifu\common\asbamboo\tradePay\TradePayNotify;
use asbamboo\qirifu\common\model\trade\Repository AS TradeRepository;
use asbamboo\http\TextResponse;
use asbamboo\qirifu\common\model\trade\Code AS TradeCode;
use asbamboo\qirifu\common\exception\SystemException;
use asbamboo\router\RouterInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;

class Trade extends ControllerAbstract
{
    public function channels()
    {
        $channels   = [];
        foreach( MerchantChannelCode::TYPES AS $type => $label){
            $channels[] = ['key' => $type, 'label' => $label];
        }
        return $this->successJson('success', [
            'channels'  => $channels,
        ]);
    }

    public function lists(ServerRequestInterface $Request)
    {
        try{
            $data           = ['total' => 0, 'items'=>[]];

            /**
             *
             * @var TradeRepository $TradeRepository
             * @var UserTokenInterface $UserToken
             * @var \asbamboo\qirifu\common\model\trade\Entity $TradeEntity
             */
            $TradeRepository        = $this->Container->get(TradeRepository::class);
            $UserToken              = $this->Container->get(UserTokenInterface::class);
            $Pagintor               = $TradeRepository->getPaginatorByWww($Request, $UserToken->getUser()->getUserId());
            $data['total']          = $Pagintor->count();
            foreach($Pagintor->getIterator() AS $TradeEntity){
                $data['items'][]    = [
                    'seq'           => $TradeEntity->getSeq(),
                    'channel'       => ['key' => $TradeEntity->getMerchantChannelType(), 'label' => MerchantChannelCode::TYPES[$TradeEntity->getMerchantChannelType()]],
                    'amount'        => $TradeEntity->getPrice(),
                    'in_trade_no'   => $TradeEntity->getQirifuTradeNo(),
                    'out_trade_no'  => $TradeEntity->getChannelTradeNo(),
                    'create_ymdhis' => date('Y-m-d H:i:s', $TradeEntity->getCreateTime()),
                    'pay_ymdhis'    => date('Y-m-d H:i:s', $TradeEntity->getPayokTime()),
                ];
            }

            return $this->successJson("success", $data);

        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function authUrl(ServerRequestInterface $Request)
    {
        try{
            /**
             *
             * @var AlipayWwwAuth $AlipayWwwAuth
             */
            $AlipayWwwAuth  = $this->Container->get(AlipayWwwAuth::class);
            $auth_url       = $AlipayWwwAuth->getAuthUrl(\Parameter::instance()->get('ALIPAY_APPID'), $Request->getRequestParam('redirect_url'));

            return $this->successJson("success", [
                'auth_url'  => $auth_url,
            ]);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function authInfo(ServerRequestInterface $Request)
    {
        try{
            /**
             *
             * @var AlipayWwwAuth $AlipayWwwAuth
             */
            $AlipayWwwAuth  = $this->Container->get(AlipayWwwAuth::class);
            $auth_code      = $AlipayWwwAuth->getAuthCode($Request);
            $auth_info      = $AlipayWwwAuth->getAuthTokenInfo($auth_code);

            return $this->successJson("success", $auth_info);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function order(ServerRequestInterface $Request)
    {
        try{
            $trade_price    = $Request->getPostParam('trade_price');
            $pay_type       = $Request->getPostParam('pay_type', 'alipay');
            $user_id        = $Request->getPostParam('user_id');
            $auth_info      = $Request->getPostParam('auth_info');

            /**
             *
             * @var MerchantChannelRepository $MerchantChannelRepository
             */
            $merchant_channel_type      = array_search($pay_type, MerchantChannelCode::TYPE_NAMES);
            $MerchantChannelRepository  = $this->Container->get(MerchantChannelRepository::class);
            $MerchantChannelEntity      = $MerchantChannelRepository->findOneByTypeAndUserId($user_id, $merchant_channel_type);
            $merchant_channel_key_info  = $MerchantChannelEntity->getKeyInfo();

            /**
             *
             * @var TradeManager $TradeManager
             * @var DbFactoryInterface $Db
             * @var \asbamboo\qirifu\common\model\trade\Entity TradeEntity
             */
            $TradeManager               = $this->Container->get(TradeManager::class);
            $Db                         = $this->Container->get(DbFactoryInterface::class);
            $TradeEntity                = $TradeManager->load();
            $TradeManager->create($user_id, $merchant_channel_type, $trade_price);
            $Db->getManager()->flush();


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
            $TradePayRequest->title         = $TradeEntity->getQirifuTradeNo();
            $TradePayRequest->total_fee     = bcmul($trade_price, 100);
            $TradePayRequest->notify_url    = $Router->generateAbsoluteUrl('trade_notify');

            if($merchant_channel_type == MerchantChannelCode::TYPE_ALIPAY ){
                if($auth_info['user_id'] == $user_id){
                    throw new MessageException('不能把钱支付给自己。');
                }
                $TradePayRequest->third_part    = json_encode([
                    'app_auth_token'            => $merchant_channel_key_info['app_auth_token'],
                    'seller_id'                 => $merchant_channel_key_info['user_id'],
                    'buyer_id'                  => $auth_info['user_id'],
                ]);
            }

            $TradePayResponse               = $TradePayRequest->exec();
            $onecd_pay_json                 = json_decode($TradePayResponse->onecd_pay_json, true);

            if($merchant_channel_type == MerchantChannelCode::TYPE_ALIPAY ){
                $channel_trade_no   = $onecd_pay_json['trade_no'];
                $TradeManager->updateChannelTradeNo($channel_trade_no);
                $Db->getManager()->flush();
            }

            return $this->successJson("success", [
                'onecd_pay_json'    => $onecd_pay_json,
            ]);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
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
                $TradeManager->updatePayok();
                $Db->getManager()->flush();
            }elseif($TradePayNotify->trade_status == 'PAYED' && $TradeEnity->getStatus() != TradeCode::STATUS_PAYED){
                if($TradePayNotify->total_fee < bcmul($TradeEnity->getPrice(), 100)){
                    throw new SystemException('非法操作,实际支付金额小于交易应该支付金额2');
                }
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
}
