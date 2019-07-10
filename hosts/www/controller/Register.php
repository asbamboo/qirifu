<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\http\ServerRequest;
use asbamboo\qirifu\common\model\account\Code AS AccountCode;
use asbamboo\qirifu\common\model\account\Validator AS AccountValidator;
use asbamboo\qirifu\common\model\account\Repository AS AccountRepository;
use asbamboo\qirifu\common\email\RegisterEmailManager;
use asbamboo\qirifu\common\exception\MessageException;


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
    public function sendCaptcha(ServerRequest $Request, AccountRepository $AccountRepository, RegisterEmailManager $RegisterEmailManager)
    {
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
             * 发送注册邮件
             */
            $captcha = strtoupper(base_convert(mt_rand(1679616, 60466175), 10, 36));    /* 10000 - ZZZZZ */
            $RegisterEmailManager->sendTo($account_value_email, $captcha);

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