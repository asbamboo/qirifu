<?php
namespace asbamboo\qirifu\common\model\captcha;

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
     * @param int $captcha_seq
     * @return Entity|NULL
     */
    public function findOneBySeq(int $captcha_seq) : ?Entity
    {
        return $this->Repository->findOneBy(['seq' => $captcha_seq]);
    }

    /**
     *
     * @param string $captcha_target
     * @return Entity|NULL
     */
    public function findOneByTarget(string $captcha_target) : ? Entity
    {
        return $this->Repository->findOneBy(['target' => $captcha_target]);
    }
}