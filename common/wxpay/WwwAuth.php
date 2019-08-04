<?php
namespace asbamboo\qirifu\common\wxpay;

use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\SystemException;

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
        $query_string       = http_build_query([
            'appid'         => $app_id,
            'redirect_uri'  => $redirect_url,
            'response_type' => 'code',
            'scope'         => 'snsapi_base',
            'state'         => "STATE",
        ]);
        $url            = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . $query_string . '#wechat_redirect';

        return $url;
    }

    /**
     *
     * @param ServerRequestInterface $Request
     * @return string|NULL
     */
    public function getAuthCode(ServerRequestInterface $Request) : ? string
    {
        return $Request->getRequestParam('code');
    }

    /**
     *
     * @return array
     */
    public function getAuthTokenInfo(string $app_id, string $app_secret, string $auth_code) : array
    {
        $query_string       = http_build_query([
            'appid'         => $app_id,
            'secret'        => $app_secret,
            'code'          => $auth_code,
            'grant_type'    => 'authorization_code',
        ]);

        $json           = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?".$query_string);
        $decoded_json   = json_decode($json, true);

        if(!isset($decoded_json['openid'])){
            throw new SystemException("微信授权失败。[{$json}]");
        }

        return [
            "access_token"      => $decoded_json['access_token'],
            "expires_in"        => $decoded_json['expires_in'],
            "refresh_token"     => $decoded_json['refresh_token'],
            "openid"            => $decoded_json['openid'],
            "scope"             => $decoded_json['scope'],
        ];
    }
}

