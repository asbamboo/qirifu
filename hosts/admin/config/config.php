<?php
use asbamboo\framework\config\RouterConfig;
use asbamboo\qirifu\hosts\admin\login\UserProvider;
use asbamboo\security\user\login\Login;
use asbamboo\framework\config\EventListenerConfig;
use asbamboo\session\Session;
use asbamboo\session\handler\PdoHandler;
use asbamboo\qirifu\common\Constant;

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
            'sessionHandler'    => new PdoHandler(new PDO(
                    'mysql:host=' . Parameter::instance()->get('DB_HOST'). ';dbname=' . Parameter::instance()->get('DB_NAME'),
                    Parameter::instance()->get('DB_USER'),
                    Parameter::instance()->get('DB_PASSWORD')
                )),
            'option'            => ['cookie_domain'=>$_SERVER['SERVER_NAME'], 'name' => md5('qrfuser')],
        ],
    ],
    /************************************************************************************************************/

    /*************************************************************************************************************
     * 权限配置
     *************************************************************************************************************/
    UserProvider::class         =>
        ['init_params'          => [
            'users'             => [
                [Parameter::instance()->get('SYSTEM_ADMIN'), Parameter::instance()->get('SYSTEM_PASSWORD'), [Constant::USER_ROLE_LOGINED, Constant::USER_ROLE_ADMIN]]
            ]
        ]
    ],
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