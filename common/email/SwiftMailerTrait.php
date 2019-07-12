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
        $Swift_SmtpTransport    = new \Swift_SmtpTransport(\Parameter::instance()->get('MAILER_HOST'));
        $Swift_SmtpTransport->setUsername(\Parameter::instance()->get('MAILER_USER'));
        $Swift_SmtpTransport->setPassword(\Parameter::instance()->get('MAILER_PASSWORD'));
        $Swift_SmtpTransport->setPort(\Parameter::instance()->get('MAILER_PORT'));
        $Swift_SmtpTransport->setEncryption(\Parameter::instance()->get('MAILER_ENCRYPTION'));
        return new \Swift_Mailer($Swift_SmtpTransport);
    }
}
