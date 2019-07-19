<?php
namespace asbamboo\qirifu\common\alipay;

use asbamboo\qirifu\common\alipay\response\ResponseInterface;
use asbamboo\qirifu\common\alipay\request\RequestInterface;
use asbamboo\http\RequestInterface AS HttpRequestInterface;
use asbamboo\http\ResponseInterface AS HttpResponseInterface;
use asbamboo\http\Client AS HttpClient;
use asbamboo\qirifu\common\exception\SystemException;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年10月22日
 */
class APiClient implements ApiClientInterface
{
    /**
     *
     * @param string $api_name
     * @param array $assign_data
     * @return ResponseInterface
     */
    public static function request(string $api_name, array $assign_data = []) : ResponseInterface
    {
        $Request        = static::createRequest($api_name);
        $Request        = $Request->assignData($assign_data);
        $HttpRequest    = $Request->create();
        $HttpResponse   = static::sendRequest($HttpRequest);
        $Response       = static::transformResponse($api_name, $HttpResponse);

        return $Response;
    }

    /**
     * 返回一个Http Request实例
     *
     * @param string $api_name
     * @throws SystemException
     * @return RequestInterface
     */
    public static function createRequest(string $api_name) : RequestInterface
    {
        $class_name         = __NAMESPACE__ . "\\request\\{$api_name}";
        if(!class_exists($class_name)){
            throw new SystemException(sprintf('找不到请求支付宝接口相关的类：%s', $api_name));
        }
        return new $class_name;
    }

    /**
     * 发送请求并且返回得到的响应值
     *
     * @param HttpRequestInterface $HttpRequest
     * @return HttpResponseInterface
     */
    private static function sendRequest(HttpRequestInterface $HttpRequest) : HttpResponseInterface
    {
        static $Client  = null;
        if(is_null($Client)){
            $Client = new HttpClient();
        }
        return $Client->send($HttpRequest);
    }

    /**
     *
     * @param string $api_name
     * @param HttpResponseInterface $HttpResponse
     * @throws SystemException
     * @return ResponseInterface
     */
    private static function transformResponse(string $api_name, HttpResponseInterface $HttpResponse) : ResponseInterface
    {
        $response_class     = __NAMESPACE__ . "\\response\\{$api_name}Response";
        if(!class_exists($response_class)){
            throw new SystemException(sprintf('%s接口:找不到转换响应值的类。', $api_name));
        }
        return new $response_class($HttpResponse);
    }
}
