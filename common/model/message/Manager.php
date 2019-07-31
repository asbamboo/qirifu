<?php
namespace asbamboo\qirifu\common\model\message;

use asbamboo\database\FactoryInterface;
use asbamboo\qirifu\common\exception\MessageException;
use Doctrine\DBAL\LockMode;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\security\user\AnonymousUser;

/**
 * 管理站内信表的操作
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
     * @var UserTokenInterface
     */
    private $UserToken;

    /**
     *
     * @param FactoryInterface $Db
     * @param UserTokenInterface $UserToken
     * @param Repository $Repository
     */
    public function __construct(FactoryInterface $Db, UserTokenInterface $UserToken,  Repository $Repository)
    {
        $this->Db               = $Db;
        $this->Repository       = $Repository;
        $this->UserToken        = $UserToken;
    }

    /**
     *
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $message = null) : Entity
    {
        if(is_null($message)){
            $this->Entity   = new Entity();
        }else if($message instanceof Entity){
            $this->Entity   = $message;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($message);
            if(empty($this->Entity)){
                throw new MessageException('站内信不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     *
     * @param string $from_user_id
     * @param string $to_user_id
     * @param string $title
     * @return Manager
     */
    public function create(string $from_user_id, string $to_user_id, string $title) : Manager
    {
        $this->Entity->setFromUserId($from_user_id);
        $this->Entity->setToUserId($to_user_id);
        $this->Entity->setTitle($title);

        $this->validateCreate();

        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->Entity->setIsRead(false);
        $this->Entity->setCreateUser($user_id);
        $this->Entity->setCreateTime(time());
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     *
     * @param string $channel_trade_no
     * @return Manager
     */
    public function updateReaded() : Manager
    {
        $this->Entity->setIsRead(true);

        $this->validateUpdateReaded();

        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);

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
        $this->validateTitle($this->Entity->getTitle());
    }
    public function validateUpdateReaded()
    {

    }
}