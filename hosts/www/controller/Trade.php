<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\qirifu\common\alipay\WwwAuth AS AlipayWwwAuth;

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

            return $this->successJson("success", [
                'auth_info' => $auth_info,
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
}
