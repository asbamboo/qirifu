<?php
namespace asbamboo\qirifu\common\model\message;

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
    public function validateTitle(string $message_title)
    {
        if(empty($message_title)){
            throw new MessageException('请输入消息标题');
        }
        if(mb_strlen($message_title) > 45){
            throw new MessageException('消息标题过长[45]');
        }
    }
}