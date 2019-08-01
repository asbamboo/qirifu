<?php

namespace asbamboo\qirifu\common\model\merchant;

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
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $link_man = '';

    /**
     * @var string
     */
    private $link_phone = '';

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
     * Set name.
     *
     * @param string $name
     *
     * @return Entity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set linkMan.
     *
     * @param string $linkMan
     *
     * @return Entity
     */
    public function setLinkMan($linkMan)
    {
        $this->link_man = $linkMan;

        return $this;
    }

    /**
     * Get linkMan.
     *
     * @return string
     */
    public function getLinkMan()
    {
        return $this->link_man;
    }

    /**
     * Set linkPhone.
     *
     * @param string $linkPhone
     *
     * @return Entity
     */
    public function setLinkPhone($linkPhone)
    {
        $this->link_phone = $linkPhone;

        return $this;
    }

    /**
     * Get linkPhone.
     *
     * @return string
     */
    public function getLinkPhone()
    {
        return $this->link_phone;
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
    /**
     * @var string
     */
    private $link_email = '';


    /**
     * Set linkEmail.
     *
     * @param string $linkEmail
     *
     * @return Entity
     */
    public function setLinkEmail($linkEmail)
    {
        $this->link_email = $linkEmail;

        return $this;
    }

    /**
     * Get linkEmail.
     *
     * @return string
     */
    public function getLinkEmail()
    {
        return $this->link_email;
    }
}
