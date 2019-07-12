<?php
namespace asbamboo\qirifu\common\email;

/**
 * trait 用于创建Swift_SmtpTransport实例
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月29日
 */
trait SwiftSmtpTransportTrait
{
    /**
     * 创建新的实例
     * @return \Swift_SmtpTransport
     */
    public function create()
    {
        $Swift_SmtpTransport    = new \Swift_SmtpTransport(\Parameter::instance()->get('MAILER_HOST'));
        $Swift_SmtpTransport->setUsername(\Parameter::instance()->get('MAILER_USER'));
        $Swift_SmtpTransport->setPassword(\Parameter::instance()->get('MAILER_PASSWORD'));
        return $Swift_SmtpTransport;
    }
}
