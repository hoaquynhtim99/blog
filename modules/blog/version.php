<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'NukeViet Blog',
    'modfuncs' => 'main, tags, viewcat, search, detail, newsletters',
    'submenu' => 'main, tags, viewcat, search, detail, newsletters',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.3.05',
    'date' => 'Wednesday, March 20, 2019 9:35:53 PM GMT+07:00',
    'author' => 'PHAN TAN DUNG <phantandung92@gmail.com>',
    'note' => 'Module Blog For Personal Blog Website',
    'uploads_dir' => [
        $module_name,
        $module_name . '/images',
        $module_name . '/thumb'
    ]
];
