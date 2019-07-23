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
    const STATUS_CREATE             = '0';
    const STATUS_PAYING             = '1';
    const STATUS_PAYFAILED          = '2';
    const STATUS_CANCLE             = '3';
    const STATUS_PAYOK              = '8';
    const STATUS_PAYED              = '9';
    /********************************************************************************************************/
}
