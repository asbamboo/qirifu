<?php
use asbamboo\router\RouterInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\hosts\admin\listener\RequestListener;
use asbamboo\framework\Event AS FrameworkEvent;
use asbamboo\qirifu\hosts\admin\Event AS AdminEvent;
use asbamboo\qirifu\hosts\admin\listener\ChannelStatusListener;
use asbamboo\di\ContainerInterface;

/**
 * 事件监听器
 */

return [
    [   // 监听http请求，在用户未登录的情况下跳转到登录页面
        'name' => FrameworkEvent::KERNEL_HTTP_REQUEST,
        'class' => RequestListener::class,
        'method' => 'checkIsLogin',
        'construct_params' => ['@'.RouterInterface::class, '@'.ServerRequestInterface::class, '@'.UserTokenInterface::class],
    ],
    [   // 监听管理员变更支付通道事件，发送站内信
        'name' => AdminEvent::CHANGE_CHANNEL_APPLY_STATUS,
        'class' => ChannelStatusListener::class,
        'method' => 'onChangeCreateMessage',
        'construct_params' => ['@'.ContainerInterface::class],
    ],
    [   // 监听管理员变更支付通道事件，发送email
        'name' => AdminEvent::CHANGE_CHANNEL_APPLY_STATUS,
        'class' => ChannelStatusListener::class,
        'method' => 'onChangeSendEmail',
        'construct_params' => ['@'.ContainerInterface::class],
    ],
];