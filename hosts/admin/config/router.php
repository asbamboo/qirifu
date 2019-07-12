<?php
return [
    /*******************************************************************************************************************************************
     * 用户登录/注销
     *******************************************************************************************************************************************/
    ['id' => 'user_login', 'path' => '/user/login' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\User:login'],
    ['id' => 'user_info', 'path' => '/user/info' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\User:info'],
    ['id' => 'user_logout', 'path' => '/user/logout' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\User:logout'],
    /******************************************************************************************************************************************/
];