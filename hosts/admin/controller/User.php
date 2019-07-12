<?php
namespace asbamboo\qirifu\hosts\admin\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\user\login\LoginInterface;
use asbamboo\security\user\login\LogoutInterface;
use asbamboo\security\exception\UserNotExistsException;
use asbamboo\security\exception\NotEqualPasswordException;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\security\user\token\UserTokenInterface;

/**
 * 登录
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月26日
 */
class User extends ControllerAbstract
{
    /**
     * 执行登录动作
     *
     * @return \asbamboo\http\ResponseInterface
     */
    public function login(ServerRequestInterface $Request)
    {
        try
        {
            $Login  = $this->Container->get(LoginInterface::class);
            $Login->handler($Request);
            return $this->successJson();
        }catch(UserNotExistsException $e){
            return $this->failedJson('用户名或者密码错误');
        }catch(NotEqualPasswordException $e){
            return $this->failedJson('用户名或者密码错误');
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function info()
    {
        /**
         *
         * @var UserTokenInterface $UserToken
         */
        $UserToken  = $this->Container->get(UserTokenInterface::class);
        $User       = $UserToken->getUser();

        return $this->successJson('用户信息', [
            'roles'     => $User->getRoles(),
            'name'      => '服务商',
            'avatar'    => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
        ]);
    }

    /**
     * 注销
     *
     * @return \asbamboo\http\ResponseInterface
     */
    public function logout(ServerRequestInterface $Request)
    {
        try
        {
            $Logout  = $this->Container->get(LogoutInterface::class);
            $Logout->handler($Request);
            return $this->successJson();
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }
}