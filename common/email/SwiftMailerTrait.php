<?php
namespace asbamboo\qirifu\common\email;

/**
 * trait 用来处理 SwiftMailer 实例
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
trait SwiftMailerTrait
{
    /**
     * 创建新的实例
     * @return \Swift_SmtpTransport
     */
    public function createSwiftMailer()
    {
        $Swift_SmtpTransport    = new \Swift_SmtpTransport(\CommonConstant::MAILER_HOST);
        $Swift_SmtpTransport->setUsername(\CommonConstant::MAILER_USER);
        $Swift_SmtpTransport->setPassword(\CommonConstant::MAILER_PASSWORD);
        $Swift_SmtpTransport->setPort(\CommonConstant::MAILER_PORT);
        $Swift_SmtpTransport->setEncryption(\CommonConstant::MAILER_ENCRYPTION);
        return new \Swift_Mailer($Swift_SmtpTransport);
    }
}
