<?php
namespace asbamboo\qirifu\hosts\www\listener;

use asbamboo\router\RouterInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\Constant;
use asbamboo\http\JsonResponse;
use asbamboo\http\RedirectResponse;

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
            $this->Router->generateUrl('register_send_captcha'),
            $this->Router->generateUrl('register_action'),
            $this->Router->generateUrl('user_login'),
            $this->Router->generateUrl('trade_auth_url'),
            $this->Router->generateUrl('trade_auth_info'),
            $this->Router->generateUrl('trade_order'),
            $this->Router->generateUrl('trade_notify'),
            $this->Router->generateUrl('system_info'),
        ];
        if(!in_array($cur_url, $none_login_urls) && !in_array(Constant::USER_ROLE_LOGINED, $cur_roles)){
            return exit((new JsonResponse(['status' => 'no-login', 'message'=>'请登录']))->send());
        }
        return;
    }
}
