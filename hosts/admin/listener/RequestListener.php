<?php
namespace asbamboo\qirifu\hosts\admin\listener;

use asbamboo\router\RouterInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\Constant;
use asbamboo\http\JsonResponse;

/**
 * 监听kernel request 事件
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class RequestListener
{
    /**
     *
     * @var RouterInterface
     */
    private $Router;

    /**
     *
     * @var ServerRequestInterface
     */
    private $Request;

    /**
     *
     * @var UserTokenInterface
     */
    private $UserToken;

    /**
     *
     * @param RouterInterface $Router
     * @param ServerRequestInterface $Request
     * @param UserTokenInterface $UserToken
     */
    public function __construct(RouterInterface $Router, ServerRequestInterface $Request, UserTokenInterface $UserToken)
    {
        $this->Router           = $Router;
        $this->Request          = $Request;
        $this->UserToken        = $UserToken;
    }

    /**
     * 判断用户是否已经登录
     * 未登录的情况下，页面跳转到登录页面
     */
    public function checkIsLogin()
    {
        $cur_url            = $this->Request->getUri()->getPath();
        $cur_roles          = $this->UserToken->getUser()->getRoles();
        $none_login_urls    = [
            $this->Router->generateUrl('home'),
            $this->Router->generateUrl('user_login'),
        ];
        if(!in_array($cur_url, $none_login_urls) && !in_array(Constant::USER_ROLE_ADMIN, $cur_roles)){
            return exit((new JsonResponse(['status' => 'no-login', 'message'=>'请登录']))->send());
        }
        return;
    }
}
