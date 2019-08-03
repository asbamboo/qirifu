<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\security\user\login\LoginInterface;
use asbamboo\security\user\login\LogoutInterface;
use asbamboo\security\exception\UserNotExistsException;
use asbamboo\security\exception\NotEqualPasswordException;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\model\message\Repository AS MessageRepository;
use asbamboo\qirifu\common\model\merchant\Repository AS MerchantRepository;

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
         * @var MessageRepository $MessageRepository
         * @var MerchantRepository $MerchantRepository
         */
        $UserToken          = $this->Container->get(UserTokenInterface::class);
        $MessageRepository  = $this->Container->get(MessageRepository::class);
        $MerchantRepository = $this->Container->get(MerchantRepository::class);
        $User               = $UserToken->getUser();
        $merchant_names     = $MerchantRepository->findNamesByUserIds([$User->getUserId()]);
        $is_new             = empty( $merchant_names ) ? true : false;
        $merchant_name      = empty( $merchant_names ) ? '未填写资料' : $merchant_names[$User->getUserId()];


        return $this->successJson('用户信息', [
            'roles'                 => $User->getRoles(),
            'name'                  => $merchant_name,
            'is_new'                => $is_new,
            'unread_message_cnt'    => $MessageRepository->getUnreadCountByToUserId($User->getUserId()),
            'avatar'                => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
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