<?php
return [
    /*******************************************************************************************************************************************
     * 主页
     *******************************************************************************************************************************************/
    ['id' => 'home', 'path' => '/' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Home:index'],
    /******************************************************************************************************************************************/

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

    /*******************************************************************************************************************************************
     * 商户资料
     *******************************************************************************************************************************************/
    ['id' => 'merchant_info', 'path' => '/merchant/get-info' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Merchant:getInfo'],
    ['id' => 'merchant_save', 'path' => '/merchant/save' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Merchant:save'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * 支付渠道
     *******************************************************************************************************************************************/
    ['id' => 'channel_info', 'path' => '/channel/get-info' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Channel:getInfo'],
    ['id' => 'channel_new', 'path' => '/channel/new' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Channel:new'],
    ['id' => 'channel_update', 'path' => '/channel/update' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Channel:update'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * 支付宝获取授权
     *******************************************************************************************************************************************/
    ['id' => 'alipay_auth', 'path' => '/alipay/auth' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Alipay:auth'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * 二维码
     *******************************************************************************************************************************************/
    ['id' => 'qrcode_get', 'path' => '/qrcode/get-data' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Qrcode:getData'],
    ['id' => 'qrcode_trade', 'path' => '/qrcode/{user_id}/trade' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Qrcode:trade'],
    ['id' => 'qrcode_notify', 'path' => '/qrcode/notify' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Qrcode:notify'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * 交易
     *******************************************************************************************************************************************/
    ['id' => 'trade_channels', 'path' => '/trade/channels' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Trade:channels'],
    ['id' => 'trade_lists', 'path' => '/trade/lists' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Trade:lists'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * 交易
     *******************************************************************************************************************************************/
    ['id' => 'message_inbox', 'path' => '/message/inbox/lists' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Message:inbox'],
    ['id' => 'message_inbox_read', 'path' => '/message/inbox/read' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Message:inboxRead'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * SYSTEM
     *******************************************************************************************************************************************/
    ['id' => 'system_info', 'path' => '/system/info' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\System:info'],
    /*******************************************************************************
     *
     */
    /*******************************************************************************************************************************************
     * 图片读取
     *******************************************************************************************************************************************/
    ['id' => 'image_upload', 'path' => '/upload/image/{path}', 'callback' => 'asbamboo\\qirifu\\hosts\\files\\controller\\Upload:image'],
    ['id' => 'image_read', 'path' => '/iamge/{fileid}/read' , 'callback' => 'asbamboo\\qirifu\\hosts\\files\\controller\\Image:read'],
    /******************************************************************************************************************************************/
];