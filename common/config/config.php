<?php
use asbamboo\framework\config\DbConfig;
use asbamboo\framework\config\EventListenerConfig;

include __DIR__ . DIRECTORY_SEPARATOR . 'Parameter.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'CommonConstant.php';

return [
    /*************************************************************************************************************
     * 数据库配置
     *************************************************************************************************************/
    DbConfig::class     =>
    ['init_params'  => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'db.php']],
    /************************************************************************************************************/

    /*************************************************************************************************************
     * 事件监听器配置
     *************************************************************************************************************/
    EventListenerConfig::class  =>
    ['init_params' => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'listener.php']],
    /************************************************************************************************************/
];