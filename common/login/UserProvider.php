<?php
namespace asbamboo\qirifu\common\login;

use asbamboo\security\user\provider\UserProviderInterface;
use asbamboo\security\user\UserInterface;
use asbamboo\qirifu\common\model\account\Repository AS AccountRepository;
use asbamboo\qirifu\common\model\user\Repository AS UserRepository;
use asbamboo\qirifu\common\model\user\Manager AS UserManager;
use asbamboo\qirifu\common\exception\MessageException;

/**
 * 查询用户
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class UserProvider implements UserProviderInterface
{
    /**
     *
     * @var AccountRepository
     */
    private $AccountRepository;

    /**
     *
     * @var UserRepository
     */
    private $UserRepository;

    /**
     *
     * @var UserManager
     */
    private $UserManager;

    /**
     *
     * @param AccountRepository $AccountRepository
     * @param UserRepository $UserRepository
     */
    public function __construct(AccountRepository $AccountRepository, UserRepository $UserRepository, UserManager $UserManager)
    {
        $this->AccountRepository    = $AccountRepository;
        $this->UserRepository       = $UserRepository;
        $this->UserManager          = $UserManager;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\security\user\provider\UserProviderInterface::loadByLoginName()
     */
    public function loadByLoginName(string $login_name) : ?UserInterface
    {
        $Account    = $this->AccountRepository->findOneByValue($login_name);

        if(empty( $Account )){
            throw new MessageException('用户不存在。');
        }

        if($Account->getIsEnable() == false){
            throw new MessageException('该账号已经被设置为不可使用。');
        }

        $User   =   $this->UserRepository->findOneByUserId($Account->getUserId());

        if($User->getIsEnable() == false){
            throw new MessageException('该用户已经被设置为不可使用。');
        }

        return new User(
            $this->UserManager,
            $User,
            $login_name
        );
    }
}