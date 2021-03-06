<?php
namespace asbamboo\qirifu\common\alipay\request\tool;

use asbamboo\http\Constant;
use asbamboo\http\RequestInterface;
use asbamboo\http\Request;

/**
 * 公共的创建Request对象的方法
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月12日
 */
trait CreateRequestTrait
{
    /**
     *
     * @return RequestInterface
     */
    public function create() : RequestInterface
    {
        return new Request($this->uri(), $this->body(), Constant::METHOD_POST);
    }
}