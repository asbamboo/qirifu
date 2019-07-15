<?php
return [
    'connection'    => [
        'driver'    => Parameter::instance()->get('DB_DRIVER'),
        'host'      => Parameter::instance()->get('DB_HOST'),
        'port'      => Parameter::instance()->get('DB_PORT'),
        'dbname'    => Parameter::instance()->get('DB_NAME'),
        'user'      => Parameter::instance()->get('DB_USER'),
        'password'  => Parameter::instance()->get('DB_PASSWORD'),
        'charset'   => Parameter::instance()->get('DB_CHARSET'),
    ],'metadata'    => [
        'path'      => [__DIR__ . '/database'],
        'type'      => 'yaml',
    ],
    'is_dev'      => true,
];