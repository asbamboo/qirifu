<?php
namespace asbamboo\qirifu\common\email;

use asbamboo\qirifu\common\exception\MessageException;

/**
 * 重置密码邮件管理器
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
class ResetPasswordEmailManager
{
    use SwiftMailerTrait;

    /**
     * 发送邮件
     * @param string $to
     */
    public function sendTo(string $to, array $data) : void
    {
        $q              = urlencode(base64_encode(http_build_query($data)));
        $t              = base_convert(time(), 10, 36);
        $c              = $this->makeCheckValue($q, $t);
        $url            = \CommonConstant::RESET_PASSWORD_EMAIL_CHECK_URL . '?q=' . $q . '&c=' . $c . '&t=' . $t;
        $SwiftMailer    = $this->createSwiftMailer();
        $message        = new \Swift_Message();
        $message->setContentType('text/html');
        $message->setCharset('utf-8');
        $message->setSubject(\CommonConstant::SYSTEM_NAME . '密码重置邮件[请确认]');
        $message->setFrom(\CommonConstant::MAILER_USER, \CommonConstant::SYSTEM_NAME);
        $message->setTo($to);
        $message->setBody("
            <!DOCTYPE html>
            <html lang='zh-cn'>
                <head>
                    <title>" . \CommonConstant::SYSTEM_NAME . "密码重置邮件[请确认]</title>
                </head>
                <body>
                    <h4>您好:</h4>
                    <div>欢迎您使用" . \CommonConstant::SYSTEM_NAME . "。收到本邮件请在10分钟内，请在浏览器打开此URL:<a href='{$url}'>{$url}</a>, 重置账号的密码。</div>
                    <p><b>注意：</b>不要将这个URL告诉任何人。</p>
                    <p><b>注意：</b>在10分钟之后，本邮件失效。</p>
                </body>
            </html>
        ");

        $SwiftMailer->send($message);
    }

    /**
     * 验证邮件传过来的参数是否真实有效
     *
     * @param (string) $q
     * @param (string) $c
     */
    public function checkEmail($q, $c, $t)
    {
        $time  = base_convert($t, 36, 10);
        if(time() > $time + 600){ // 10分钟内有效
            throw new MessageException('该重置密码邮件已经失效。');
        }
        return $this->makeCheckValue($q, $t) == $c;
    }

    /**
     * 生成一个校验值
     *  - 用来验证用户访问url时是否时一个真实由本系统发出的url
     *
     * @param (string) $q
     * @return string
     */
    public function makeCheckValue($q, $t)
    {
        if(substr($q, -1) == '='){
            $q  = urlencode($q);
        }
        return md5(sha1($q) . md5($q) . $t);
    }
}
