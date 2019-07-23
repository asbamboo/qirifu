<?php
namespace asbamboo\qirifu\common\model\trade;

use asbamboo\database\FactoryInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use Doctrine\DBAL\LockMode;
use asbamboo\qirifu\common\exception\SystemException;

/**
 * 管理交易表的操作
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
    public function load(/*int|Entity*/ $trade = null) : Entity
    {
        if(is_null($trade)){
            $this->Entity   = new Entity();
        }else if($trade instanceof Entity){
            $this->Entity   = $trade;
        }else{
            $this->Entity   = $this->Repository->findOneByQirifuTradeNo($trade);
            if(empty($this->Entity)){
                throw new MessageException('交易不存在。');
            }
        }
        return $this->Entity;
    }

    /**
     * 生成文件的id
     *
     * @return string
     */
    public function genrateQirifuTradeNo() : string
    {
        $time   = date("ymdHis");
        $random = str_pad(base_convert(mt_rand(0, base_convert('ZZZ', 36, 10)), 10, 36), 3, '0');
        $ip     = substr(base_convert(ip2long($this->ServerRequest->getClientIp()), 10, 36), -7);
        return  strtoupper($time.$random.$ip);
    }

    /**
     *
     * @param string $fileid
     * @param string $name
     * @param string $path
     * @param string $media_type
     * @return Manager
     */
    public function create(string $user_id, int $merchant_channel_type, string $price) : Manager
    {

        $this->Entity->setUserId($user_id);
        $this->Entity->setMerchantChannelType($merchant_channel_type);
        $this->Entity->setPrice($price);

        $this->validateCreate();

        $this->Entity->setQirifuTradeNo($this->genrateQirifuTradeNo());
        $this->Entity->setStatus(Code::STATUS_NOPAY);
        $this->Entity->setChannelTradeNo('');
        $this->Entity->setCreateIp($this->ServerRequest->getClientIp());
        $this->Entity->setCreateTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());

        $this->validateCreate();

        $this->Db->getManager()->persist($this->Entity);

        return $this;
    }

    /**
     *
     * @param string $channel_trade_no
     * @return Manager
     */
    public function updateChannelTradeNo(string $channel_trade_no) : Manager
    {
        $this->Entity->setChannelTradeNo($channel_trade_no);

        $this->validateUpdateChannelTradeNo();

        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    /**
     *
     * @return Manager
     */
    public function updateCancel() : Manager
    {
        $this->validateUpdateCancel();

        $this->Entity->setStatus(Code::STATUS_CANCLE);
        $this->Entity->setCancelTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    public function updatePayfailed()
    {
        $this->validateUpdatePayfailed();

        $this->Entity->setStatus(Code::STATUS_PAYFAILED);
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    public function updatePaying()
    {
        $this->validateUpdatePaying();

        $this->Entity->setStatus(Code::STATUS_PAYING);
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    public function updatePayok()
    {
        $this->validateUpdatePayok();

        $this->Entity->setStatus(Code::STATUS_PAYOK);
        $this->Entity->setPayokTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    public function updatePayed()
    {
        $this->validateUpdatePayed();

        $this->Entity->setStatus(Code::STATUS_PAYED);
        $this->Entity->setPayedTime(time());
        $this->Entity->setUpdateIp($this->ServerRequest->getClientIp());
        $this->Entity->setUpdateTime(time());

        $this->Db->getManager()->lock($this->Entity, LockMode::OPTIMISTIC);

        return $this;
    }

    /**
     * 验证
     *
     * @throws MessageException
     */
    public function validateCreate()
    {
        $this->validateMerchantChannelType($this->Entity->getMerchantChannelType());
        $this->validatePrice($this->Entity->getPrice());
    }

    public function validateUpdateChannelTradeNo()
    {
    }

    public function validateUpdateCancel()
    {
        if(!in_array($this->Entity->getStatus(), [Code::STATUS_CANCLE, Code::STATUS_NOPAY, Code::STATUS_PAYING, Code::STATUS_PAYFAILED])){
            throw new SystemException('只有未完成支付状态的订单可以取消');
        }
    }

    public function validateUpdatePayfailed()
    {
        if(!in_array($this->Entity->getStatus(), [Code::STATUS_NOPAY, Code::STATUS_PAYING, Code::STATUS_PAYFAILED])){
            throw new SystemException('只有未完成支付状态的订单会产生支付失败状态');
        }
    }

    public function validateUpdatePaying()
    {
        if(!in_array($this->Entity->getStatus(), [Code::STATUS_NOPAY, Code::STATUS_PAYING, Code::STATUS_PAYFAILED])){
            throw new SystemException('只有未完成支付状态的订单可以发起支付');
        }
    }

    public function validateUpdatePayok()
    {
        if(!in_array($this->Entity->getStatus(), [Code::STATUS_NOPAY, Code::STATUS_PAYING, Code::STATUS_PAYFAILED, Code::STATUS_PAYOK])){
            throw new SystemException('只有未完成支付的订单状态可以修改为支付成功');
        }
    }

    public function validateUpdatePayed()
    {
        if(!in_array($this->Entity->getStatus(), [Code::STATUS_NOPAY, Code::STATUS_PAYING, Code::STATUS_PAYFAILED, Code::STATUS_PAYOK])){
            throw new SystemException('只有未完成支付的订单状态可以修改为支付成功并不可退款');
        }
    }
}