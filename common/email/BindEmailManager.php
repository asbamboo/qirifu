<?php
namespace asbamboo\qirifu\common\email;

/**
 * 绑定email邮件管理器
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class BindEmailManager
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
        $message->setSubject(\Parameter::instance()->get('SYSTEM_NAME').'绑定email验证码[请确认]');
        $message->setFrom(\Parameter::instance()->get('MAILER_USER'), \Parameter::instance()->get('SYSTEM_NAME'));
        $message->setTo($to);
        $message->setBody("
            <!DOCTYPE html>
            <html lang='zh-cn'>
                <head>
                    <title>" . \Parameter::instance()->get('SYSTEM_NAME') . "绑定email验证码[请确认]</title>
                </head>
                <body>
                    <h4>您好:</h4>
                    <div>欢迎您使用" . \Parameter::instance()->get('SYSTEM_NAME'). ", 您的绑定email验证码为:{$captcha}。</div>
                    <p><b>注意：</b>在10分钟之后，本邮件失效。</p>
                </body>
            </html>
        ");

        $SwiftMailer->send($message);
    }
}
