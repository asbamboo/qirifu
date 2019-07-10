<?php
return [
    ['id' => 'register_send_captcha', 'path' => '/register/send-captcha' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Register:sendCaptcha'],
    ['id' => 'register_action', 'path' => '/register/action' , 'callback' => 'asbamboo\\qirifu\\hosts\\www\\controller\\Register:action'],
];