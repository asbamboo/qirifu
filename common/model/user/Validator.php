<?php
namespace asbamboo\qirifu\common\model\user;

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
     * @param number $user_balance
     * @throws MessageException
     */
    private function validateBalance($user_balance)
    {
        if(ctype_digit((string) $user_balance) == false){
            throw new MessageException('余额只能输入数字');
        }
        if(strlen($user_balance) > 14){
            throw new MessageException('余额超出了系统可控制范围。   ');
        }
    }
}