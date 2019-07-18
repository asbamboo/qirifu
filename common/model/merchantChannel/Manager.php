<?php
namespace asbamboo\qirifu\common\model\merchantChannel;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\qirifu\common\model\merchant\Entity AS MerchantEntity;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\security\user\AnonymousUser;
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
     * @var UserTokenInterface
     */
    private $UserToken;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, UserTokenInterface $UserToken, ServerRequestInterface $ServerRequest, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->ServerRequest    = $ServerRequest;
        $this->Repository       = $Repository;
        $this->UserToken        = $UserToken;
    }

    /**
     *
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $merchant_channel = null) : Entity
    {
        if(is_null($merchant_channel)){
            $this->Entity   = new Entity();
        }else if($merchant_channel instanceof Entity){
            $this->Entity   = $merchant_channel;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($merchant_channel);
            if(empty($this->Entity)){
                throw new MessageException('支付渠道未开通。');
            }
        }
        return $this->Entity;
    }

    /**
     *
     * @param MerchantEntity $MerchantEntity
     * @param int $type
     * @return Manager
     */
    public function create(MerchantEntity $MerchantEntity, int $type) : Manager
    {
        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->Entity->setMerchantSeq($MerchantEntity->getSeq());
        $this->Entity->setUserId($MerchantEntity->getUserId());
        $this->Entity->setType($type);

        $this->validateCreate();

        $this->Entity->setStatus(Code::STATUS_APPLY);
        $this->Entity->setKeyInfo([]);
        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateUser($user_id);
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     *
     * @param int $status
     * @return Manager
     */
    public function updateStatus(int $status) : Manager
    {
        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->Entity->setStatus($status);

        $this->validateUpdateStatus();

        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    public function updateStatusToReapply() : manager
    {
        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->validateUpdateStatusToReapply();

        $this->Entity->setStatus(Code::STATUS_REAPPLY);
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
        if($this->Repository->isExist($this->Entity->getUserId(), $this->Entity->getType()) == true){
            $type_label = Code::TYPES[$this->Entity->getType()];
            throw new MessageException("{$type_label}已经申请开通。");
        }
    }

    public function validateUpdateStatus()
    {
        $this->validateStatus($this->Entity->getStatus());
    }

    public function validateUpdateStatusToReapply()
    {
        if(!in_array($this->Entity->getStatus(), [Code::STATUS_REFUSE, Code::STATUS_RESEND_THIRD])){
            throw new MessageException("不允许重复多次重新提申请。");
        }
    }
}