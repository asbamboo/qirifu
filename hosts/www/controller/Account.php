<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\qirifu\common\model\account\Repository AS AccountRepository;
use asbamboo\qirifu\common\model\account\Code AS AccountCode;
use asbamboo\qirifu\common\model\account\Manager AS AccountManager;
use asbamboo\qirifu\common\model\user\Manager AS UserManager;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;
use asbamboo\qirifu\common\model\captcha\Repository AS CaptchaRepository;
use asbamboo\qirifu\common\model\captcha\Manager AS CaptchaManager;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\http\ServerRequestInterface;
use asbamboo\http\ServerRequest;
use asbamboo\qirifu\common\email\BindEmailManager;

/**
 * 注册
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月26日
 */
class Account extends ControllerAbstract
{
    public function info()
    {
        try{
            $info   = [
                'account'   => '',
                'email'     => '',
                'phone'     => '',
            ];

            /**
             *
             * @var \asbamboo\qirifu\common\login\User $User
             * @var UserTokenInterface $UserToken
             * @var AccountRepository $AccountRepository
             * @var \asbamboo\qirifu\common\model\account\Entity $AccountEntity
             */
            $UserToken          = $this->Container->get(UserTokenInterface::class);
            $User               = $UserToken->getUser();
            $user_id            = $User->getUserId();
            $AccountRepository  = $this->Container->get(AccountRepository::class);
            $AccountEntitys     = $AccountRepository->findAllByUserId($user_id);
            foreach($AccountEntitys AS $AccountEntity){
                if($AccountEntity->getType() == AccountCode::TYPE_ACCOUNT){
                    $info['account']    = $AccountEntity->getValue();
                }elseif($AccountEntity->getType() == AccountCode::TYPE_EMAIL){
                    $info['email']    = $AccountEntity->getValue();
                }elseif($AccountEntity->getType() == AccountCode::TYPE_PHONE){
                    $info['phone']    = $AccountEntity->getValue();
                }
            }

            return $this->successJson('处理成功', $info);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function settingAccount(ServerRequestInterface $Request)
    {
        try{
            $account_value      = $Request->getPostParam('account');
            $account_type       = AccountCode::TYPE_ACCOUNT;
            $account_is_enable  = true;

            /**
             *
             * @var \asbamboo\qirifu\common\login\User $User
             * @var UserTokenInterface $UserToken
             * @var AccountRepository $AccountRepository
             * @var \asbamboo\qirifu\common\model\account\Entity $AccountEntity
             * @var AccountManager $AccountManager
             * @var DbFactoryInterface $Db
             */
            $UserToken          = $this->Container->get(UserTokenInterface::class);
            $User               = $UserToken->getUser();
            $UserEntity         = $User->getUserEntity();
            $AccountManager     = $this->Container->get(AccountManager::class);
            $AccountRepository  = $this->Container->get(AccountRepository::class);
            $Db                 = $this->Container->get(DbFactoryInterface::class);

            $Db->getManager()->transactional(function()use(
                $Db, $AccountManager, $AccountRepository, $UserEntity, $account_type, $account_value, $account_is_enable
            ){
                $AccountManager->load();
                $AccountManager->create($UserEntity, $account_type, $account_value, $account_is_enable);
                $Db->getManager()->flush();
                $AccountEntity  = $AccountRepository->findAllByUserIdAndType($UserEntity->getUserId(), $account_type);
                if(count($AccountEntity) != 1){
                    throw new MessageException('账号绑定失败，请重试。');
                }
            });

            return $this->successJson('设置成功');
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function settingEmail(ServerRequestInterface $Request)
    {
        try{
            $captcha            = $Request->getPostParam('captcha');
            $email              = $Request->getPostParam('email');
            $account_type       = AccountCode::TYPE_EMAIL;
            $account_is_enable  = true;

            if(strpos($email, '@')==false){
                throw new MessageException('请输入有效email。');
            }
            if($captcha === ''){
                throw new MessageException('请输入验证码。');
            }
            $CaptchaRepository  = $this->Container->get(CaptchaRepository::class);
            $CaptchaEntity = $CaptchaRepository->findOneByTarget($email);
            if(empty($CaptchaEntity) || $CaptchaEntity->getValue() != strtoupper($captcha)){
                throw new MessageException('验证码错误。');
            }


            /**
             *
             * @var \asbamboo\qirifu\common\login\User $User
             * @var UserTokenInterface $UserToken
             * @var AccountRepository $AccountRepository
             * @var \asbamboo\qirifu\common\model\account\Entity $AccountEntity
             * @var AccountManager $AccountManager
             * @var DbFactoryInterface $Db
             */
            $UserToken          = $this->Container->get(UserTokenInterface::class);
            $User               = $UserToken->getUser();
            $UserEntity         = $User->getUserEntity();
            $AccountManager     = $this->Container->get(AccountManager::class);
            $AccountRepository  = $this->Container->get(AccountRepository::class);
            $Db                 = $this->Container->get(DbFactoryInterface::class);

            $Db->getManager()->transactional(function()use(
                $Db, $AccountManager, $AccountRepository, $UserEntity, $account_type, $email, $account_is_enable
                ){
                    $AccountManager->load();
                    $AccountManager->create($UserEntity, $account_type, $email, $account_is_enable);
                    $Db->getManager()->flush();
                    $AccountEntity  = $AccountRepository->findAllByUserIdAndType($UserEntity->getUserId(), $account_type);
                    if(count($AccountEntity) != 1){
                        throw new MessageException('email绑定失败，请重试。');
                    }
            });

            return $this->successJson('设置成功');
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function resetPassword(ServerRequestInterface $Request)
    {
        try{
            $org_password       = $Request->getPostParam('org_password', '');
            $new_password       = $Request->getPostParam('new_password', '');
            $confirm_password   = $Request->getPostParam('confirm_password', '');

            if($org_password === ''){
                throw new MessageException('请输入原密码');
            }

            if($new_password === ''){
                throw new MessageException('请输入新密码');
            }

            if($confirm_password === ''){
                throw new MessageException('请q确认新密码');
            }

            if($confirm_password != $new_password){
                throw new MessageException('请新密码输入不一致');
            }

            /**
             *
             * @var \asbamboo\qirifu\common\login\User $User
             * @var UserTokenInterface $UserToken
             * @var UserManager $UserManager
             * @var DbFactoryInterface $Db
             */
            $UserToken          = $this->Container->get(UserTokenInterface::class);
            $User               = $UserToken->getUser();

            $UserManager    = $this->Container->get(UserManager::class);
            $UserManager->load($User->getUserId());
            if(!$UserManager->isEqualPassword($org_password)){
                throw new MessageException('原密码错误.');
            }
            $UserManager->updatePassword($confirm_password);
            $Db = $this->Container->get(DbFactoryInterface::class);
            $Db->getManager()->flush();

            return $this->successJson('设置成功。');
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    /**
     * 发送email注册确认的邮件
     *
     * @return \asbamboo\http\ResponseInterface
     */
    public function sendCaptcha(ServerRequest $Request)
    {
        try{
            /**
             * request params
             * 用户的密码会在最后邮件验证通过时写入数据
             */
            $email              = trim($Request->getPostParam('email', ''));
            $phone              = trim($Request->getPostParam('phone', ''));
            $email_or_phone     = $email ?? $phone;

            if(!empty($phone)){
                throw new MessageException('手机号码绑定功能暂未开放。');
            }

            if(empty($email_or_phone)){
                throw new MessageException('请输入email或者手机号.');
            }
            /**
             * make captcha
             *@var CaptchaRepository $CaptchaRepository
             */
            $captcha            = strtoupper(base_convert(mt_rand(1679616, 60466175), 10, 36));    /* 10000 - ZZZZZ */
            $CaptchaRepository  = $this->Container->get(CaptchaRepository::class);
            $CaptchaManager     = $this->Container->get(CaptchaManager::class);
            $CaptchaEntity      = $CaptchaRepository->findOneByTarget($email_or_phone);
            $CaptchaManager->load($CaptchaEntity);
            $CaptchaManager->createOrUpdate($email_or_phone, $captcha);

            /**
             * 发送邮件验证码
             */
            if(!empty($email)){
                $BindEmailManager   = $this->Container->get(BindEmailManager::class);
                $BindEmailManager->sendTo($email, $captcha);
            }

            $Db = $this->Container->get(DbFactoryInterface::class);
            $Db->getManager()->flush();

            /**
             * result
             */
            return $this->successJson('验证码已经发送到你的email，请注意查收。');
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