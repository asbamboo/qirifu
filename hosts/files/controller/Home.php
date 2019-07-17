<?php
namespace asbamboo\qirifu\hosts\files\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\TextResponse;

/**
 * 主页
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月26日
 */
class Home extends ControllerAbstract
{
    /**
     *
     * @return \asbamboo\http\ResponseInterface
     */
    public function index()
    {
        return new TextResponse('this is file manager system.');
    }
}