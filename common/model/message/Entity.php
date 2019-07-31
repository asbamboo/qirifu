<?php

namespace asbamboo\qirifu\common\model\message;

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
    private $from_user_id = '';

    /**
     * @var string
     */
    private $to_user_id = '';

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var bool
     */
    private $is_read = '0';

    /**
     * @var int
     */
    private $create_time = '0';

    /**
     * @var string
     */
    private $create_user = '';

    /**
     * @var int
     */
    private $update_time = '0';

    /**
     * @var string
     */
    private $update_user = '';

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
     * Set fromUserId.
     *
     * @param string $fromUserId
     *
     * @return Entity
     */
    public function setFromUserId($fromUserId)
    {
        $this->from_user_id = $fromUserId;

        return $this;
    }

    /**
     * Get fromUserId.
     *
     * @return string
     */
    public function getFromUserId()
    {
        return $this->from_user_id;
    }

    /**
     * Set toUserId.
     *
     * @param string $toUserId
     *
     * @return Entity
     */
    public function setToUserId($toUserId)
    {
        $this->to_user_id = $toUserId;

        return $this;
    }

    /**
     * Get toUserId.
     *
     * @return string
     */
    public function getToUserId()
    {
        return $this->to_user_id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Entity
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set isRead.
     *
     * @param bool $isRead
     *
     * @return Entity
     */
    public function setIsRead($isRead)
    {
        $this->is_read = $isRead;

        return $this;
    }

    /**
     * Get isRead.
     *
     * @return bool
     */
    public function getIsRead()
    {
        return $this->is_read;
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
     * Set createUser.
     *
     * @param string $createUser
     *
     * @return Entity
     */
    public function setCreateUser($createUser)
    {
        $this->create_user = $createUser;

        return $this;
    }

    /**
     * Get createUser.
     *
     * @return string
     */
    public function getCreateUser()
    {
        return $this->create_user;
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
     * Set updateUser.
     *
     * @param string $updateUser
     *
     * @return Entity
     */
    public function setUpdateUser($updateUser)
    {
        $this->update_user = $updateUser;

        return $this;
    }

    /**
     * Get updateUser.
     *
     * @return string
     */
    public function getUpdateUser()
    {
        return $this->update_user;
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
}
