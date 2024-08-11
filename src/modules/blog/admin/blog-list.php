<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_BLOG_ADMIN')) {
    die('Stop!!!');
}

// Xoa bai viet
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $list_levelid = $nv_Request->get_title('listid', 'post', '');

    if (empty($id) and empty($list_levelid)) {
        die('NO');
    }

    $listid = [];
    if ($id) {
        $listid[] = $id;
        $num = 1;
    } else {
        $list_levelid = explode(",", $list_levelid);
        $list_levelid = array_map("intval", $list_levelid);
        $list_levelid = array_filter($list_levelid);

        $listid = $list_levelid;
        $num = sizeof($list_levelid);
    }

    // Goi chuc nang xoa
    $BL->delPost($listid);

    // Ghi log
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('blogDelete'), implode(", ", $listid), $admin_info['userid']);

    // Xoa cache
    $nv_Cache->delMod($module_name);

    nv_htmlOutput('OK');
}

// Thay doi hoat dong bai viet
if ($nv_Request->isset_request('changestatus', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $controlstatus = $nv_Request->get_int('status', 'post', 0);
    $array_id = $nv_Request->get_title('listid', 'post', '');

    if ((empty($id) and empty($array_id)) or empty($controlstatus)) {
        die('NO');
    }

    $listid = [];
    if ($id) {
        $listid[] = $id;
        $num = 1;
    } else {
        $array_id = explode(",", $array_id);
        $array_id = array_map("intval", $array_id);
        $array_id = array_filter($array_id);

        $listid = $array_id;
        $num = sizeof($array_id);
    }

    // Lay cac bai viet
    $posts = $BL->getPostByID($listid);

    // Kiem tra du lieu
    if (sizeof($posts) != $num) {
        die('NO');
    }

    $array_status = [];
    foreach ($posts as $row) {
        if ($controlstatus == 2) {
            $array_status[$row['id']] = 0;
        } else {
            if (!empty($row['title']) and !empty($row['alias']) and !empty($row['keywords']) and !empty($row['hometext']) and !empty($row['bodytext']) and !empty($row['catids']) and (empty($row['exptime']) or $row['exptime'] > NV_CURRENTTIME) and $row['pubtime'] > 0 and $row['pubtime'] <= NV_CURRENTTIME) {
                $array_status[$row['id']] = 1;
            } else {
                $array_status[$row['id']] = $row['status'];
            }
        }
    }

    foreach ($array_status as $id => $status) {
        $sql = "UPDATE " . $BL->table_prefix . "_rows SET status=" . $status . " WHERE id=" . $id;
        $db->query($sql);
    }

    // Xoa cache
    $nv_Cache->delMod($module_name);

    nv_htmlOutput('OK');
}

$page_title = $nv_Lang->getModule('blogList');
$per_page = 20;
$page = $nv_Request->get_absint('page', 'get', 1);

// SQL co ban
$sql = "FROM " . $BL->table_prefix . "_rows";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Bien tim kiem
$data_search = [
    "q" => nv_substr($nv_Request->get_title('q', 'get', ''), 0, 100),
    "from" => nv_substr($nv_Request->get_title('from', 'get', ''), 0, 100),
    "to" => nv_substr($nv_Request->get_title('to', 'get', ''), 0, 100),
    "catid" => $nv_Request->get_absint('catid', 'get', 0),
    "status" => $nv_Request->get_int('status', 'get', 10)
];

$where = [];
if (!empty($data_search['q'])) {
    $base_url .= "&amp;q=" . urlencode($data_search['q']);
    $where[] = "(
        title LIKE '%" . $db->dblikeescape($data_search['q']) . "%' OR
        hometext LIKE '%" . $db->dblikeescape($data_search['q']) . "%' OR
        bodytext LIKE '%" . $db->dblikeescape($data_search['q']) . "%'
    )";
}
if (!empty($data_search['from'])) {
    unset($match);
    if (preg_match("/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['from'], $match)) {
        $from = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
        $where[] = "posttime >= " . $from;
        $base_url .= "&amp;from=" . $data_search['from'];
    }
}
if (!empty($data_search['to'])) {
    unset($match);
    if (preg_match("/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['to'], $match)) {
        $to = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
        $where[] = "posttime <= " . $to;
        $base_url .= "&amp;to=" . $data_search['to'];
    }
}
if (!empty($data_search['catid'])) {
    $base_url .= "&amp;catid=" . $data_search['catid'];
    $where[] = $BL->build_query_search_id($data_search['catid'], 'catids');
}
if (in_array($data_search['status'], $BL->blogStatus)) {
    $base_url .= "&amp;status=" . $data_search['status'];
    $where[] = "status=" . $data_search['status'];
}
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

// Du lieu sap xep
$order = [];
$check_order = [
    "ASC",
    "DESC",
    "NO"
];
$opposite_order = [
    "NO" => "ASC",
    "DESC" => "ASC",
    "ASC" => "DESC"
];
$lang_order_1 = [
    "NO" => $nv_Lang->getModule('filter_lang_asc'),
    "DESC" => $nv_Lang->getModule('filter_lang_asc'),
    "ASC" => $nv_Lang->getModule('filter_lang_desc')
];
$lang_order_2 = [
    "title" => $nv_Lang->getModule('title'),
    "posttime" => $nv_Lang->getModule('blogposttime'),
    "updatetime" => $nv_Lang->getModule('blogupdatetime'),
];

$order['title']['order'] = $nv_Request->get_title('order_title', 'get', 'NO');
$order['posttime']['order'] = $nv_Request->get_title('order_posttime', 'get', 'NO');
$order['updatetime']['order'] = $nv_Request->get_title('order_updatetime', 'get', 'NO');

foreach ($order as $key => $check) {
    $order[$key]['data'] = [
        'key' => strtolower($order[$key]['order']),
        'url' => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
        'title' => sprintf($nv_Lang->getModule('filter_order_by'), "&quot;" . $lang_order_2[$key] . "&quot;") . " " . $lang_order_1[$order[$key]['order']]
    ];

    if (!in_array($check['order'], $check_order)) {
        $order[$key]['order'] = "NO";
    } else {
        $base_url .= "&amp;order_" . $key . "=" . $order[$key]['order'];
    }
}

if ($order['title']['order'] != "NO") {
    $sql .= " ORDER BY title " . $order['title']['order'];
} elseif ($order['posttime']['order'] != "NO") {
    $sql .= " ORDER BY posttime " . $order['posttime']['order'];
} elseif ($order['updatetime']['order'] != "NO") {
    $sql .= " ORDER BY updatetime " . $order['updatetime']['order'];
} else {
    $sql .= " ORDER BY id DESC";
}

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

$sql = "SELECT * " . $sql . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);

$template = get_tpl_dir([$global_config['module_theme'], $global_config['admin_theme']], 'admin_default', '/modules/' . $module_file . '/blog-list.tpl');
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'date', 'nv_date');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$array = [];
while ($row = $result->fetch()) {
    $row['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'], true);
    $array[$row['id']] = $row;
}

$tpl->assign('ARRAY', $array);
$tpl->assign('DATA_SEARCH', $data_search);
$tpl->assign('DATA_ORDER', $order);
$tpl->assign('GENERATE_PAGE', nv_generate_page($base_url, $all_page, $per_page, $page));
$tpl->assign('LIST_CATS', $global_array_cat);
$tpl->assign('BLOGSTATUS', $BL->blogStatus);

$contents = $tpl->fetch('blog-list.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
