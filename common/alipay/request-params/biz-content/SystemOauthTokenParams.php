<?php
namespace asbamboo\qirifu\common\alipay\requestParams\bizContent;

use asbamboo\qirifu\common\alipay\requestParams\CommonParams;

/**
 * alipay.system.oauth.token 参数
 *
 * @see https://docs.open.alipay.com/api_9/alipay.open.auth.token.app
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class SystemOauthTokenParams extends CommonParams
{

    /**
     * 必选
     * 值为authorization_code时，代表用code换取；值为refresh_token时，代表用refresh_token换取
     *
     * @var string(20)
     */
    public $grant_type;

    /**
     * 可选
     * 授权码，如果grant_type的值为authorization_code。该值必须填写
     *
     * @var string(40)
     */
    public $code;

    /**
     * 可选
     * 刷刷新令牌，上次换取访问令牌时得到。见出参的refresh_token字段
     *
     * @var string(40)
     */
    public $refresh_token;
}