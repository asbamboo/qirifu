<?php
namespace asbamboo\qirifu\common\model\merchantDetail;

use asbamboo\database\FactoryInterface;
use asbamboo\database\EntityRepository;

/**
 * 查询管理
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Repository
{

    /**
     *
     * @var FactoryInterface
     */
    private $Db;

    /**
     *
     * @var EntityRepository
     */
    private $Repository;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db)
    {
        $this->Db           = $Db;
        $this->Repository   = $this->Db->getManager()->getRepository(Entity::class);
    }

    /**
     *
     * @param int $seq
     * @return Entity|NULL
     */
    public function findOneBySeq(int $seq) : ?Entity
    {
        return $this->Repository->findOneBy(['seq' => $seq]);
    }

    /**
     *
     * @param int $merchant_seq
     * @return Entity|NULL
     */
    public function findOneByMerchantSeq(int $merchant_seq) : ? Entity
    {
        return $this->Repository->findOneBy(['merchant_seq' => $merchant_seq]);
    }

    /**
     *
     * @param string $user_id
     * @return Entity|NULL
     */
    public function findOneByUserId(string $user_id) : ? Entity
    {
        return $this->Repository->findOneBy(['user_id' => $user_id]);
    }
}