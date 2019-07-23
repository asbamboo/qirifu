<?php

namespace asbamboo\qirifu\common\model\trade;

/**
 * Entity
 */
class Entity
{
    /**
     * @var int
     */
    private $seq;

    /**
     * @var string
     */
    private $qirifu_trade_no = '';

    /**
     * @var string
     */
    private $channel_trade_no = '';

    /**
     * @var string
     */
    private $user_id = '';

    /**
     * @var int
     */
    private $merchant_channel_type = '0';

    /**
     * @var string
     */
    private $price = '0.0000';

    /**
     * @var int
     */
    private $status = '0';

    /**
     * @var int
     */
    private $create_time = '0';

    /**
     * @var string
     */
    private $create_ip = '';

    /**
     * @var int
     */
    private $update_time = '0';

    /**
     * @var string
     */
    private $update_ip = '';

    /**
     * @var int
     */
    private $version = '0';


    /**
     * Get seq.
     *
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * Set qirifuTradeNo.
     *
     * @param string $qirifuTradeNo
     *
     * @return Entity
     */
    public function setQirifuTradeNo($qirifuTradeNo)
    {
        $this->qirifu_trade_no = $qirifuTradeNo;

        return $this;
    }

    /**
     * Get qirifuTradeNo.
     *
     * @return string
     */
    public function getQirifuTradeNo()
    {
        return $this->qirifu_trade_no;
    }

    /**
     * Set channelTradeNo.
     *
     * @param string $channelTradeNo
     *
     * @return Entity
     */
    public function setChannelTradeNo($channelTradeNo)
    {
        $this->channel_trade_no = $channelTradeNo;

        return $this;
    }

    /**
     * Get channelTradeNo.
     *
     * @return string
     */
    public function getChannelTradeNo()
    {
        return $this->channel_trade_no;
    }

    /**
     * Set userId.
     *
     * @param string $userId
     *
     * @return Entity
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set merchantChannelType.
     *
     * @param int $merchantChannelType
     *
     * @return Entity
     */
    public function setMerchantChannelType($merchantChannelType)
    {
        $this->merchant_channel_type = $merchantChannelType;

        return $this;
    }

    /**
     * Get merchantChannelType.
     *
     * @return int
     */
    public function getMerchantChannelType()
    {
        return $this->merchant_channel_type;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return Entity
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Entity
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createTime.
     *
     * @param int $createTime
     *
     * @return Entity
     */
    public function setCreateTime($createTime)
    {
        $this->create_time = $createTime;

        return $this;
    }

    /**
     * Get createTime.
     *
     * @return int
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set createIp.
     *
     * @param string $createIp
     *
     * @return Entity
     */
    public function setCreateIp($createIp)
    {
        $this->create_ip = $createIp;

        return $this;
    }

    /**
     * Get createIp.
     *
     * @return string
     */
    public function getCreateIp()
    {
        return $this->create_ip;
    }

    /**
     * Set updateTime.
     *
     * @param int $updateTime
     *
     * @return Entity
     */
    public function setUpdateTime($updateTime)
    {
        $this->update_time = $updateTime;

        return $this;
    }

    /**
     * Get updateTime.
     *
     * @return int
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * Set updateIp.
     *
     * @param string $updateIp
     *
     * @return Entity
     */
    public function setUpdateIp($updateIp)
    {
        $this->update_ip = $updateIp;

        return $this;
    }

    /**
     * Get updateIp.
     *
     * @return string
     */
    public function getUpdateIp()
    {
        return $this->update_ip;
    }

    /**
     * Set version.
     *
     * @param int $version
     *
     * @return Entity
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
    /**
     * @var int
     */
    private $payok_time = '0';

    /**
     * @var int
     */
    private $payed_time = '0';

    /**
     * @var int
     */
    private $cancel_time = '0';


    /**
     * Set payokTime.
     *
     * @param int $payokTime
     *
     * @return Entity
     */
    public function setPayokTime($payokTime)
    {
        $this->payok_time = $payokTime;

        return $this;
    }

    /**
     * Get payokTime.
     *
     * @return int
     */
    public function getPayokTime()
    {
        return $this->payok_time;
    }

    /**
     * Set payedTime.
     *
     * @param int $payedTime
     *
     * @return Entity
     */
    public function setPayedTime($payedTime)
    {
        $this->payed_time = $payedTime;

        return $this;
    }

    /**
     * Get payedTime.
     *
     * @return int
     */
    public function getPayedTime()
    {
        return $this->payed_time;
    }

    /**
     * Set cancelTime.
     *
     * @param int $cancelTime
     *
     * @return Entity
     */
    public function setCancelTime($cancelTime)
    {
        $this->cancel_time = $cancelTime;

        return $this;
    }

    /**
     * Get cancelTime.
     *
     * @return int
     */
    public function getCancelTime()
    {
        return $this->cancel_time;
    }
}
