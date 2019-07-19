<?php
namespace asbamboo\qirifu\common\alipay\requestParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月11日
 */
class CommonHasNotifyParams extends CommonParams
{
    /**
     * 支付宝服务器主动通知商户服务器里指定的页面http/https路径。
     *
     * @var string(256)
     */
    public $notify_url;
}