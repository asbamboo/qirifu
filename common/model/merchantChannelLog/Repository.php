<?php
namespace asbamboo\qirifu\common\model\merchantChannelLog;

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
     * @param int[] $merchant_channel_seqs
     * @return Entity[]|NULL
     */
    public function findAllByMerchantChannelSeqs(array $merchant_channel_seqs) : ?array
    {
        return $this->Repository->findBy(['merchant_channel_seq' => $merchant_channel_seqs], ['seq' => 'DESC']);
    }
}