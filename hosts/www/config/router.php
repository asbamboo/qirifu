<?php
return [
    /*******************************************************************************************************************************************
     * 用户注册
     *******************************************************************************************************************************************/
    ['id' => 'register_send_captcha', 'path' => '/register/send-captcha' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Register:sendCaptcha'],
    ['id' => 'register_action', 'path' => '/register/action' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Register:action'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * 用户登录/注销
     *******************************************************************************************************************************************/
    ['id' => 'user_login', 'path' => '/user/login' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\User:login'],
    ['id' => 'user_info', 'path' => '/user/info' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\User:info'],
    ['id' => 'user_logout', 'path' => '/user/logout' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\User:logout'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * 账号信息
     *******************************************************************************************************************************************/
    ['id' => 'account_info', 'path' => '/account/info' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Account:info'],
    ['id' => 'account_setting_account', 'path' => '/account/setting-account' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Account:settingAccount'],
    ['id' => 'account_setting_email', 'path' => '/account/setting-email' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Account:settingEmail'],
    ['id' => 'account_reset_password', 'path' => '/account/reset-password' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Account:resetPassword'],
    ['id' => 'account_send_captcha', 'path' => '/account/send-captcha' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Account:sendCaptcha'],
    /******************************************************************************************************************************************/
];