<?php
namespace asbamboo\qirifu\common\model\sysDbLog;

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
        $this->Db                   = $Db;
        $this->Repository           = $this->Db->getManager()->getRepository(Entity::class);
    }

    /**
     * 通过主键查询一个日志信息
     *
     * @param string $user_id
     * @return Entity|NULL
     */
    public function findOneBySeq(string $sys_db_log_seq) : ?Entity
    {
        return $this->Repository->find($sys_db_log_seq);
    }
}