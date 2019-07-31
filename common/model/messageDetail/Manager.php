<?php
namespace asbamboo\qirifu\common\model\messageDetail;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\security\user\AnonymousUser;
use asbamboo\qirifu\common\model\message\Entity AS MessageEntity;

/**
 * 管理站内信表的操作
 *  - 控制 增/删/改
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Manager
{
    use Validator;

    /**
     *
     * @var FactoryInterface
     */
    private $Db;

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
     * @param UserTokenInterface $UserToken
     * @param ServerRequestInterface $ServerRequest
     * @param Repository $Repository
     */
    public function __construct(FactoryInterface $Db, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->Repository       = $Repository;
    }

    /**
     *
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $message_detail = null) : Entity
    {
        if(is_null($message_detail)){
            $this->Entity   = new Entity();
        }else if($message_detail instanceof Entity){
            $this->Entity   = $message_detail;
        }else{
            $this->Entity   = $this->Repository->findOneBySeq($message_detail);
            if(empty($this->Entity)){
                throw new MessageException('站内信不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     *
     * @param MessageEntity $MessageEntity
     * @param string $content
     * @return Manager
     */
    public function create(MessageEntity $MessageEntity, string $content) : Manager
    {
        $this->Entity->setMessageSeq($MessageEntity->getSeq());
        $this->Entity->setContent($content);

        $this->validateCreate();


        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     * 验证
     *
     * @throws MessageException
     */
    public function validateCreate()
    {
        $this->validateContent($this->Entity->getContent());
    }
}