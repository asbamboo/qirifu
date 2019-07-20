<?php
namespace asbamboo\qirifu\common\alipay\response;

/**
 * alipay.trade.page.pay(统一收单下单并支付页面接口) 响应结果
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class SystemOauthTokenResponse extends ResponseAbstract
{
    /**
     * 必填 支付宝用户的唯一userId
     *
     * @var string(16)
     */
    protected $user_id;

    /**
     * 必填 访问令牌。通过该令牌调用需要授权类接口
     *
     * @var string(40)
     */
    protected $access_token;

    /**
     * 必填 刷新令牌。通过该令牌可以刷新access_token
     *
     * @var string(40)
     */
    protected $refresh_token;

    /**
     * 必填 访问令牌的有效时间，单位是秒。
     *
     * @var string(16)
     */
    protected $expires_in;

    /**
     * 必填 刷新令牌的有效时间，单位是秒。
     *
     * @var string(16)
     */
    protected $re_expires_in;

    /**
     * 必填 10000 接口调用成功，调用结果请参考具体的API文档所对应的业务返回参数
     *
     * @see https://docs.open.alipay.com/common/105806
     * @var string
     */
    protected $code = self::CODE_SUCCESS;

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\qirifu\common\alipay\response\ResponseAbstract::getResponseRootNode()
     */
    final protected function getResponseRootNode() : string
    {
        return 'alipay_system_oauth_token_response';
    }
}