<?php
namespace asbamboo\qirifu\common\alipay\gateway;

use asbamboo\http\Uri;
use asbamboo\http\UriInterface;
use asbamboo\qirifu\common\alipay\request\RequestInterface;

/**
 * 接口请求网关uri
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月9日
 */
trait GatewayUriTrait
{
    /**
     * 网关uri
     *  - 官方 sandbox uri 等于 https://openapi.alipaydev.com/gateway.do
     *  - 官方 生产 uri 等于 https://openapi.alipay.com/gateway.do
     *
     * @var string
     */
    private $gateway_uri;

    /**
     * 设置请求网关
     *
     * @param string $uri
     * @return RequestInterface
     */
    public function setGateway(string $uri) : RequestInterface
    {
        $this->gateway_uri  = $uri;
        return $this;
    }

    /**
     * 返回请求网关
     *
     * @return string|NULL
     */
    public function getGateway() : ?UriInterface
    {
        if($this->gateway_uri == null){
            $this->gateway_uri    = \Parameter::instance()->get('ALIPAY_SANDBOX') ? 'https://openapi.alipaydev.com/gateway.do' : 'https://openapi.alipay.com/gateway.do';
        }
        return new Uri($this->gateway_uri);
    }
}