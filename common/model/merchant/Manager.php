<?php
namespace asbamboo\qirifu\common\model\merchant;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\security\user\AnonymousUser;
use asbamboo\security\user\token\UserTokenInterface;
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
    public function load(/*int|Entity*/ $merchant = null) : Entity
    {
        if(is_null($merchant)){
            $this->Entity   = new Entity();
        }else if($merchant instanceof Entity){
            $this->Entity   = $merchant;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($merchant);
            if(empty($this->Entity)){
                throw new MessageException('商户资料不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     *
     * @param string $user_id
     * @param string $merchant_name
     * @param string $merchant_link_man
     * @param string $merchant_link_phone
     * @param string $media_type
     * @return Manager
     */
    public function create(string $user_id, string $merchant_name, string $merchant_link_man, string $merchant_link_phone, string $merchant_link_email) : Manager
    {

        $this->Entity->setUserId($user_id);
        $this->Entity->setName($merchant_name);
        $this->Entity->setLinkMan($merchant_link_man);
        $this->Entity->setLinkPhone($merchant_link_phone);
        $this->Entity->setLinkEmail($merchant_link_email);
        $this->validateCreate();

        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateUser($user_id);
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);


        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     *
     * @param string $merchant_name
     * @param string $merchant_link_man
     * @param string $merchant_link_phone
     * @return Manager
     */
    public function update(string $merchant_name, string $merchant_link_man, string $merchant_link_phone, string $merchant_link_email) : Manager
    {
        $this->Entity->setName($merchant_name);
        $this->Entity->setLinkMan($merchant_link_man);
        $this->Entity->setLinkPhone($merchant_link_phone);
        $this->Entity->setLinkEmail($merchant_link_email);
        $this->validateUpdate();

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
        $this->validateName($this->Entity->getName());
        $this->validateLinkMan($this->Entity->getLinkMan());
        $this->validateLinkPhone($this->Entity->getLinkPhone());
        $this->validateLinkEmail($this->Entity->getLinkEmail());
    }
    public function validateUpdate()
    {
        $this->validateName($this->Entity->getName());
        $this->validateLinkMan($this->Entity->getLinkMan());
        $this->validateLinkPhone($this->Entity->getLinkPhone());
        $this->validateLinkEmail($this->Entity->getLinkEmail());
    }
}