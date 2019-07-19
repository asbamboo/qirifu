<?php
namespace asbamboo\qirifu\common\alipay\requestParams\bizContent;

use asbamboo\qirifu\common\alipay\requestParams\BizContentInterface;
use asbamboo\qirifu\common\alipay\requestParams\MappingDataTrait;

/**
 * alipay.open.auth.token.app 参数
 *
 * @see https://docs.open.alipay.com/api_9/alipay.open.auth.token.app
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class OpenAuthTokenAppParams implements BizContentInterface
{
    use MappingDataTrait;

    /**
     * 必选
     * authorization_code表示换取app_auth_token。refresh_token表示刷新app_auth_token。
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
     * 刷新令牌，如果grant_type值为refresh_token。该值不能为空。该值来源于此接口的返回值app_refresh_token（至少需要通过grant_type=authorization_code调用此接口一次才能获取）
     *
     * @var string(40)
     */
    public $refresh_token;
}