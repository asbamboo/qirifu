<?php
namespace asbamboo\qirifu\common\model\user;

use asbamboo\database\FactoryInterface;
use asbamboo\database\EntityRepository;
use asbamboo\http\ServerRequestInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use asbamboo\qirifu\common\model\account\Repository AS AccountRepository;

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
     * @var AccountRepository
     */
    private $AccountRepository;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, AccountRepository $AccountRepository)
    {
        $this->Db                   = $Db;
        $this->Repository           = $this->Db->getManager()->getRepository(Entity::class);
        $this->AccountRepository    = $AccountRepository;
    }

    /**
     * 通过user_id查询一个用户信息
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
     *
     * @param array $uesr_ids
     * @return array
     */
    public function findByUserIds(array $uesr_ids)
    {
        return $this->Repository->findBy(['user_id' => $uesr_ids]);
    }

    /**
     * 后台(普通用户)列表页面
     *
     * @param ServerRequestInterface $Request
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getUserListPaginatorByAdmin(ServerRequestInterface $Request) : Paginator
    {
        /**
         * request params
         */
        $page_no            = $Request->getQueryParam('page_no', 1);
        $list_size          = $Request->getQueryParam('list_size', 20);
        $user_id            = $Request->getRequestParam('user_id');
        $user_create_symd   = $Request->getRequestParam('user_create_symd');
        $user_create_eymd   = $Request->getRequestParam('user_create_eymd');
        $account_value      = $Request->getRequestParam('account_value');

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
        $uniwhere   = false;
        if($user_id !== ''){
            $andx->add($queryBuilder->expr()->eq('t.user_id', ':user_id'));
            $queryBuilder->setParameter('user_id', $user_id);
            $uniwhere   = true;
        }
        if($uniwhere == false && $account_value !== ''){
            $user_id        = '';
            $AccountEntity  = $this->AccountRepository->findOneByValue($account_value);
            $user_id        = $AccountEntity ? $AccountEntity->getUserId() : $user_id;
            $andx->add($queryBuilder->expr()->eq('t.user_id', ':user_id'));
            $queryBuilder->setParameter('user_id', $user_id);
            $uniwhere   = true;
        }
        if(!$uniwhere){
            if($user_create_symd !== ''){
                $andx->add($queryBuilder->expr()->gte('t.create_time', ':start_time'));
                $queryBuilder->setParameter('start_time', strtotime($user_create_symd . ' 00:00"00'));
            }
            if($user_create_eymd !== ''){
                $andx->add($queryBuilder->expr()->lte('t.create_time', ':end_time'));
                $queryBuilder->setParameter('end_time', strtotime($user_create_eymd . ' 23:59:59'));
            }
        }
        $andx->add($queryBuilder->expr()->eq('t.type', ':type'));
        $queryBuilder->setParameter('type', Code::TYPE_USER);
        $queryBuilder->where($andx);

        /**
         * return
         */
        return new Paginator($queryBuilder);
    }
}