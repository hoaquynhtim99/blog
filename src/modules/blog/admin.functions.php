<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

use NukeViet\Module\Blog\BlogInit;
use NukeViet\Module\Blog\Parsedown;

// Class cua module
$BL = new BlogInit();

define('NV_BLOG_ADMIN', true);

// Tao lien ket tinh tu dong
if ($nv_Request->isset_request("get_alias", "post")) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $BL->creatAlias(nv_substr($nv_Request->get_title('get_alias', 'post', ''), 0, 250), nv_substr($nv_Request->get_title('mode', 'post', 'cat'), 0, 250));
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xem trước nội dung soạn thảo
if ($nv_Request->get_title('markdownpreview', 'post', '') === NV_CHECK_SESSION) {
    $markdowntext = isset($_POST['markdowntext']) ? $_POST['markdowntext'] : '';
    if (empty($markdowntext)) {
        nv_htmlOutput('');
    }

    $Parsedown = new Parsedown();
    nv_htmlOutput($Parsedown->text($markdowntext));
}
