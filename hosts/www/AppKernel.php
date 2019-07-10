<?php
namespace asbamboo\qirifu\hosts\www;

use asbamboo\qirifu\common\AppKernel AS CommonAppKernel;

/**
 * 前台Kernel
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年11月28日
 */
class AppKernel extends CommonAppKernel
{
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
