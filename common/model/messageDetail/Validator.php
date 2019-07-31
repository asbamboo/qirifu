<?php
namespace asbamboo\qirifu\common\model\messageDetail;

use asbamboo\qirifu\common\exception\MessageException;

/**
 * 字段验证器
 *  - 确保字段时数据可写入的字段
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
trait Validator
{
    /**
     *
     * @param string $message_title
     * @throws MessageException
     */
    public function validateContent(string $message_detail_content)
    {
        if(mb_strlen($message_detail_content) > 65535){
            throw new MessageException('消息内容过长[65,535]');
        }
    }
}