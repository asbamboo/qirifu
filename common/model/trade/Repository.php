<?php
namespace asbamboo\qirifu\common\model\trade;

use asbamboo\database\FactoryInterface;
use asbamboo\database\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\model\merchant\Repository AS MerchantRepository;

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
     * @var MerchantRepository
     */
    private $MerchantRepository;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, MerchantRepository $MerchantRepository)
    {
        $this->Db                   = $Db;
        $this->Repository           = $this->Db->getManager()->getRepository(Entity::class);
        $this->MerchantRepository   = $MerchantRepository;
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
     *
     * @param int $limit_size
     * @param int $limit_start_seql
     * @return array
     */
    public function noPayQirifuTradeNos(int $max_create_time, int $limit_size = 10, int $limit_start_seq = 0) : array
    {
        $queryBuilder   = $this->Repository->createQueryBuilder('t');

        $queryBuilder->select(['t.seq', 't.qirifu_trade_no']);
        $queryBuilder->orderBy('t.seq', 'ASC');
        $queryBuilder->setMaxResults($limit_size);

        $andx           = $queryBuilder->expr()->andX();

        $andx->add($queryBuilder->expr()->lt('t.create_time', ':create_time'));
        $queryBuilder->setParameter('create_time', $max_create_time);

        $andx->add($queryBuilder->expr()->in('t.status', ':status'));
        $queryBuilder->setParameter('status', [Code::STATUS_NOPAY, Code::STATUS_PAYING, Code::STATUS_PAYFAILED]);

        $andx->add($queryBuilder->expr()->gt('t.seq', ':limit_start_seq'));
        $queryBuilder->setParameter('limit_start_seq', $limit_start_seq);

        $queryBuilder->where($andx);

        $query_result   = $queryBuilder->getQuery()->getArrayResult();

        $result         = [];
        foreach($query_result AS $item){
            $trade_seq          = $item['seq'];
            $result[$trade_seq] = $item['qirifu_trade_no'];
        }

        return $result;
    }

    /**
     *
     * @param int $limit_size
     * @param int $limit_start_seq
     * @return array|Entity[]
     */
    public function getNoPayListsByAdmin(ServerRequestInterface $Request) : ?array
    {
        $limit_size             = $Request->getQueryParam('limit', 100);
        $limit_start_seq        = $Request->getRequestParam('start_seq');

        $queryBuilder   = $this->Repository->createQueryBuilder('t');

        $queryBuilder->orderBy('t.seq', 'ASC');
        $queryBuilder->setMaxResults($limit_size);

        $andx           = $queryBuilder->expr()->andX();

        $andx->add($queryBuilder->expr()->in('t.status', ':status'));
        $queryBuilder->setParameter('status', [Code::STATUS_NOPAY, Code::STATUS_PAYING]);

        $andx->add($queryBuilder->expr()->gt('t.seq', ':limit_start_seq'));
        $queryBuilder->setParameter('limit_start_seq', $limit_start_seq);

        $queryBuilder->where($andx);

        return $queryBuilder->getQuery()->getResult();
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
        $merchant_name          = trim($Request->getRequestParam('merchant_name', ''));
        $status                 = $Request->getRequestParam('status');
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

        if($merchant_name !== ''){
            $user_ids   = $this->MerchantRepository->getUserIdsLikeName($merchant_name);
            $andx->add($queryBuilder->expr()->in('t.user_id', ':user_id'));
            $queryBuilder->setParameter('user_id', $user_ids);
            $has_where = true;
        }
        if(!empty($status) && $status != Code::STATUS_PAYED){
            $andx->add($queryBuilder->expr()->eq('t.status', ':status'));
            $queryBuilder->setParameter('status', $status);
            $has_where  = true;
        }
        if(!empty($status) && $status == Code::STATUS_PAYED){
            $andx->add($queryBuilder->expr()->in('t.status', ':status'));
            $queryBuilder->setParameter('status', [Code::STATUS_PAYOK, Code::STATUS_PAYED]);
            $has_where  = true;
        }
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
}