<?php
namespace asbamboo\qirifu\common\model\account;

use asbamboo\database\FactoryInterface;
use asbamboo\qirifu\common\model\user\Entity AS UserEntity;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\security\user\AnonymousUser;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use Doctrine\DBAL\LockMode;

/**
 * 管理账号表的操作
 *  - 控制 增/删/改
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
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $account = null) : Entity
    {
        if(is_null($account)){
            $this->Entity   = new Entity();
        }else if($account instanceof Entity){
            $this->Entity   = $account;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($account);
            if(empty($this->Entity)){
                throw new MessageException('账号信息不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     * 创建数据行
     *
     * @param UserEntity $UserEntity
     * @param int $account_type
     * @param string $account_value
     * @param bool $account_is_enable
     * @return Manager
     */
    public function create(UserEntity $UserEntity, $account_type, $account_value, $account_is_enable) : Manager
    {
        $this->Entity->setUserId($UserEntity->getUserId());
        $this->Entity->setType($account_type);
        $this->Entity->setValue($account_value);
        $this->Entity->setIsEnable($account_is_enable);

        $this->validateCreate();

        /**
         *
         * @var \asbamboo\webapp\common\login\User $User
         */
        $User       = $this->UserToken->getUser();
        $user_id    = $User instanceof AnonymousUser ? $UserEntity->getUserId() : $User->getUserId();


        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateUser($user_id);
        $this->Entity->setCreateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());

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
     * 验证
     *
     * @throws MessageException
     */
    public function validateCreate()
    {
        $this->validateValue($this->Entity->getValue(), $this->Entity->getType());

        if($this->Repository->isExistValue($this->Entity->getValue())){
            throw new MessageException(Code::TYPES[$this->Entity->getType()] . '已经存在。');
        }
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