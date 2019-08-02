<?php
namespace asbamboo\qirifu\common\model\message;

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
        $this->Db                   = $Db;
        $this->Repository           = $this->Db->getManager()->getRepository(Entity::class);
    }

    /**
     *
     * @param int $captcha_seq
     * @return Entity|NULL
     */
    public function findOneBySeq(string $message_seq) : ?Entity
    {
        return $this->Repository->findOneBy(['seq' => $message_seq]);
    }

    /**
     *
     * @param string $to_user_id
     * @return int
     */
    public function getUnreadCountByToUserId(string $to_user_id) : int
    {
        $queryBuilder   = $this->Repository->createQueryBuilder('t');

        $andx           = $queryBuilder->expr()->andX();

        $andx->add($queryBuilder->expr()->eq('t.to_user_id', ':to_user_id'));
        $andx->add($queryBuilder->expr()->eq('t.is_read', ':is_read'));

        $queryBuilder->setParameter('to_user_id', $to_user_id);
        $queryBuilder->setParameter('is_read', false);

        $queryBuilder->where($andx);

        $queryBuilder->select('count(1) AS cnt');

        $query_result   = $queryBuilder->getQuery()->getArrayResult();

        return current(current($query_result));
    }

    /**
     * 后台列表页面
     *
     * @param ServerRequestInterface $Request
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getPaginatorByWww(ServerRequestInterface $Request, string $user_id)
    {
        /**
         * request params
         */
        $page_no                = $Request->getQueryParam('page', 1);
        $list_size              = $Request->getQueryParam('limit', 20);
        $is_read                = $Request->getQueryParam('is_read', 'ALL');


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
        $andx       = $queryBuilder->expr()->andX();
        $has_where  = false;

        $andx->add($queryBuilder->expr()->eq('t.to_user_id', ':user_id'));
        $queryBuilder->setParameter('user_id', $user_id);
        $has_where = true;

        if($is_read !== '' && $is_read != 'ALL'){
            $andx->add($queryBuilder->expr()->like('t.is_read', ':is_read'));
            $queryBuilder->setParameter('is_read', "{$is_read}%");
            $has_where  = true;
        }
        if($has_where){
            $queryBuilder->where($andx);
        }

        /**
         * return
         */
        return new Paginator($queryBuilder);
    }
}