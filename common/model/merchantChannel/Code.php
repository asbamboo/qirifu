<?php
namespace asbamboo\qirifu\common\model\merchantChannel;

/**
 * 数据表枚举字段值
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class Code
{
    /*********************************************************************************************************
     * type 字段
    *********************************************************************************************************/
    const TYPES             = [
        self::TYPE_ALIPAY   => '支付宝支付',
        self::TYPE_WXPAY    => '微信支付',
    ];
    const TYPE_NAMES        = [
        self::TYPE_ALIPAY   => 'alipay',
        self::TYPE_WXPAY    => 'wxpay',
    ];
    const TYPE_ALIPAY       = '1';
    const TYPE_WXPAY        = '2';
    /********************************************************************************************************/

    /*********************************************************************************************************
     * status 字段
     *********************************************************************************************************/
    const STATUSS                  = [
        self::STATUS_NO_APPLY           => '未申请',
        self::STATUS_APPLY              => '商户申请开通',
        self::STATUS_REAPPLY            => '补充或修改资料后，再次申请开通',
        self::STATUS_REFUSE             => '审核未通过，需要商户补充或修改资料',
        self::STATUS_THIRD_REFUSE       => '官方支付通道审核未通过，需要商户补充或修改资料',
        self::STATUS_SEND_THIRD         => '资料已提交到官方支付通道，等待审核',
        self::STATUS_RESEND_THIRD       => '补充或修改资料后，重新提交到官方支付通道，等待审核',
        self::STATUS_WAIT_AUTHORIZATION => '审核通过，等待商户使用官方通道账号授权',
        self::STATUS_OK                 => '正式开通',
    ];
    const STATUS_NAMES                  = [
        self::STATUS_NO_APPLY           => 'no-apply',
        self::STATUS_APPLY              => 'apply-ing',
        self::STATUS_REAPPLY            => 're-apply',
        self::STATUS_REFUSE             => 'refuse',
        self::STATUS_THIRD_REFUSE       => 'third-refuse',
        self::STATUS_SEND_THIRD         => 'send-third',
        self::STATUS_RESEND_THIRD       => 'resend-third',
        self::STATUS_WAIT_AUTHORIZATION => 'wait-authorization',
        self::STATUS_OK                 => 'ok',
    ];
    const STATUS_NO_APPLY           = '0';
    const STATUS_APPLY              = '1';
    const STATUS_REAPPLY            = '2';
    const STATUS_REFUSE             = '3';
    const STATUS_THIRD_REFUSE       = '4';
    const STATUS_SEND_THIRD         = '5';
    const STATUS_RESEND_THIRD       = '6';
    const STATUS_WAIT_AUTHORIZATION = '7';
    const STATUS_OK                 = '99';
    /********************************************************************************************************/
}
