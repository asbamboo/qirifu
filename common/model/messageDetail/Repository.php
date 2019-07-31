<?php
namespace asbamboo\qirifu\common\model\messageDetail;

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
     *
     * @param int $message_detail_seq
     * @return Entity|NULL
     */
    public function findOneBySeq(string $message_detail_seq) : ?Entity
    {
        return $this->Repository->findOneBy(['seq' => $message_detail_seq]);
    }

    /**
     *
     * @param array $message_seqs
     * @return Entity[]
     */
    public function findContentsByMessageSeqs(array $message_seqs) : array
    {
        $queryBuilder   = $this->Repository->createQueryBuilder('t');

        $andx           = $queryBuilder->expr()->andX();
        $andx->add($queryBuilder->expr()->in('t.message_seq', ':message_seqs'));

        $queryBuilder->setParameter('message_seqs', array_values($message_seqs));
        $queryBuilder->where($andx);

        $queryBuilder->select('t.message_seq, t.content');

        $query_result   = $queryBuilder->getQuery()->getArrayResult();

        $result         = [];
        foreach($query_result AS $item){
            $message_seq            = $item['message_seq'];
            $result[$message_seq]   = $item['content'];
        }

        return $result;
    }

    /**
     *
     * @param array $data
     */
    public function mappingContents(array &$data) : void
    {
        $message_seqs   = [];
        foreach($data AS $item){
            $message_seq                = $item['seq'];
            $message_seqs[$message_seq] = $message_seq;
        }

        $contents  = $this->findContentsByMessageSeqs($message_seqs);

        foreach($data AS $key => $item){
            $message_seq            = $item['seq'];
            $data[$key]['content']  = $contents[$message_seq];
        }
    }
}