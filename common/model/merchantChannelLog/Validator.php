<?php
namespace asbamboo\qirifu\common\model\merchantChannelLog;

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
     * @param string $merchant_name
     * @throws MessageException
     */
    public function validateDesc(string $merchant_channel_log_desc)
    {
        if(mb_strlen($merchant_channel_log_desc) > 255){
            throw new MessageException('说明过长，超过有效范围[255]。');
        }
    }
}