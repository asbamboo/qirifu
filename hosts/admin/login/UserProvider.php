<?php
namespace asbamboo\qirifu\hosts\admin\login;

use asbamboo\security\user\UserInterface;
use asbamboo\security\user\provider\UserProviderInterface;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class UserProvider implements UserProviderInterface
{
    /**
     * 存在系统用户信息
     * @var array
     */
    private $users  = [];

    /**
     *
     * @param array $users ['login_name','login_password', 'roles'][]
     */
    public function __construct(array $users = [])
    {
        foreach($users AS $user){
            $this->addUser(...$user);
        }
    }

    /**
     * 添加用户
     * @param string $login_name
     * @param string $login_password
     * @param array $roles
     */
    public function addUser(string $login_name, string $login_password, array $roles = []) : self
    {
        $this->users[$login_name]   = [
            'login_name'            => $login_name,
            'login_password'        => $login_password,
            'roles'                 => $roles,
        ];

        return $this;
    }

    /**
     * load user
     * @param string $login_name
     * @return UserInterface|NULL
     */
    public function loadByLoginName(string $login_name): ?UserInterface
    {
        if(array_key_exists($login_name, $this->users)){
            return $this->convertUser($this->users[$login_name]);
        }

        return null;
    }

    /**
     * 将用户信息 array 转换成 Base User
     * @param array $user
     * @return \asbamboo\security\user\BaseUser
     */
    private function convertUser(array $user) : UserInterface
    {
        return (new User())
            ->setLoginName($user['login_name'])
            ->setLoginPassword($user['login_password'])
            ->setRoles($user['roles']);
    }
}