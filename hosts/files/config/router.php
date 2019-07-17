<?php
return [
    ['id' => 'home', 'path'=> '/' , 'callback' => 'asbamboo\\qirifu\\hosts\\files\\controller\\Home:index'],
    ['id' => 'image', 'path'=> '/image/{fileid}' , 'callback' => 'asbamboo\\qirifu\\hosts\\files\\controller\\Image:read'],
    ['id' => 'upload_image', 'path' => '/upload/image/{path}', 'callback' => 'asbamboo\\qirifu\\hosts\\files\\controller\\Upload:image'],
];