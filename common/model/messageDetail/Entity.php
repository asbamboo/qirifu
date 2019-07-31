<?php

namespace asbamboo\qirifu\common\model\messageDetail;

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
    private $message_seq = '0';

    /**
     * @var string
     */
    private $content;


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
     * Set messageSeq.
     *
     * @param int $messageSeq
     *
     * @return Entity
     */
    public function setMessageSeq($messageSeq)
    {
        $this->message_seq = $messageSeq;

        return $this;
    }

    /**
     * Get messageSeq.
     *
     * @return int
     */
    public function getMessageSeq()
    {
        return $this->message_seq;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Entity
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
