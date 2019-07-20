<?php
namespace asbamboo\qirifu\common\alipay;

use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\alipay\response\OpenAuthTokenAppResponse;
use asbamboo\qirifu\common\exception\MessageException;

class WwwAuth
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
        $url            = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id={$app_id}&scope=auth_base&redirect_uri={$redirect_url}";
        if(\Parameter::instance()->get('ALIPAY_SANDBOX')){
            $url        = "https://openauth.alipaydev.com/oauth2/publicAppAuthorize.htm?app_id={$app_id}&scope=auth_base&redirect_uri={$redirect_url}";
        }

        return $url;
    }

    /**
     *
     * @param ServerRequestInterface $Request
     * @return string|NULL
     */
    public function getAuthCode(ServerRequestInterface $Request) : ? string
    {
        return $Request->getRequestParam('auth_code');
    }

    /**
     *
     * @return array
     */
    public function getAuthTokenInfo(string $auth_code) : array
    {
        $AlipayResponse     = APiClient::request('SystemOauthToken', [
            'grant_type'    => 'authorization_code',
            'code'          => $auth_code,
        ]);
        if(     $AlipayResponse->get('code') != OpenAuthTokenAppResponse::CODE_SUCCESS
            ||  $AlipayResponse->get('sub_code') != null
            ){
                $error  = $AlipayResponse->get('code') . ':' .  $AlipayResponse->get('msg') . '|' . $AlipayResponse->get('sub_code') . ':' . $AlipayResponse->get('sub_msg');
                throw new MessageException('授权处理支付宝接口请求失败:'. $error);
        }

        return [
            'user_id'               => $AlipayResponse->get('user_id'),
            'access_token'          => $AlipayResponse->get('access_token'),
            'expires_in'            => $AlipayResponse->get('expires_in'),
            're_expires_in'         => $AlipayResponse->get('re_expires_in'),
            'refresh_token'         => $AlipayResponse->get('refresh_token'),
        ];
    }
}

