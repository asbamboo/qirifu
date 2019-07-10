<?php
namespace asbamboo\qirifu\common\model\user;

use asbamboo\database\FactoryInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\security\user\AnonymousUser;
use asbamboo\http\ServerRequestInterface;
use Doctrine\DBAL\LockMode;
use asbamboo\qirifu\common\exception\MessageException;

/**
 * 管理账号表的操作
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Manager
{
    use Validator;

    /**
     *
     * @var FactoryInterface
     */
    private $Db;

    /**
     *
     * @var UserTokenInterface
     */
    private $UserToken;

    /**
     *
     * @var ServerRequestInterface
     */
    private $ServerRequest;

    /**
     *
     * @var Repository
     */
    private $Repository;

    /**
     *
     * @var Entity
     */
    private $Entity;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, UserTokenInterface $UserToken, ServerRequestInterface $ServerRequest, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->UserToken        = $UserToken;
        $this->ServerRequest    = $ServerRequest;
        $this->Repository       = $Repository;
    }

    /**
     *
     * @param string|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*string|Entity*/ $user = null) : Entity
    {
        if(is_null($user)){
            $this->Entity   = new Entity();
        }else if($user instanceof Entity){
            $this->Entity   = $user;
        }else{
            $this->Entity   = $this->Repository->findOneByUserId($user);
            if(empty($this->Entity)){
                throw new MessageException('用户不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     * 生成用户唯一标识
     *
     * @return string
     */
    public function generateUserId() : string
    {
        return uniqid();
    }

    /**
     *
     * @param string $user_password
     * @return string
     */
    public function encodePassword($user_password) : string
    {
        if(strlen($user_password) > 0){
            return password_hash($user_password, PASSWORD_BCRYPT);
        }
        return '';
    }

    /**
     * 返回 $user_password是否正确
     *
     * @param string $user_password
     * @return bool
     */
    public function isEqualPassword(string $user_password): bool
    {
        return password_verify($user_password, $this->Entity->getPassword());
    }

    /**
     * 创建数据行
     *
     * @param int $user_type
     * @param string $user_password
     * @param string $user_balance
     * @param bool $user_is_enable
     * @return Manager
     */
    public function create($user_type, $user_password, $user_balance, $user_is_enable) : Manager
    {
        $this->Entity->setUserId($this->generateUserId());
        $this->Entity->setType($user_type);
        $this->Entity->setPassword($user_password);
        $this->Entity->setBalance($user_balance);
        $this->Entity->setIsEnable($user_is_enable);

        $this->validateCreate($this->Entity);

        /**
         *
         * @var \asbamboo\webapp\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? $this->Entity->getUserId() : $LoginUser->getUserId();

        $this->Entity->setPassword($this->encodePassword($this->Entity->getPassword()));
        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateUser($user_id);
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     * 停用
     *
     * @return Manager
     */
    public function updateDisable() : Manager
    {
        $this->validateUpdateDisable();

        $this->Entity->setIsEnable(false);
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($this->UserToken->getUser()->getUserId());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    /**
     * 启用
     *
     * @return Manager
     */
    public function updateEnable() : Manager
    {
        $this->validateUpdateEnable();

        $this->Entity->setIsEnable(true);
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($this->UserToken->getUser()->getUserId());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    /**
     * 修改密码
     *
     * @param string $user_password
     * @return Manager
     */
    public function updatePassword(string $user_password) : Manager
    {
        $this->Entity->setPassword($user_password);

        $this->validateUpdatePassword();

        /**
         *
         * @var \asbamboo\webapp\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? $this->Entity->getUserId() : $LoginUser->getUserId();

        $this->Entity->setPassword($this->encodePassword($this->Entity->getPassword()));
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    /**
     * 验证 准备创建新用户
     */
    public function validateCreate() : void
    {
        $this->validateBalance($this->Entity->getBalance());
    }

    /**
     * 验证 准备修改密码
     */
    public function validateUpdatePassword() : void
    {
    }

    /**
     * 验证 修改使用状态为禁用
     */
    public function validateUpdateDisable() : void
    {
    }

    /**
     * 验证 修改使用状态为启用
     */
    public function validateUpdateEnable() : void
    {
    }
}