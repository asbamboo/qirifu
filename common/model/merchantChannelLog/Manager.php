<?php
namespace asbamboo\qirifu\common\model\merchantChannelLog;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\qirifu\common\model\merchantChannel\Entity AS MerchantChannelEntity;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\security\user\AnonymousUser;

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
     * @param MerchantChannelEntity $MerchantChannelEntity
     * @param string $merchant_channel_log_desc
     * @return Manager
     */
    public function create(MerchantChannelEntity $MerchantChannelEntity, string $merchant_channel_log_desc) : Manager
    {
        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();


        $this->Entity->setMerchantChannelSeq($MerchantChannelEntity->getSeq());
        $this->Entity->setMerchantStatus($MerchantChannelEntity->getStatus());
        $this->Entity->setMerchantStatusLabel(MerchantChannelCode::STATUSS[$MerchantChannelEntity->getStatus()]);
        $this->Entity->setDesc($merchant_channel_log_desc);
        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateUser($user_id);

        $this->validateCreate();

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     * 验证
     *
     * @throws MessageException
     */
    public function validateCreate()
    {
        $this->validateDesc($this->Entity->getDesc());
    }
}