<?php
namespace asbamboo\qirifu\hosts\admin\login;

use asbamboo\security\user\BaseUser;
use asbamboo\qirifu\common\Constant;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class User extends BaseUser
{
    /**
     * 返回用户的ID
     *
     * @return string
     */
    public function getUserId() : string
    {
        return Constant::SYSTEM_ADMIN_USER_ID;
    }
}