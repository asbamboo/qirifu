<?php

namespace asbamboo\qirifu\common\model\user;

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
    private $user_id = '';

    /**
     * @var int
     */
    private $type = '0';

    /**
     * @var string
     */
    private $password = '';

    /**
     * @var bool
     */
    private $is_enable = '0';

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
     * Set type.
     *
     * @param int $type
     *
     * @return Entity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return Entity
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isEnable.
     *
     * @param bool $isEnable
     *
     * @return Entity
     */
    public function setIsEnable($isEnable)
    {
        $this->is_enable = $isEnable;

        return $this;
    }

    /**
     * Get isEnable.
     *
     * @return bool
     */
    public function getIsEnable()
    {
        return $this->is_enable;
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
