<?php

use asbamboo\framework\kernel\Http;
use asbamboo\qirifu\hosts\admin\AppKernel;

$autoload   = require_once dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

if($_SERVER['SERVER_NAME'] == $_SERVER['REMOTE_ADDR']){
    $is_debug   = true;
}else if( $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'] ){
    $is_debug   = true;
}else{
    $is_debug   = substr($_SERVER['SERVER_NAME'], '-8') == '.develop';
}

(new Http())->run(new AppKernel($is_debug));
