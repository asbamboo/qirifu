<?php
namespace asbamboo\qirifu\common\model\trade;

/**
 * 数据表枚举字段值
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Code
{
    /*********************************************************************************************************
     * status 字段
     *********************************************************************************************************/
    const STATUSS                   = [
        self::STATUS_NOPAY          => '未支付',
        self::STATUS_PAYING         => '支付中',
        self::STATUS_PAYFAILED      => '支付失败',
        self::STATUS_CANCLE         => '取消支付',
        self::STATUS_PAYOK          => '支付成功',
        self::STATUS_PAYED          => '支付成功',
    ];

    const STATUS_NOPAY              = '0';
    const STATUS_PAYING             = '1';
    const STATUS_PAYFAILED          = '2';
    const STATUS_CANCLE             = '3';
    const STATUS_PAYOK              = '8';
    const STATUS_PAYED              = '9';
    /********************************************************************************************************/
}
