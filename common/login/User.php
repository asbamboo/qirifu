<?php
namespace asbamboo\qirifu\common\login;

use asbamboo\security\user\UserInterface;
use asbamboo\qirifu\common\model\user\Entity AS UserEntity;
use asbamboo\qirifu\common\model\user\Manager AS UserManager;
use asbamboo\qirifu\common\Constant;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class User implements UserInterface
{
    /**
     * @var UserManager
     */
    private $UserManager;

    /**
     *
     * @var UserEntity
     */
    private $UserEntity;

    /**
     *
     * @var string
     */
    private $login_name;

    /**
     *
     * @param UserManager $UserManager
     * @param UserEntity $UserEntity
     * @param string $login_name
     * @param string $login_passord
     */
    public function __construct(UserManager $UserManager, UserEntity $UserEntity, string $login_name)
    {
        $this->UserManager      = $UserManager;
        $this->UserEntity       = $UserEntity;
        $this->login_name       = $login_name;
    }

    /**
     * 返回用户的ID
     *
     * @return string
     */
    public function getUserId() : string
    {
        return $this->UserEntity->getUserId();
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\security\user\UserInterface::getLoginName()
     */
    public function getLoginName() : string
    {
        return $this->login_name;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\security\user\UserInterface::getLoginPassword()
     */
    public function getLoginPassword() : ?string
    {
        return $this->UserEntity->getPassword();
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\security\user\UserInterface::encodePassword()
     */
    public function encodePassword(string $password) : ?string
    {
        return $this->UserManager->encodePassword($password);
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\security\user\UserInterface::isEqualPassword()
     */
    public function isEqualPassword(string $password) : bool
    {
        $this->UserManager->load($this->UserEntity);
        return $this->UserManager->isEqualPassword($password);
    }

    /**
     *
     * @return UserEntity
     */
    public function getUserEntity() : UserEntity
    {
        return $this->UserEntity;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\security\user\UserInterface::getRoles()
     */
    public function getRoles() : array
    {
        return [Constant::USER_ROLE_LOGINED];
    }

    /**
     * 返回用户实体类中的属性值
     *
     * @param string $method
     * @param array|null $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if(0 === strpos($method, 'get')){
            if(method_exists($this->UserEntity, $method)){
                return $this->UserEntity->{$method}(... $arguments);
            }
        }
    }

    /**
     * 返回一个调用serialize序列化的数组
     *
     * @return string[]
     */
    public function __sleep()
    {
        return [
            'UserEntity',
            'login_name'
        ];
    }
}