<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'Blog',
    'modfuncs' => 'main, tags, viewcat, search, detail, newsletters',
    'submenu' => 'search',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.6.00',
    'date' => 'Tuesday, July 23, 2024 6:23:30 PM GMT+07:00',
    'author' => 'PHAN TAN DUNG <writeblabla@gmail.com>',
    'note' => 'Module Blog For Personal Blog Website',
    'uploads_dir' => [
        $module_name,
        $module_name . '/images',
        $module_name . '/thumb'
    ]
];
