<?php
namespace asbamboo\qirifu\common\alipay\requestParams;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月11日
 */
class CommonHasReturnParams extends CommonHasNotifyParams
{
    /**
     * HTTP/HTTPS开头字符串
     *
     * @var string(256)
     */
    public $return_url;
}