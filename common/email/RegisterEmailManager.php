<?php
namespace asbamboo\qirifu\common\email;

/**
 * 注册邮件管理器
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class RegisterEmailManager
{
    use SwiftMailerTrait;

    /**
     * 发送邮件
     * @param string $to
     */
    public function sendTo(string $to, string $captcha) : void
    {
        $SwiftMailer    = $this->createSwiftMailer();
        $message        = new \Swift_Message();
        $message->setContentType('text/html');
        $message->setCharset('utf-8');
        $message->setSubject(\CommonConstant::SYSTEM_NAME.'账号申请验证邮件[请确认]');
        $message->setFrom(\CommonConstant::MAILER_USER, \CommonConstant::SYSTEM_NAME);
        $message->setTo($to);
        $message->setBody("
            <!DOCTYPE html>
            <html lang='zh-cn'>
                <head>
                    <title>" . \CommonConstant::SYSTEM_NAME . "账号申请验证邮件[请确认]</title>
                </head>
                <body>
                    <h4>您好:</h4>
                    <div>欢迎您使用" . \CommonConstant::SYSTEM_NAME . ", 您的账号注册验证码为:{$captcha}。</div>
                    <p><b>注意：</b>在10分钟之后，本邮件失效。</p>
                </body>
            </html>
        ");

        $SwiftMailer->send($message);
    }
}
