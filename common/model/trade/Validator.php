<?php
namespace asbamboo\qirifu\common\model\trade;

use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\qirifu\common\model\merchantChannel\Code AS MerchantChannelCode;
use asbamboo\qirifu\common\exception\SystemException;

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
     * @param int $merchant_channel_type
     * @throws SystemException
     */
    public function validateMerchantChannelType(int $merchant_channel_type)
    {
        if(!isset(MerchantChannelCode::TYPES[$merchant_channel_type])){
            throw new SystemException('支付方式无效。');
        }
    }

    /**
     *
     * @param string $price
     * @throws MessageException
     */
    public function validatePrice(string $price)
    {
        if(empty($price)){
            throw new MessageException('请输入支付金额。');
        }

        $explode_price  = explode('.', $price);

        if(count($explode_price) > 2){
            throw new MessageException('支付金额只能是数字。');
        }

        if($price < 0){
            throw new MessageException('支付金额必须大于0。');
        }


        if($price > 999999999999){
            throw new MessageException('支付金额只能超出系统支持范围。');
        }

        if(isset($explode_price[1]) && $explode_price[1] > 99){
            throw new MessageException('支付金额小数部分最多输入2位。');
        }
    }
}