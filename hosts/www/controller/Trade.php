<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\alipay\WwwAuth AS AlipayWwwAuth;
use asbamboo\qirifu\common\asbamboo\tradePay\TradePayRequest;
use asbamboo\qirifu\common\model\merchantChannel\Repository AS MerchantChannelRepository;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;

class Trade extends ControllerAbstract
{
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
            $pay_type       = $Request->getPostParam('pay_type');
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

            if($merchant_channel_type == MerchantChannelCode::TYPE_ALIPAY ){
                /**
                 *
                 * @var TradePayRequest $TradePayRequest
                 */
                $TradePayRequest                = $this->Container->get(TradePayRequest::class);
                $TradePayRequest->channel       = 'ALIPAY_ONECD';
                $TradePayRequest->client_ip     = $Request->getClientIp();
                $TradePayRequest->out_trade_no  = 'TEST' . date("YMDHIS") . rand(1000, 9999);
                $TradePayRequest->title         = $TradePayRequest->out_trade_no;
                $TradePayRequest->total_fee     = bcmul($trade_price, 100);
                $TradePayRequest->third_part    = json_encode([
                    'app_auth_token'            => $merchant_channel_key_info['app_auth_token'],
                    'seller_id'                 => $merchant_channel_key_info['user_id'],
                    'buyer_id'                  => $auth_info['user_id'],
                ]);

                $TradePayResponse               = $TradePayRequest->exec();

                ;
                return $this->successJson("success", [
                    'onecd_pay_json'    => json_decode($TradePayResponse->onecd_pay_json, true),
                ]);
            }
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }
}
