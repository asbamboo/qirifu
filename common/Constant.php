<?php
namespace asbamboo\qirifu\common;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Constant
{
    /*************************************************************************************************************
     * 系统用户(非数据库中的用户)
    **************************************************************************************************************/
    const SYSTEM_CONSOLE_USER       = '1000000000000'; // 命令行执行程序时user id的值
    const SYSTEM_ADMIN_USER_ID      = '2000000000000'; // 系统管理员user id的值
    /*************************************************************************************************************/

    /*************************************************************************************************************
     * 账号
     **************************************************************************************************************/
    const ACCOUNT_ADMIN_PREFIX      = 'admin_'; // 后台管理员的账号都应该时admin_开头（系统处理，用户无感）
    /*************************************************************************************************************/

    /*************************************************************************************************************
     * 用户角色
     **************************************************************************************************************/
    const USER_ROLE_LOGINED         = 'logined'; // 已登录用户
    const USER_ROLE_ADMIN           = 'admin'; // admin 用户
    /*************************************************************************************************************/

    /*************************************************************************************************************
     * 图片保存路径
     ************************************************************************************************************/
    const FILE_IMAGE_ROOT_PATH   = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'hosts' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'images';
    /************************************************************************************************************/

}
