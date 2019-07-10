<?php
namespace asbamboo\qirifu\common\model\sysDbLog;

use asbamboo\qirifu\common\exception\SystemException;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\user\AnonymousUser;

class Manager
{
    /**
     *
     * @var FactoryInterface
     */
    private $Db;

    /**
     *
     * @var UserTokenInterface
     */
    private $UserToken;

    /**
     *
     * @var ServerRequestInterface
     */
    private $Request;

    /**
     *
     * @var Repository
     */
    private $Repository;

    /**
     *
     * @var Entity
     */
    private $Entity;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, UserTokenInterface $UserToken, ServerRequestInterface $Request, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->UserToken        = $UserToken;
        $this->Request          = $Request;
        $this->Repository       = $Repository;
    }

    /**
     *
     * @param string|Entity $account
     * @return Entity
     */
    public function load(/*string|Entity*/ $sys_db_log = null) : Entity
    {
        if(is_null($sys_db_log)){
            $this->Entity   = new Entity();
        }else if($sys_db_log instanceof Entity){
            $this->Entity   = $sys_db_log;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($sys_db_log);
            if(empty($this->Entity)){
                throw new SystemException('数据变更日志不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     * 创建一个数据日志
     *
     * @param string $request_url
     * @param string $table
     * @param string $uniqid
     * @param string $data
     * @param string $request_info
     * @return Manager
     */
    public function create($request_url, $table, $uniqid, $data, $request_info) : Manager
    {
        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->Entity->setRequestUrl($request_url);
        $this->Entity->setTable($table);
        $this->Entity->setUniqid($uniqid);
        $this->Entity->setData($data);
        $this->Entity->setRequestInfo($request_info);
        $this->Entity->setCreateUser($user_id);
        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateIp($this->Request->getClientIp());

        $metadata   = $this->Db->getManager()->getClassMetadata(Entity::class);
        $table_name = $metadata->getTableName();
        $data       = [
            $metadata->getColumnName('request_url')     => $this->Entity->getRequestUrl(),
            $metadata->getColumnName('table')           => $this->Entity->getTable(),
            $metadata->getColumnName('uniqid')          => $this->Entity->getUniqid(),
            $metadata->getColumnName('data')            => $this->Entity->getData(),
            $metadata->getColumnName('request_info')    => $this->Entity->getRequestInfo(),
            $metadata->getColumnName('create_user')     => $this->Entity->getCreateUser(),
            $metadata->getColumnName('create_time')     => $this->Entity->getCreateTime(),
            $metadata->getColumnName('create_ip')       => $this->Entity->getCreateIp(),
        ];
        $this->Db->getManager()->getConnection()->insert($table_name, $data);

        return $this;
    }
}
