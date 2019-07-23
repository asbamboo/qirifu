<?php
namespace asbamboo\qirifu\common\model\trade;

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
     * @param int $captcha_seq
     * @return Entity|NULL
     */
    public function findOneByQirifuTradeNo(string $qirifu_trade_no) : ?Entity
    {
        return $this->Repository->findOneBy(['qirifu_trade_no' => $qirifu_trade_no]);
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
        $channel                = $Request->getRequestParam('channel');
        $qirifu_trade_no        = trim($Request->getRequestParam('in_trade_no', ''));
        $channel_trade_no       = trim($Request->getRequestParam('out_trade_no', ''));
        $create_ymdhis          = $Request->getRequestParam('create_ymdhis');


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

        $andx->add($queryBuilder->expr()->eq('t.user_id', ':user_id'));
        $queryBuilder->setParameter('user_id', $user_id);
        $has_where = true;

        $andx->add($queryBuilder->expr()->in('t.status', ':status'));
        $queryBuilder->setParameter('status', [Code::STATUS_PAYOK, Code::STATUS_PAYED]);
        $has_where = true;

        if(!empty($channel)){
            $andx->add($queryBuilder->expr()->eq('t.merchant_channel_type', ':merchant_channel_type'));
            $queryBuilder->setParameter('merchant_channel_type', $channel);
            $has_where  = true;
        }
        if($qirifu_trade_no !== ''){
            $andx->add($queryBuilder->expr()->like('t.qirifu_trade_no', ':qirifu_trade_no'));
            $queryBuilder->setParameter('qirifu_trade_no', "{$qirifu_trade_no}%");
            $has_where  = true;
        }
        if($channel_trade_no !== ''){
            $andx->add($queryBuilder->expr()->like('t.link_man', ':channel_trade_no'));
            $queryBuilder->setParameter('channel_trade_no', "{$channel_trade_no}%");
            $has_where  = true;
        }
        if(!empty($create_ymdhis)){
            $andx->add($queryBuilder->expr()->gte('t.create_time', ':create_symdhis'));
            $andx->add($queryBuilder->expr()->lte('t.create_time', ':create_eymdhis'));
            $queryBuilder->setParameter('create_symdhis', strtotime($create_ymdhis[0] . " 00:00:00"));
            $queryBuilder->setParameter('create_eymdhis', strtotime($create_ymdhis[1] . " 23:59:60"));
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
        $qirifu_trade_no        = trim($Request->getRequestParam('in_trade_no', ''));
        $channel_trade_no       = trim($Request->getRequestParam('out_trade_no', ''));
        $create_ymdhis          = $Request->getRequestParam('create_ymdhis');


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
        if($qirifu_trade_no !== ''){
            $andx->add($queryBuilder->expr()->like('t.qirifu_trade_no', ':qirifu_trade_no'));
            $queryBuilder->setParameter('qirifu_trade_no', "{$qirifu_trade_no}%");
            $has_where  = true;
        }
        if($channel_trade_no !== ''){
            $andx->add($queryBuilder->expr()->like('t.link_man', ':channel_trade_no'));
            $queryBuilder->setParameter('channel_trade_no', "{$channel_trade_no}%");
            $has_where  = true;
        }
        if(!empty($create_ymdhis)){
            $andx->add($queryBuilder->expr()->gte('t.create_time', ':create_symdhis'));
            $andx->add($queryBuilder->expr()->lte('t.create_time', ':create_eymdhis'));
            $queryBuilder->setParameter('create_symdhis', strtotime($create_ymdhis[0]));
            $queryBuilder->setParameter('create_symdhis', strtotime($create_ymdhis[1]));
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