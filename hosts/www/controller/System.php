<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;

class System extends ControllerAbstract
{
    public function info()
    {
        return $this->successJson('success', [
            'name'              => \Parameter::instance()->get('SYSTEM_NAME'),
            'admin_base_url'    => \Parameter::instance()->get('SYSTEM_ADMIN_BASE_URL'),
        ]);
    }
}