<?php
namespace asbamboo\qirifu\common\alipay;

use asbamboo\qirifu\common\alipay\response\ResponseInterface;

/**
 * 支付宝支付请求接口
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月22日
 */
interface ApiClientInterface
{
    /**
     * 发起一个接口请求
     *
     * @param string $api_name api请求构件名称 如 ScanQRCodeByPayUnifiedorder
     * @param array $assign_data api请求构件指派的数据集
     * @return ResponseInterface 响应结果
     */
    public static function request(string $api_name, array $assign_data = []) : ResponseInterface;
}
