<?php
namespace asbamboo\qirifu\common\model\upload;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\security\user\AnonymousUser;
use asbamboo\security\user\token\UserTokenInterface;

/**
 * 管理账号表的操作
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
     * @var UserTokenInterface
     */
    private $UserToken;

    /**
     *
     * @param FactoryInterface $Db
     */
    public function __construct(FactoryInterface $Db, UserTokenInterface $UserToken, ServerRequestInterface $ServerRequest, Repository $Repository)
    {
        $this->Db               = $Db;
        $this->ServerRequest    = $ServerRequest;
        $this->Repository       = $Repository;
        $this->UserToken        = $UserToken;
    }

    /**
     *
     * @param int|Entity $account
     * @throws MessageException
     * @return Entity
     */
    public function load(/*int|Entity*/ $upload = null) : Entity
    {
        if(is_null($upload)){
            $this->Entity   = new Entity();
        }else if($upload instanceof Entity){
            $this->Entity   = $upload;
        }else{
            $this->Entity   = $this->Repository->findOneByFileid($upload);
            if(empty($this->Entity)){
                throw new MessageException('文件不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     * 生成文件的id
     *
     * @return string
     */
    public function genrateFileid() : string
    {
        return uniqid();
    }

    /**
     *
     * @param string $fileid
     * @param string $name
     * @param string $path
     * @param string $media_type
     * @return Manager
     */
    public function create(string $fileid, string $name, string $path, string $media_type) : Manager
    {
        /**
         *
         * @var \asbamboo\tuitui\common\login\User $LoginUser
         */
        $LoginUser  = $this->UserToken->getUser();
        $user_id    = $LoginUser instanceof AnonymousUser ? '' : $LoginUser->getUserId();

        $this->Entity->setFileid($fileid);
        $this->Entity->setName($name);
        $this->Entity->setPath($path);
        $this->Entity->setMediaType($media_type);
        $this->Entity->setCreateTime(time());
        $this->Entity->setCreateUser($user_id);
        $this->Entity->setCreateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());
        $this->Entity->setUpdateUser($user_id);
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());

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
        $this->validateName($this->Entity->getName());
        $this->validatePath($this->Entity->getPath());
    }

    public function validateCreateOrUpdate()
    {

    }
}