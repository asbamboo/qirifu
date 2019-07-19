<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;

class Home extends ControllerAbstract
{
    public function index()
    {
        return $this->view();
    }
}