<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\http\ServerRequest;
use asbamboo\qirifu\common\model\user\Code AS UserCode;
use asbamboo\qirifu\common\model\user\Manager AS UserManager;
use asbamboo\qirifu\common\model\account\Code AS AccountCode;
use asbamboo\qirifu\common\model\account\Validator AS AccountValidator;
use asbamboo\qirifu\common\model\account\Repository AS AccountRepository;
use asbamboo\qirifu\common\model\account\Manager AS AccountManager;
use asbamboo\qirifu\common\email\RegisterEmailManager;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\qirifu\common\model\captcha\Manager AS CaptchaManager;
use asbamboo\qirifu\common\model\captcha\Repository AS CaptchaRepository;
use asbamboo\database\FactoryInterface AS DbFactoryInterface;

/**
 * 注册
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月26日
 */
class Register extends ControllerAbstract
{
    use AccountValidator;

    /**
     * 发送email注册确认的邮件
     *
     * @return \asbamboo\http\ResponseInterface
     */
    public function sendCaptcha(
        ServerRequest $Request,
        AccountRepository $AccountRepository,
        RegisterEmailManager $RegisterEmailManager,
        CaptchaManager $CaptchaManager,
        CaptchaRepository $CaptchaRepository

    ){
        try{
            /**
             * request params
             * 用户的密码会在最后邮件验证通过时写入数据
             */
            $account_value_email    = $Request->getPostParam('email');

            $this->validateValue($account_value_email, AccountCode::TYPE_EMAIL);
            if($AccountRepository->isExistValue($account_value_email)){
                throw new MessageException('该email地址已经被注册.');
            }

            /**
             * make captcha
             */
            $captcha = strtoupper(base_convert(mt_rand(1679616, 60466175), 10, 36));    /* 10000 - ZZZZZ */
            $CaptchaEntity = $CaptchaRepository->findOneByTarget($account_value_email);
            $CaptchaManager->load($CaptchaEntity);
            $CaptchaManager->createOrUpdate($account_value_email, $captcha);

            /**
             * 发送注册邮件
             */
            $RegisterEmailManager->sendTo($account_value_email, $captcha);

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

    public function action(ServerRequest $Request, AccountRepository $AccountRepository, AccountManager $AccountManager, UserManager $UserManager, CaptchaRepository $CaptchaRepository)
    {
        try{
            /**
             * request params
             * 用户的密码会在最后邮件验证通过时写入数据
             */
            $account_value_email    = trim($Request->getPostParam('email'));
            $user_password          = $Request->getPostParam('password');
            $confirm_password       = $Request->getPostParam('confirm_password');
            $captcha                = trim($Request->getPostParam('captcha'));
            $account_is_enable      = true;
            $user_is_enable         = true;

            if($AccountRepository->isExistValue($account_value_email)){
                throw new MessageException('该email地址，已经被注册。');
            }

            $this->validateValue($account_value_email, AccountCode::TYPE_EMAIL);

            if(empty($user_password)){
                throw new MessageException('请输入密码。');
            }

            if($confirm_password != $user_password){
                throw new MessageException('两次密码输入不一致。');
            }

            if($captcha === ''){
                throw new MessageException('请输入验证码。');
            }

            $CaptchaEntity = $CaptchaRepository->findOneByTarget($account_value_email);
            if(empty($CaptchaEntity) || $CaptchaEntity->getValue() != strtoupper($captcha)){
                throw new MessageException('验证码错误。');
            }

            if($AccountRepository->isExistValue($account_value_email) == false){
                $UserEntity         = $UserManager->load();
                $AccountEntity      = $AccountManager->load();
                $UserManager->create(
                    UserCode::TYPE_USER,
                    $user_password,
                    $user_is_enable
                );

                $AccountManager->create(
                    $UserEntity,
                    AccountCode::TYPE_EMAIL,
                    $account_value_email,
                    $account_is_enable
                );

                $Db = $this->Container->get(DbFactoryInterface::class);
                $Db->getManager()->flush();
            }


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