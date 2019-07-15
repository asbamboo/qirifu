<?php
namespace asbamboo\qirifu\hosts\admin\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\http\ServerRequestInterface;
use asbamboo\qirifu\common\exception\MessageException;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\http\Constant AS HttpConstant;

class System extends ControllerAbstract
{
    public function setting($type = 'system-info', ServerRequestInterface $Request)
    {
        try
        {
            switch($type){
                case 'system-info':
                    return $this->systemInfo($Request);
                case 'database-info':
                    return $this->databaseInfo($Request);
                case 'email-info':
                    return $this->emailInfo($Request);
                case 'asbamboo-info':
                    return $this->asbambooInfo($Request);
            }
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function systemInfo(ServerRequestInterface $Request)
    {
        try
        {
            $system_info    = [
                'name'      => \Parameter::instance()->get('SYSTEM_NAME'),
                'user'      => \Parameter::instance()->get('SYSTEM_ADMIN'),
                'password'  => \Parameter::instance()->get('SYSTEM_PASSWORD'),
            ];

            if($Request->getMethod() == HttpConstant::METHOD_POST){
                $system_name        = trim($Request->getPostParam('name'));
                $system_admin       = trim($Request->getPostParam('user'));
                $system_password    = $Request->getPostParam('password');
                if(empty($system_name)){
                    throw new MessageException('请输入系统名称');
                }
                if(empty($system_admin)){
                    throw new MessageException('请输入系统管理员账号');
                }
                if(empty($system_password)){
                    throw new MessageException('请输入系统管理员密码');
                }
                \Parameter::instance()->set('SYSTEM_NAME', $system_name);
                \Parameter::instance()->set('SYSTEM_ADMIN', $system_admin);
                \Parameter::instance()->set('SYSTEM_PASSWORD', $system_password);
            }

            return $this->successJson('处理成功', $system_info);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function databaseInfo(ServerRequestInterface $Request)
    {
        try
        {
            $database_info      = [
                'host'          => \Parameter::instance()->get('DB_HOST'),
                'port'          => \Parameter::instance()->get('DB_PORT'),
                'database'      => \Parameter::instance()->get('DB_NAME'),
                'username'      => \Parameter::instance()->get('DB_USER'),
                'password'      => \Parameter::instance()->get('DB_PASSWORD'),
                'charset'       => \Parameter::instance()->get('DB_CHARSET'),
            ];

            if($Request->getMethod() == HttpConstant::METHOD_POST){
                $db_host        = trim($Request->getPostParam('host'));
                $db_port        = trim($Request->getPostParam('port'));
                $db_name        = $Request->getPostParam('database');
                $db_user        = $Request->getPostParam('username');
                $db_password    = $Request->getPostParam('password');
                $db_charset     = $Request->getPostParam('charset');
                if(empty($db_host)){
                    throw new MessageException('请输入数据库主机');
                }
                if(empty($db_port)){
                    throw new MessageException('请输入数据库主机端口号');
                }
                if(empty($db_name)){
                    throw new MessageException('请输入数据库名称');
                }
                if(empty($db_user)){
                    throw new MessageException('请输入数据库链接账号');
                }
                if(empty($db_password)){
                    throw new MessageException('请输入数据库链接密码');
                }
                if(empty($db_charset)){
                    throw new MessageException('请输入数据库字符集');
                }
                \Parameter::instance()->set('DB_HOST', $db_host);
                \Parameter::instance()->set('DB_PORT', $db_port);
                \Parameter::instance()->set('DB_NAME', $db_name);
                \Parameter::instance()->set('DB_USER', $db_user);
                \Parameter::instance()->set('DB_PASSWORD', $db_password);
                \Parameter::instance()->set('DB_CHARSET', $db_charset);
            }

            return $this->successJson('处理成功', $database_info);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function asbambooInfo(ServerRequestInterface $Request)
    {
        try
        {
            $asbamboo_info      = [
                'app_key'       => \Parameter::instance()->get('ASBAMBOO_APPKEY'),
                'secret'        => \Parameter::instance()->get('ASBAMBOO_APPSERECT')
            ];

            if($Request->getMethod() == HttpConstant::METHOD_POST){
                $asbamboo_appkey    = trim($Request->getPostParam('app_key'));
                $asbamboo_appserect = trim($Request->getPostParam('secret'));
                if(empty($asbamboo_appkey)){
                    throw new MessageException('请输入app key');
                }
                if(empty($asbamboo_appserect)){
                    throw new MessageException('请输入app secret');
                }
                \Parameter::instance()->set('ASBAMBOO_APPKEY', $asbamboo_appkey);
                \Parameter::instance()->set('ASBAMBOO_APPSERECT', $asbamboo_appserect);
            }

            return $this->successJson('处理成功', $asbamboo_info);
        }catch(MessageException $e){
            return $this->failedJson($e->getMessage());
        }catch(\Exception $e){
            if($this->Container->get(KernelInterface::class)->getIsDebug()){
                return $this->failedJson((string) $e);
            }
            return $this->failedJson();
        }
    }

    public function emailInfo(ServerRequestInterface $Request)
    {
        try
        {
            $email_info         = [
                "host"          => \Parameter::instance()->get('MAILER_HOST'),
                "port"          => \Parameter::instance()->get('MAILER_PORT'),
                "encryption"    => \Parameter::instance()->get('MAILER_ENCRYPTION'),
                "user"          => \Parameter::instance()->get('MAILER_USER'),
                "password"      => \Parameter::instance()->get('MAILER_PASSWORD'),
            ];

            if($Request->getMethod() == HttpConstant::METHOD_POST){
                $mailer_host        = trim($Request->getPostParam('host'));
                $mailer_port        = trim($Request->getPostParam('port'));
                $mailer_encryption  = trim($Request->getPostParam('encryption'));
                $mailer_user        = trim($Request->getPostParam('user'));
                $mailer_password    = trim($Request->getPostParam('password'));
                if(empty($mailer_host)){
                    throw new MessageException('请输入email host');
                }
                if(empty($mailer_port)){
                    throw new MessageException('请输入email port');
                }
                if(empty($mailer_encryption)){
                    throw new MessageException('请输入email encryption');
                }
                if(empty($mailer_user)){
                    throw new MessageException('请输入email账号');
                }
                if(empty($mailer_password)){
                    throw new MessageException('请输入email密码');
                }
                \Parameter::instance()->set('MAILER_HOST', $mailer_host);
                \Parameter::instance()->set('MAILER_PORT', $mailer_port);
                \Parameter::instance()->set('MAILER_ENCRYPTION', $mailer_encryption);
                \Parameter::instance()->set('MAILER_USER', $mailer_user);
                \Parameter::instance()->set('MAILER_PASSWORD', $mailer_password);
            }

            return $this->successJson('处理成功', $email_info);
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