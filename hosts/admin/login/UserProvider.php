<?php
namespace asbamboo\qirifu\hosts\admin\login;

use asbamboo\security\user\provider\MemoryUserProvider;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class UserProvider extends MemoryUserProvider
{
    /**
     *
     * @param array $users ['login_name','login_password', 'roles'][]
     */
    public function __construct(array $users = [])
    {
        foreach($users AS $user){
            parent::addUser(...$user);
        }
    }
}