<?php
return [
    /*******************************************************************************************************************************************
     * 用户登录/注销
     *******************************************************************************************************************************************/
    ['id' => 'user_login', 'path' => '/user/login' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\User:login'],
    ['id' => 'user_info', 'path' => '/user/info' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\User:info'],
    ['id' => 'user_logout', 'path' => '/user/logout' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\User:logout'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * SYSTEM SETTING
     *******************************************************************************************************************************************/
    ['id' => 'system_setting', 'path' => '/system/setting/{type}' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\System:setting'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * SYSTEM SETTING
     *******************************************************************************************************************************************/
    ['id' => 'merchant_lists', 'path' => '/merchant/lists' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\Merchant:lists'],
    ['id' => 'merchant_detail', 'path' => '/merchant/detail' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\Merchant:detail'],
    ['id' => 'merchant_channel', 'path' => '/merchant/channel' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\Merchant:channel'],
    ['id' => 'merchant_channel_available_status', 'path' => '/merchant/{type}/channel-available-status' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\Merchant:channelAvailableStatus'],
    ['id' => 'merchant_channel_create_history', 'path' => '/merchant/{type}/channel-create-history' , 'callback' => 'asbamboo\\qirifu\\hosts\\admin\\controller\\Merchant:channelCreateHistory'],
    /******************************************************************************************************************************************/

    /*******************************************************************************************************************************************
     * 图片读取
     *******************************************************************************************************************************************/
    ['id' => 'image_read', 'path' => '/iamge/{fileid}/read' , 'callback' => 'asbamboo\\qirifu\\hosts\\files\\controller\\Image:read'],
    /******************************************************************************************************************************************/
];