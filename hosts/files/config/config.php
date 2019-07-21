<?php
use asbamboo\framework\config\RouterConfig;
use asbamboo\session\handler\PdoHandler;
use asbamboo\session\Session;

/*************************************************************************************************************
 * 环境常量
 *************************************************************************************************************/
$common_config  = include dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
/************************************************************************************************************/

$config = array_merge_recursive(
    $common_config
    ,[

/*************************************************************************************************************
 * SESSION
 *************************************************************************************************************/
Session::class              => [
    'init_params'           => [
        'sessionHandler'    => new PdoHandler(new PDO(
            'mysql:host=' . Parameter::instance()->get('DB_HOST'). ';port='.Parameter::instance()->get('DB_PORT').';dbname=' . Parameter::instance()->get('DB_NAME'),
            Parameter::instance()->get('DB_USER'),
            Parameter::instance()->get('DB_PASSWORD')
        )),
        'option'            => ['cookie_domain'=>$_SERVER['SERVER_NAME'], 'name' => md5('qrfuser')],
    ],
],
/************************************************************************************************************/


/*************************************************************************************************************
 * url 规则配置
 *************************************************************************************************************/
RouterConfig::class         =>
    ['init_params' => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'router.php']],
/************************************************************************************************************/
]);
return $config;