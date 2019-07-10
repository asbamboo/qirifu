<?php
use asbamboo\router\RouterInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\hosts\www\listener\RequestListener;
use asbamboo\framework\Event AS FrameworkEvent;

/**
 * 事件监听器
 */

return [
    [   // 监听http请求，在用户未登录的情况下跳转到登录页面
        'name' => FrameworkEvent::KERNEL_HTTP_REQUEST,
        'class' => RequestListener::class,
        'method' => 'checkIsLogin',
        'construct_params' => ['@'.RouterInterface::class, '@'.ServerRequestInterface::class, '@'.UserTokenInterface::class]
    ],
];