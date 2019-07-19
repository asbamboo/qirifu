<?php
namespace asbamboo\qirifu\common\alipay\request;
use asbamboo\qirifu\common\alipay\gateway\GatewayUriTrait;
use asbamboo\qirifu\common\alipay\request\tool\BodyTrait;
use asbamboo\qirifu\common\alipay\request\tool\UriTrait;
use asbamboo\qirifu\common\alipay\request\tool\CreateRequestTrait;
use asbamboo\qirifu\common\alipay\requestParams\bizContent\OpenAuthTokenAppParams;
use asbamboo\qirifu\common\alipay\requestParams\CommonParams;

/**
 * alipay.open.auth.token.app
 * 换取应用授权令牌
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class OpenAuthTokenApp implements RequestInterface
{
    use GatewayUriTrait;
    use BodyTrait;
    use UriTrait;
    use CreateRequestTrait;

    /**
     * 接口请求的method参数的固定值
     *
     * @var string
     */
    const METHOD    = 'alipay.open.auth.token.app';

    /**
     * 指派参数的数据集合
     *
     * @var array
     */
    private $assign_data;

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\qirifu\common\alipay\request\RequestInterface::assignData()
     */
    public function assignData(array $assign_data): RequestInterface
    {
        $BizContent     = new OpenAuthTokenAppParams();
        $CommonParams   = new CommonParams();

        $BizContent->mappingData($assign_data);
        $CommonParams->mappingData($assign_data);
        $CommonParams->setBizContent($BizContent);

        $CommonParams->method   = self::METHOD;
        $CommonParams->sign     = $CommonParams->makeSign();
        $this->assign_data      = get_object_vars($CommonParams);

        return $this;
    }

    /**
     *
     * @return array|NULL
     */
    public function getAssignData() : ?array
    {
        return $this->assign_data;
    }
}