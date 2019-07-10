<?php
use asbamboo\framework\config\RouterConfig;
use asbamboo\qirifu\hosts\www\login\UserProvider;
use asbamboo\security\user\login\Login;
use asbamboo\security\gurad\authorization\Authenticator;
use asbamboo\framework\config\EventListenerConfig;
use asbamboo\session\Session;
use asbamboo\session\handler\PdoHandler;

/*************************************************************************************************************
 * 环境常量
 *************************************************************************************************************/
$common_config  = include dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
/************************************************************************************************************/

$config = array_merge_recursive($common_config,[
    /*************************************************************************************************************
     * url 规则配置
     *************************************************************************************************************/
    RouterConfig::class     =>
        ['init_params'      => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'router.php']],
    /************************************************************************************************************/

    /*************************************************************************************************************
     * SESSION
     *************************************************************************************************************/
    Session::class              => [
        'init_params'           => [
            'sessionHandler'    => new PdoHandler(new PDO('mysql:host=' . Parameter::DB_HOST . ';dbname=' . Parameter::DB_NAME, Parameter::DB_USER, Parameter::DB_PASSWORD)),
            'option'            => ['cookie_domain'=>$_SERVER['SERVER_NAME'], 'name' => md5('qrfuser')],
        ],
    ],
    /************************************************************************************************************/

    /*************************************************************************************************************
     * 权限配置
     *************************************************************************************************************/
    UserProvider::class     =>
        ['class'            => UserProvider::class],
    Login::class            =>
        ['init_params'      => ['UserProvider' => '@'.UserProvider::class]],
    /************************************************************************************************************/

    /*************************************************************************************************************
     * 事件监听器配置
     *************************************************************************************************************/
    EventListenerConfig::class  =>
        ['init_params' => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'listener.php']],
    /************************************************************************************************************/
]);

return $config;