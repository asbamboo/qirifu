<?php
return [
    'connection'    => [
        'driver'    => Parameter::DB_DRIVER,
        'host'      => Parameter::DB_HOST,
        'dbname'    => Parameter::DB_NAME,
        'user'      => Parameter::DB_USER,
        'password'  => Parameter::DB_PASSWORD,
        'charset'   => Parameter::DB_CHARSET,
    ],'metadata'    => [
        'path'      => [__DIR__ . '/database'],
        'type'      => 'yaml',
    ],
    'is_dev'      => true,
];