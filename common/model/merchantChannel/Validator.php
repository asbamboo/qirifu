<?php
namespace asbamboo\qirifu\common\model\merchantChannel;

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
    public function validateStatus(int $merchant_channel_status)
    {
        if(!isset(Code::STATUSS[$merchant_channel_status])){
            throw new MessageException('非法操作,系统不存在这种支付通道申请状态。');
        }
    }
}