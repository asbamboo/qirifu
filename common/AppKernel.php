<?php
namespace asbamboo\qirifu\common;

use asbamboo\framework\kernel\KernelAbstract;

/**
 * 前台Kernel
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月26日
 */
class AppKernel extends KernelAbstract
{
    /**
     *
     * {@inheritDoc}
     * @see KernelAbstract::getProjectDir()
     */
    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    /**
     *
     * {@inheritDoc}
     * @see KernelAbstract::getConfigPath()
     */
    public function getConfigPath() : string
    {
        return __DIR__ . '/config/config.php' ;
    }
}
