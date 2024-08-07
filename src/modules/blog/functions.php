<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

use NukeViet\Module\blog\BlogInit;

define('NV_IS_MOD_BLOG', true);

// Class cua module
$BL = new BlogInit();

// Các danh mục
$global_array_cat = $BL->listCat();

// Xac dinh RSS co ban cua module
if ($module_info['rss']) {
    $rss[] = [
        'title' => $module_info['custom_title'],
        'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rss'
    ];
}

// Cac bien he thong
$catid = 0;
$nv_vertical_menu = [];
$page = 1;
$blog_op = $op;
$blog_data = [];

// Xac dinh $catid
if ($op == 'main') {
    if (isset($array_op[0])) {
        // Trang chủ phân trang
        if (preg_match('/^page\-([0-9]+)$/i', $array_op[0], $m)) {
            $page = intval($m[1]);

            if ($page <= 1) {
                nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            }
        } else {
            $defis = 0;
            foreach ($global_array_cat as $_cat) {
                // Xem theo danh mục, ưu tiên danh mục con càng nhỏ càng tốt
                if ($_cat['alias'] == $array_op[0] and $_cat['cat_level'] > $defis) {
                    $defis = $_cat['cat_level'];
                    $catid = $_cat['id'];
                    $op = $blog_op = 'viewcat';
                }
            }

            // Xem danh muc
            if ($blog_op == 'viewcat') {
                // Phan trang tai danh muc
                if (isset($array_op[1])) {
                    if (preg_match('/^page\-([0-9]+)$/i', $array_op[1], $m)) {
                        $page = intval($m[1]);
                    } else {
                        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $array_op[0]);
                    }
                }
            } else {
                // Xem bai viet
                // Khong cho array_op nao lon hon
                if (sizeof($array_op) > 1) {
                    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $array_op[0]);
                }

                $sql = "SELECT a.*, b.username postname, b.first_name, b.last_name
                FROM " . $BL->table_prefix . "_rows a LEFT JOIN " . NV_USERS_GLOBALTABLE . " b ON a.postid=b.userid
                WHERE a.status=1 AND a.alias=" . $db->quote($array_op[0]);
                $result = $db->query($sql);

                if ($result->rowCount()) {
                    $blog_data = $result->fetch();
                    $op = $blog_op = 'detail';

                    // Chinh mot so thong tin
                    $blog_data['catid'] = 0;
                    $blog_data['catids'] = $BL->string2array($blog_data['catids']);
                    $blog_data['tagids'] = $BL->string2array($blog_data['tagids']);

                    if (empty($blog_data['sitetitle'])) {
                        $blog_data['sitetitle'] = $blog_data['title'];
                    }

                    $blog_data['bodyhtml'] = $blog_data['bodytext'];
                    $blog_data['postName'] = nv_show_name_user($blog_data['first_name'], $blog_data['last_name'], $blog_data['postname']);

                    // Xac dinh media
                    if ($blog_data['mediatype'] == 0) {
                        $blog_data['mediavalue'] = $blog_data['images'];
                    }

                    if (!empty($blog_data['mediavalue'])) {
                        if (is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . $blog_data['mediavalue'])) {
                            $blog_data['mediavalue'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . $blog_data['mediavalue'];
                        } elseif (!nv_is_url($blog_data['mediavalue'])) {
                            $blog_data['mediavalue'] = '';
                        }
                    }

                    // Xac dinh images
                    if (!empty($blog_data['images'])) {
                        if (is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . $blog_data['images'])) {
                            $blog_data['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . $blog_data['images'];
                        } elseif (!nv_is_url($blog_data['images'])) {
                            $blog_data['images'] = '';
                        }
                    }

                    // Xac dinh ID danh muc
                    foreach ($global_array_cat as $_cat) {
                        if (in_array($_cat['id'], $blog_data['catids']) and $_cat['cat_level'] > $defis) {
                            $defis = $_cat['cat_level'];
                            $blog_data['catid'] = $catid = $_cat['id'];
                        }
                    }

                    // Thêm chính tên bài viết vào breadcrumbs
                    if (nv_apply_hook($module_name, 'append_detail_breadcrumb', [$blog_data], true)) {
                        $array_mod_title[] = [
                            'catid' => $blog_data['id'],
                            'title' => $blog_data['title'],
                            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $blog_data['alias'] . $global_config['rewrite_exturl'],
                        ];
                    }
                } else {
                    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                }
            }
        }
    }
}

/**
 * Xác định RSS các danh mục và menu
 * @deprecated $nv_vertical_menu bỏ cái này
 */
foreach ($global_array_cat as $_cat) {
    if ($module_info['rss']) {
        $rss[] = [
            'title' => $_cat['title'] . NV_TITLEBAR_DEFIS . $module_info['custom_title'],
            'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rss/' . $_cat['alias']
        ];
    }
}

// Xuat breadcrumbs cua danh muc
if (!empty($catid)) {
    $parentid = $catid;
    while ($parentid > 0) {
        $array_mod_title[] = [
            'catid' => $parentid,
            'title' => $global_array_cat[$parentid]['title'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$parentid]['alias'],
        ];
        $parentid = $global_array_cat[$parentid]['parentid'];
    }
    krsort($array_mod_title, SORT_NUMERIC);
}

// Loai bo cac bien tam
unset($_cat, $sub_menu, $act, $catid_i, $subcats, $defis, $parentid, $result, $sql);

// Chỉ có phần xem chi tiết bài viết mới có thể .html
if ($op != 'detail' and preg_match('/' . preg_quote($global_config['rewrite_exturl']) . '$/', $_SERVER['REQUEST_URI'])) {
    $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
    nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content') . $redirect);
}
