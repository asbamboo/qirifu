<?php
namespace asbamboo\qirifu\common\alipay;

use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\alipay\response\OpenAuthTokenAppResponse;
use asbamboo\qirifu\common\exception\MessageException;

class Auth
{
    /**
     *
     * @param string $app_id
     * @param string $redirect_url
     * @return string
     */
    public function getAuthUrl(string $app_id, string $redirect_url) : string
    {
        $redirect_url   = urlencode($redirect_url);
        $url            = "https://openauth.alipay.com/oauth2/appToAppAuth.htm?app_id={$app_id}&redirect_uri={$redirect_url}";
        if(\Parameter::instance()->get('ALIPAY_SANDBOX')){
            $url    = "https://openauth.alipaydev.com/oauth2/appToAppAuth.htm?app_id={$app_id}&redirect_uri={$redirect_url}";
        }

        return $url;
    }

    /**
     *
     * @param ServerRequestInterface $Request
     * @return string|NULL
     */
    public function getAppAuthCode(ServerRequestInterface $Request) : ? string
    {
        return $Request->getRequestParam('app_auth_code');
    }

    /**
     *
     * @return array
     */
    public function getAppAuthTokenInfo(string $app_auth_code) : array
    {
        $AlipayResponse     = APiClient::request('OpenAuthTokenApp', [
            'grant_type'    => 'authorization_code',
            'code'          => $app_auth_code,
        ]);
        if(     $AlipayResponse->get('code') != OpenAuthTokenAppResponse::CODE_SUCCESS
            ||  $AlipayResponse->get('sub_code') != null
        ){
            $error  = $AlipayResponse->get('code') . ':' .  $AlipayResponse->get('msg') . '|' . $AlipayResponse->get('sub_code') . ':' . $AlipayResponse->get('sub_msg');
            throw new MessageException('授权处理支付宝接口请求失败:'. $error);
        }

        return [
            'app_auth_token'        => $AlipayResponse->get('app_auth_token'),
            'user_id'               => $AlipayResponse->get('user_id'),
            'auth_app_id'           => $AlipayResponse->get('auth_app_id'),
            'expires_in'            => $AlipayResponse->get('expires_in'),
            're_expires_in'         => $AlipayResponse->get('re_expires_in'),
            'app_refresh_token'     => $AlipayResponse->get('app_refresh_token'),
        ];
    }
}

