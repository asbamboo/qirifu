<?php
namespace asbamboo\qirifu\common\model\merchantDetail;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\qirifu\common\model\merchant\Entity AS MerchantEntity;

/**
 * 管理账号表的操作
 *  - 控制 增/删/改
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Manager
{
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
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, ServerRequestInterface $ServerRequest, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->ServerRequest    = $ServerRequest;
        $this->Repository       = $Repository;
    }

    /**
     *
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $merchant_detail = null) : Entity
    {
        if(is_null($merchant_detail)){
            $this->Entity   = new Entity();
        }else if($merchant_detail instanceof Entity){
            $this->Entity   = $merchant_detail;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($merchant_detail);
            if(empty($this->Entity)){
                throw new MessageException('商户资料详情不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     *
     * @param MerchantEntity $MerchantEntity
     * @param array $merchant_detail_data
     * @return Manager
     */
    public function create(MerchantEntity $MerchantEntity, array $merchant_detail_data) : Manager
    {
        $this->Entity->setMerchantSeq($MerchantEntity->getSeq());
        $this->Entity->setUserId($MerchantEntity->getUserId());
        $this->Entity->setData($merchant_detail_data);

        $this->validateCreate();

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     *
     * @param array $merchant_detail_data
     * @return Manager
     */
    public function update(array $merchant_detail_data) : Manager
    {
        $this->Entity->setData($merchant_detail_data);

        $this->validateUpdate();

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
    }

    public function validateUpdate()
    {

    }
}