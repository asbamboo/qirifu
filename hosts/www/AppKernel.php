<?php
namespace asbamboo\qirifu\hosts\www;

use asbamboo\qirifu\common\AppKernel AS CommonAppKernel;
use asbamboo\framework\kernel\KernelAbstract;

/**
 * 前台Kernel
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class AppKernel extends CommonAppKernel
{
    public function __construct($is_debug)
    {
        parent::__construct($is_debug);
        date_default_timezone_set('Asia/Shanghai');
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\qirifu\common\AppKernel::getConfigPath()
     */
    public function getConfigPath() : string
    {
        return __DIR__ . '/config/config.php' ;
    }
}
