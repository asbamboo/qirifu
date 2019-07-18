<?php
namespace asbamboo\qirifu\common\model\merchantChannel;

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
     * @param int $type
     * @return Entity|NULL
     */
    public function findOneByTypeAndMerchantSeq(int $merchant_seq, int $type) : ?Entity
    {
        return $this->Repository->findOneBy(['merchant_seq' => $merchant_seq, 'type' => $type]);
    }

    /**
     *
     * @param string $user_id
     * @param int $type
     * @return Entity|NULL
     */
    public function findOneByTypeAndUserId(string $user_id, int $type) : ?Entity
    {
        return $this->Repository->findOneBy(['user_id' => $user_id, 'type' => $type]);
    }

    /**
     *
     * @param int $merchant_seq
     * @return array|NULL
     */
    public function findAllByMerchantSeq(int $merchant_seq) : ?array
    {
        return $this->Repository->findBy(['merchant_seq' => $merchant_seq]);
    }

    /**
     *
     * @param string $user_id
     * @return Entity[]|NULL
     */
    public function findAllByUserId(string $user_id) : ?array
    {
        return $this->Repository->findBy(['user_id' => $user_id]);
    }
    /**
     * 判断value是否已经存在
     *
     * @param string $account_value
     * @return boolean
     */
    public function isExist(string $user_id, int $merchant_channel_type): bool
    {
        $result = $this->Repository->createQueryBuilder('t')->select('t.seq')->where("t.user_id = '{$user_id}' AND t.type='{$merchant_channel_type}'")->getQuery()->getOneOrNullResult();
        return !is_null($result);
    }
}