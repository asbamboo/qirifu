<?php
namespace asbamboo\qirifu\common\model\account;

use asbamboo\database\FactoryInterface;
use asbamboo\database\EntityRepository;
use asbamboo\http\ServerRequestInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
     * @param int $account_seq
     * @return Entity|NULL
     */
    public function findOneBySeq(int $account_seq) : ?Entity
    {
        return $this->Repository->findOneBy(['seq' => $account_seq]);
    }

    /**
     * 判断value是否已经存在
     *
     * @param string $account_value
     * @return boolean
     */
    public function isExistValue($account_value)
    {
        $result = $this->Repository->createQueryBuilder('t')->select('t.seq')->where("t.value='{$account_value}'")->getQuery()->getOneOrNullResult();
        return !is_null($result);
    }

    /**
     * 根据账号值查询一个结果
     *
     * @param string $account_type
     * @return Entity|NULL
     */
    public function findOneByValue(string $account_value) : ? Entity
    {
        return $this->Repository->findOneBy(['value' => $account_value]);
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
     *
     * @param string $user_id
     * @return Entity[]|NULL
     */
    public function findAllByUserIdAndType(string $user_id, $type) : ?array
    {
        return $this->Repository->findBy(['user_id' => $user_id, 'type' => $type]);
    }

    /**
     * 根据seq查询
     *
     * @param array $account_seqs
     * @return array
     */
    public function findBySeqs(array $account_seqs)
    {
        return $this->Repository->findBy(['seq' => $account_seqs]);
    }

    /**
     * 后台列表页面
     *
     * @param ServerRequestInterface $Request
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getPaginatorByAdmin(ServerRequestInterface $Request)
    {
        /**
         * request params
         */
        $page_no        = $Request->getQueryParam('page_no', 1);
        $list_size      = $Request->getQueryParam('list_size', 20);
        $user_id        = $Request->getRequestParam('user_id');


        /**
         * query builder
         *
         * @var \Doctrine\ORM\QueryBuilder $queryBuilder
         */
        $queryBuilder   = $this->Repository->createQueryBuilder('t');
        $queryBuilder->orderBy('t.seq', 'DESC');
        $queryBuilder->setFirstResult(($page_no - 1) * $list_size);
        $queryBuilder->setMaxResults($list_size);

        /**
         * where
         */
        $andx   = $queryBuilder->expr()->andX();
        if($user_id !== ''){
            $andx->add($queryBuilder->expr()->eq('t.user_id', ':user_id'));
            $queryBuilder->setParameter('user_id', $user_id);
        }
        $queryBuilder->where($andx);

        /**
         * return
         */
        return new Paginator($queryBuilder);
    }
}