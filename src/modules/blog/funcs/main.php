<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_IS_MOD_BLOG')) {
    die('Stop!!!');
}

// Chuyển hướng đến trang chủ không có tên module nếu có cấu hình
if ($BL->setting['sysRedirect2Home'] and empty($home) and $page <= 1 and $global_config['site_home_module'] == $module_name) {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA, true);
}

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

// Xac dinh thong tin phan trang
$per_page = intval($BL->setting['numPostPerPage']);

// SQL co ban
$sql = "FROM " . $BL->table_prefix . "_rows WHERE status=1 AND inhome=1";
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

// Lay du lieu
$sql = "SELECT * " . $sql . " ORDER BY pubtime DESC LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);

$array = $array_userids = $array_ids = [];

while ($row = $result->fetch()) {
    $row['mediatype'] = intval($row['mediatype']);
    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
    $row['postName'] = '';

    // Xac dinh media
    if ($row['mediatype'] == 0) {
        $row['mediavalue'] = $row['images'];
    }

    if (!empty($row['mediavalue'])) {
        if (is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . $row['mediavalue'])) {
            $row['mediavalue'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . $row['mediavalue'];
        } elseif (!nv_is_url($row['mediavalue'])) {
            $row['mediavalue'] = '';
        }
    }

    // Xac dinh images
    if (!empty($row['images'])) {
        if (is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . $row['images'])) {
            $row['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . $row['images'];
        } elseif (!nv_is_url($row['images'])) {
            $row['images'] = '';
        }
    }

    // Mặc định nội dung html trống
    $row['bodyhtml'] = '';

    $array[$row['id']] = $row;
    $array_userids[$row['postid']] = $row['postid'];
    $array_ids[] = $row['id'];
}

// Khong cho dat $page tuy y
if ($page > 1 and empty($array)) {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true);
}

// Lay thanh vien dang bai
if (!empty($array_userids)) {
    $sql = "SELECT userid, username, first_name, last_name FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN(" . implode(",", $array_userids) . ")";
    $result = $db->query($sql);

    $array_userids = [];
    while ($row = $result->fetch()) {
        $array_userids[$row['userid']] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
    }

    foreach ($array as $row) {
        if (isset($array_userids[$row['postid']])) {
            $array[$row['id']]['postName'] = $array_userids[$row['postid']];
        }
    }
}

// Lấy nội dung html nếu có
if (!empty($array_ids)) {
    $sql = "SELECT id, bodyhtml FROM " . $BL->table_prefix . "_rows_detail WHERE id IN( " . implode(",", $array_ids) . " )";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $array[$row['id']]['bodyhtml'] = $row['bodyhtml'];
    }
}

// Du lieu phan trang
$generate_page = nv_blog_alias_page($page_title, $base_url, $all_page, $per_page, $page, true, false);
$total_pages = ceil($all_page / $per_page);

// Them vao tieu de neu phan trang
if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $nv_Lang->getGlobal('page') . ' ' . $page;
    $key_words .= ', ' . $nv_Lang->getGlobal('page') . ' ' . $page;
    $description .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $nv_Lang->getGlobal('page') . ' ' . $page;
}

// Open Graph
if ($global_config['site_home_module'] == $module_name and !empty($home)) {
    $meta_property['og:title'] = $global_config['site_name'];
    $meta_property['og:type'] = 'website';
    $meta_property['og:url'] = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA, true);
    $meta_property['og:description'] = $global_config['site_description'];
} else {
    $meta_property['og:title'] = $page_title . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'];
    $meta_property['og:type'] = 'website';
    $meta_property['og:url'] = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true);
    $meta_property['og:description'] = $module_info['description'];
}

if (!empty($BL->setting['sysDefaultImage'])) {
    if (preg_match("/^\//", $BL->setting['sysDefaultImage'])) {
        $meta_property['og:image'] = NV_MY_DOMAIN . $BL->setting['sysDefaultImage'];
    } else {
        $meta_property['og:image'] = $BL->setting['sysDefaultImage'];
    }
} else {
    $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo'];
}

$contents = nv_main_theme($array, $generate_page, $BL->setting, $page, $total_pages, $BL);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
