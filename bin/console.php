<?php
namespace asbamboo\qirifu\bin;
use asbamboo\framework\kernel\KernelInterface;
use asbamboo\framework\kernel\Console;
use asbamboo\event\EventScheduler;
use asbamboo\framework\Event;
use asbamboo\console\ProcessorInterface;
use asbamboo\qirifu\common\AppKernel;
use asbamboo\http\ServerRequest;
use asbamboo\security\user\token\UserTokenInterface;
use asbamboo\security\user\BaseUser;
use asbamboo\security\user\token\UserToken;
use asbamboo\qirifu\common\Constant;
use asbamboo\security\user\UserInterface;
use asbamboo\session\SessionInterface;

$autoload   = require_once dirname(__DIR__) . '/vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

/**
 * 命令行程序入口
 */

EventScheduler::instance()->bind(Event::KERNEL_CONSOLE_PRE_EXEC, function(KernelInterface $Kernel){
    $CommandCollection  = $Kernel->getContainer()->get(ProcessorInterface::class)->commandCollection();
    $command_path		= $Kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'command';
    if(!is_dir($command_path)){
        return;
    }
    $files  = scandir($command_path);
    foreach($files AS $file){
        $test_class_name    = str_replace('.php', '', $file);
        $command_name       = strtolower(str_replace('Command', '', $test_class_name));
        $test_namespace     = substr(__NAMESPACE__, 0, -3/*bin*/) . 'command\\';
        $test_class_name    = $test_namespace . $test_class_name;
        if(class_exists($test_class_name)){
            $Command        = $Kernel->getContainer()->get($test_class_name);
            if(method_exists($Command, 'getName')){
                $command_name = $Command->getName();
            }
            if(method_exists($Command, 'setContainer')){
                $Command->setContainer($Kernel->getContainer());
            }
            $CommandCollection->add($command_name, $Command);
        }
    }
});


$AppKernel  = new AppKernel($debug = true);
$AppKernel->getContainer()->set(\asbamboo\http\ServerRequestInterface::class, new class extends ServerRequest
{
    /**
     *
     * {@inheritDoc}
     * @see \Psr\Http\Message\ServerRequestInterface::getServerParams()
     */
    public function getServerParams() : array
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        return $_SERVER;
    }
});
$AppKernel->getContainer()->set(UserTokenInterface::class, new class($AppKernel->getContainer()->get(SessionInterface::class))extends UserToken{
    public function getUser() : UserInterface
    {
        return new class extends BaseUser{
            public function getUserId(){
                return Constant::SYSTEM_CONSOLE_USER;
            }
        };
    }
});
(new Console())->run($AppKernel);

