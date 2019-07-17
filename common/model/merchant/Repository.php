<?php
namespace asbamboo\qirifu\common\model\merchant;

use asbamboo\database\FactoryInterface;
use asbamboo\database\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use asbamboo\http\ServerRequestInterface;

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
     * @param string $user_id
     * @return Entity|NULL
     */
    public function findOneByUserId(string $user_id) : ?Entity
    {
        return $this->Repository->findOneBy(['user_id' => $user_id]);
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
        $page_no                = $Request->getQueryParam('page', 1);
        $list_size              = $Request->getQueryParam('limit', 20);
        $merchant_name          = trim($Request->getRequestParam('name', ''));
        $merchant_link_man      = trim($Request->getRequestParam('link_man', ''));
        $merchant_link_phone    = trim($Request->getRequestParam('link_phone', ''));


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
        if($merchant_name !== ''){
            $andx->add($queryBuilder->expr()->like('t.name', ':merchant_name'));
            $queryBuilder->setParameter('merchant_name', "{$merchant_name}%");
            $has_where  = true;
        }
        if($merchant_link_man !== ''){
            $andx->add($queryBuilder->expr()->like('t.link_man', ':merchant_link_man'));
            $queryBuilder->setParameter('merchant_link_man', "{$merchant_link_man}%");
            $has_where  = true;
        }
        if($merchant_link_phone !== ''){
            $andx->add($queryBuilder->expr()->like('t.link_phone', ':merchant_link_phone'));
            $queryBuilder->setParameter('merchant_link_phone', "{$merchant_link_phone}%");
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