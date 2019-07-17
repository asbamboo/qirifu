<?php

namespace asbamboo\qirifu\common\model\merchantDetail;

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
     * @var int
     */
    private $merchant_seq = '0';

    /**
     * @var array
     */
    private $data;


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
     * Set merchantSeq.
     *
     * @param int $merchantSeq
     *
     * @return Entity
     */
    public function setMerchantSeq($merchantSeq)
    {
        $this->merchant_seq = $merchantSeq;

        return $this;
    }

    /**
     * Get merchantSeq.
     *
     * @return int
     */
    public function getMerchantSeq()
    {
        return $this->merchant_seq;
    }

    /**
     * Set data.
     *
     * @param array $data
     *
     * @return Entity
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * @var string
     */
    private $user_id = '';


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
}
