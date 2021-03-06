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
     *
     * @param array $user_ids
     * @return Entity|NULL
     */
    public function findAllByUserIds(array $user_ids) : ?Entity
    {
        return $this->Repository->findBy(['user_id' => $user_ids]);
    }

    /**
     *
     * @param array $user_ids
     * @return array
     */
    public function findNamesByUserIds(array $user_ids) : array
    {
        $queryBuilder   = $this->Repository->createQueryBuilder('t');

        $andx           = $queryBuilder->expr()->andX();
        $andx->add($queryBuilder->expr()->in('t.user_id', ':user_ids'));

        $queryBuilder->setParameter('user_ids', array_values($user_ids));
        $queryBuilder->where($andx);

        $queryBuilder->select('t.user_id, t.name');

        $query_result   = $queryBuilder->getQuery()->getArrayResult();

        $result         = [];
        foreach($query_result AS $item){
            $user_id            = $item['user_id'];
            $result[$user_id]   = $item['name'];
        }

        return $result;
    }

    /**
     *
     * @param string $merchant_name
     * @return array|string[]
     */
    public function getUserIdsLikeName(string $merchant_name) : array
    {
        $queryBuilder   = $this->Repository->createQueryBuilder('t');

        $andx           = $queryBuilder->expr()->andX();
        $andx->add($queryBuilder->expr()->like('t.name', ':merchant_name'));

        $queryBuilder->setParameter('merchant_name', "{$merchant_name}%");
        $queryBuilder->where($andx);

        $queryBuilder->select('t.user_id');

        $query_result   = $queryBuilder->getQuery()->getArrayResult();

        $result         = [];
        foreach($query_result AS $item){
            $result[]   = $item['user_id'];
        }

        return $result;
    }

    public function mappingNames(array &$data) : void
    {
        $user_ids   = [];
        foreach($data AS $item){
            $user_id            = $item['user_id'];
            $user_ids[$user_id] = $user_id;
        }

        $merchant_names  = $this->findNamesByUserIds($user_ids);

        foreach($data AS $key => $item){
            $user_id                        = $item['user_id'];
            $data[$key]['merchant_name']    = $merchant_names[$user_id];
        }
    }


    /**
     *
     * @param array $user_ids
     * @return array
     */
    public function findArrByUserIds(array $user_ids) : array
    {
        $queryBuilder   = $this->Repository->createQueryBuilder('t');

        $andx           = $queryBuilder->expr()->andX();
        $andx->add($queryBuilder->expr()->in('t.user_id', ':user_ids'));

        $queryBuilder->setParameter('user_ids', array_values($user_ids));
        $queryBuilder->where($andx);

        $queryBuilder->select('t.seq, t.user_id, t.name');

        $query_result   = $queryBuilder->getQuery()->getArrayResult();

        $result         = [];
        foreach($query_result AS $item){
            $user_id            = $item['user_id'];
            $result[$user_id]   = $item;
        }

        return $result;
    }

    /**
     *
     * @param array $data
     */
    public function mappingMerchantInfos(array &$data) : void
    {
        $user_ids   = [];
        foreach($data AS $item){
            $user_id            = $item['user_id'];
            $user_ids[$user_id] = $user_id;
        }

        $merchants  = $this->findArrByUserIds($user_ids);

        foreach($data AS $key => $item){
            $user_id                = $item['user_id'];
            $data[$key]['merchant'] = $merchants[$user_id];
        }
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