<?php
namespace asbamboo\qirifu\common\email;

/**
 * 绑定email邮件管理器
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class GeneralEmailManager
{
    use SwiftMailerTrait;

    /**
     * 发送邮件
     * @param string $to
     */
    public function sendTo(string $to, string $title, string $content) : void
    {
        $title          = '【' . \Parameter::instance()->get('SYSTEM_NAME') . '】'. $title;
        $SwiftMailer    = $this->createSwiftMailer();
        $message        = new \Swift_Message();
        $message->setContentType('text/html');
        $message->setCharset('utf-8');
        $message->setSubject($title);
        $message->setFrom(\Parameter::instance()->get('MAILER_USER'), \Parameter::instance()->get('SYSTEM_NAME'));
        $message->setTo($to);
        $message->setBody("
            <!DOCTYPE html>
            <html lang='zh-cn'>
                <head>
                    <title>{$title}</title>
                </head>
                <body>
                    <h4>亲:</h4>
                    <div>{$content}</div>
                    <p><b>注意：</b>本邮件由系统自动发出，不用回复。</p>
                </body>
            </html>
        ");

        $SwiftMailer->send($message);
    }
}
