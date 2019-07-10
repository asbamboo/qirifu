<?php
namespace asbamboo\qirifu\common\model\account;

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
     * @param string $account_value
     * @param int $account_type
     * @throws MessageException
     */
    public function validateValue($account_value, $account_type)
    {
        if($account_value == ''){
            throw new MessageException(Code::TYPES[$account_type] . '不能为空。');
        }
        if(mb_strlen($account_value) > 255){
            throw new MessageException(Code::TYPES[$account_type] . '不能超过255个字。');
        }
        if($account_type == Code::TYPE_ACCOUNT){
            if(preg_match('@^\w{4,}$@', $account_value) == 0 || ctype_digit($account_value) == true){
                throw new MessageException(Code::TYPES[$account_type] . '由4个以上字母+数字+下划线组成,并且不能是纯数字。');
            }
        }
        if($account_type == Code::TYPE_EMAIL){
            if(strpos($account_value, '@') === false){
                throw new MessageException(Code::TYPES[$account_type] . '格式错误。');
            }
        }
        if($account_type == Code::TYPE_PHONE){
            if(ctype_digit($account_value) == false){
                throw new MessageException(Code::TYPES[$account_type] . '格式错误。');
            }
        }
    }
}