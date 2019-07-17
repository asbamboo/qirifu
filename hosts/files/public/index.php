<?php

use asbamboo\framework\kernel\Http;
use asbamboo\qirifu\hosts\files\AppKernel;

$autoload   = require_once dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';
(new Http())->run(new AppKernel(true));
