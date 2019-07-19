<?php
namespace asbamboo\qirifu\common\alipay\response;

/**
 * alipay.trade.page.pay(统一收单下单并支付页面接口) 响应结果
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class OpenAuthTokenAppResponse extends ResponseAbstract
{
    /**
     * 必填 授权商户的user_id
     *
     * @var string(16)
     */
    protected $user_id;

    /**
     * 必填 授权商户的appid
     *
     * @var string(20)
     */
    protected $auth_app_id;

    /**
     * 填 应用授权令牌
     *
     * @var string(40)
     */
    protected $app_auth_token;

    /**
     * 必填 刷新令牌
     *
     * @var string(40)
     */
    protected $app_refresh_token;

    /**
     * 必填 应用授权令牌的有效时间（从接口调用时间作为起始时间），单位到秒
     *
     * @var string(16)
     */
    protected $expires_in;

    /**
     * 必填 刷新令牌的有效时间（从接口调用时间作为起始时间），单位到秒
     *
     * @var string(16)
     */
    protected $re_expires_in;

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\qirifu\common\alipay\response\ResponseAbstract::getResponseRootNode()
     */
    final protected function getResponseRootNode() : string
    {
        return 'alipay_open_auth_token_app_response';
    }
}