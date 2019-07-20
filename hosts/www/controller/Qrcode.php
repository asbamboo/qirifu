<?php
namespace asbamboo\qirifu\hosts\www\controller;

use asbamboo\framework\controller\ControllerAbstract;
use asbamboo\router\RouterInterface;
use asbamboo\security\user\token\UserTokenInterface;

class Qrcode extends ControllerAbstract
{
    public function getData()
    {
        /**
         *
         * @var RouterInterface $Router
         * @var UserTokenInterface $UserToken
         */
        $Router         = $this->Container->get(RouterInterface::class);
        $UserToken      = $this->Container->get(UserTokenInterface::class);
        $User           = $UserToken->getUser();

        return $this->successJson('success', ['qrcode' => $Router->generateAbsoluteUrl('home') . "#/trade/{$User->getUserId()}/order"]);
    }
}