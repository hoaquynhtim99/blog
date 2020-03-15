<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$submenu['blog-list'] = $nv_Lang->getModule('blogList');
$submenu['blog-content'] = $nv_Lang->getModule('blogAdd');
$submenu['categories'] = $nv_Lang->getModule('categoriesManager');
$submenu['tags'] = $nv_Lang->getModule('tagsMg');
$submenu['newsletter-manager'] = $nv_Lang->getModule('nltList');
$submenu['config-master'] = $nv_Lang->getModule('cfgMaster');

$allow_func = [
    'main',
    'categories',
    'newsletter-manager',
    'config-master',
    'tags',
    'blog-list',
    'blog-content',
    'config-block-tags',
    'config-sys',
    'config-structured-data',
    'config-instant-articles',
    'config-comment'
];
