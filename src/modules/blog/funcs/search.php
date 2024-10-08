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

$page_title = $mod_title = $nv_Lang->getModule('search');

// Breadcrumbs
$array_mod_title[] = [
    'catid' => 0,
    'title' => $nv_Lang->getModule('search'),
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op,
];

$array = [
    'q' => nv_substr($nv_Request->get_title('q', 'get', '', NV_MIN_SEARCH_LENGTH), 0, NV_MAX_SEARCH_LENGTH),
    'catid' => $nv_Request->get_int('catid', 'get', 0),
    'contents' => [],
];
if (empty($array['q'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Phân trang
$page = $nv_Request->get_int('page', 'get', 1);
$generate_page = '';
$total_pages = 0;
$all_page = 0;
$nv_BotManager->setPrivate();

// Xac dinh thong tin phan trang
$per_page = intval($BL->setting['numSearchResult']);

$array_userids = [];

// Giá trị tìm được chuẩn hóa
$sql_like = "LIKE '%" . $db->dblikeescape($array['q']) . "%'";

// SQL co ban
$sql = "FROM " . $BL->table_prefix . "_rows WHERE status=1 AND ( title " . $sql_like . " OR keywords " . $sql_like . " OR hometext " . $sql_like . " OR bodytext " . $sql_like . " )" . ($array['catid'] ? " AND ( " . $BL->build_query_search_id($array['catid'], 'catids') . " )" : "");
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;q=" . urlencode($array['q']) . "&amp;catid=" . $array['catid'];

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

$urlappend = '&amp;page=';
betweenURLs($page, ceil($all_page / $per_page), $base_url, $urlappend, $prevPage, $nextPage);

// Lay du lieu
$sql = "SELECT * " . $sql . " ORDER BY pubtime DESC LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $row['mediatype'] = intval($row['mediatype']);
    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
    $row['postName'] = '';

    $array['contents'][$row['id']] = $row;
    $array_userids[$row['postid']] = $row['postid'];
}

// Du lieu phan trang
$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
$total_pages = ceil($all_page / $per_page);

// Lay thanh vien dang bai
if (!empty($array_userids)) {
    $sql = "SELECT userid, username, first_name, last_name FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN(" . implode(",", $array_userids) . ")";
    $result = $db->query($sql);

    $array_userids = [];
    while ($row = $result->fetch()) {
        $array_userids[$row['userid']] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
    }

    foreach ($array['contents'] as $row) {
        if (isset($array_userids[$row['postid']])) {
            $array['contents'][$row['id']]['postName'] = $array_userids[$row['postid']];
        }
    }
}

$page_url = $base_url;
if ($page > 1) {
    $page_url .= '&amp;page=' . $page;
}
$canonicalUrl = getCanonicalUrl($page_url);

// Open Graph
$meta_property['og:title'] = $page_title . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'];
$meta_property['og:type'] = 'website';
$meta_property['og:url'] = $client_info['selfurl'];
$meta_property['og:description'] = $description;

if (!empty($BL->setting['sysDefaultImage'])) {
    if (preg_match("/^\//", $BL->setting['sysDefaultImage'])) {
        $meta_property['og:image'] = NV_MY_DOMAIN . $BL->setting['sysDefaultImage'];
    } else {
        $meta_property['og:image'] = $BL->setting['sysDefaultImage'];
    }
} else {
    $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo'];
}

$contents = nv_search_theme($array, $page, $total_pages, $all_page, $generate_page, $BL);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
