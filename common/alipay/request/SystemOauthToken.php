<?php
namespace asbamboo\qirifu\common\alipay\request;

use asbamboo\qirifu\common\alipay\gateway\GatewayUriTrait;
use asbamboo\qirifu\common\alipay\request\tool\BodyTrait;
use asbamboo\qirifu\common\alipay\request\tool\UriTrait;
use asbamboo\qirifu\common\alipay\request\tool\CreateRequestTrait;
use asbamboo\qirifu\common\alipay\requestParams\bizContent\SystemOauthTokenParams;

/**
 * alipay.open.auth.token.app
 * 换取应用授权令牌
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月3日
 */
class SystemOauthToken implements RequestInterface
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
    const METHOD    = 'alipay.system.oauth.token';

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
        $SystemOauthTokenParams = new SystemOauthTokenParams();
        $SystemOauthTokenParams->mappingData($assign_data);

        $SystemOauthTokenParams->method   = self::METHOD;
        $SystemOauthTokenParams->sign     = $SystemOauthTokenParams->makeSign();
        $this->assign_data                = get_object_vars($SystemOauthTokenParams);

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