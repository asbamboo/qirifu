<?php
namespace asbamboo\qirifu\common\model\captcha;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;

/**
 * 管理账号表的操作
 *  - 控制 增/删/改
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Manager
{
    /**
     *
     * @var FactoryInterface
     */
    private $Db;

    /**
     *
     * @var ServerRequestInterface
     */
    private $ServerRequest;

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
    public function __construct(FactoryInterface $Db, ServerRequestInterface $ServerRequest, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->ServerRequest    = $ServerRequest;
        $this->Repository       = $Repository;
    }

    /**
     *
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $captcha = null) : Entity
    {
        if(is_null($captcha)){
            $this->Entity   = new Entity();
        }else if($captcha instanceof Entity){
            $this->Entity   = $captcha;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($captcha);
            if(empty($this->Entity)){
                throw new MessageException('验证码信息不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     * 创建数据行
     *
     * @return Manager
     */
    public function create($target, $value) : Manager
    {
        $this->Entity->setTarget($target);
        $this->Entity->setValue($value);
        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());

        $this->validateCreate();

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     * 启用
     *
     * @return Manager
     */
    public function createOrUpdate($target, $value) : Manager
    {
        $this->Entity->setTarget($target);
        $this->Entity->setValue($value);
        if($this->Entity->getSeq()){
            $this->Entity->setUpdateTime(time());
            $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        }else{
            $this->Entity->setCreateTime(time());
            $this->Entity->setCreateIp($this->ServerRequest->getClientIp());
            $this->Db->getManager()->persist($this->Entity);
        }

        $this->validateCreateOrUpdate();

        return $this;
    }

    /**
     * 验证
     *
     * @throws MessageException
     */
    public function validateCreate()
    {
    }

    public function validateCreateOrUpdate()
    {

    }
}