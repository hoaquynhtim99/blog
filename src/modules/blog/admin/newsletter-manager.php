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

// Xóa email đăng ký nhận tin
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $id = $nv_Request->get_absint('id', 'post', 0);
    $list_levelid = $nv_Request->get_title('listid', 'post', '');

    if (empty($id) and empty($list_levelid)) {
        nv_htmlOutput('NO');
    }

    $listid = [];
    if ($id) {
        $listid[] = $id;
        $num = 1;
    } else {
        $list_levelid = explode(',', $list_levelid);
        $list_levelid = array_map('intval', $list_levelid);
        $list_levelid = array_filter($list_levelid);

        $listid = $list_levelid;
        $num = sizeof($list_levelid);
    }

    foreach ($listid as $id) {
        $sql = "DELETE FROM " . $BL->table_prefix . "_newsletters WHERE id=" . $id;
        $db->query($sql);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nltDelete'), implode(", ", $listid), $admin_info['userid']);
    nv_htmlOutput('OK');
}

// Thay doi hoat dong email nhan tin
if ($nv_Request->isset_request('changestatus', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $controlstatus = $nv_Request->get_int('status', 'post', 0);
    $array_id = $nv_Request->get_title('listid', 'post', '');

    if (empty($id) and empty($array_id)) {
        nv_htmlOutput('NO');
    }

    $listid = [];
    if ($id) {
        $listid[] = $id;
        $num = 1;
    } else {
        $array_id = explode(',', $array_id);
        $array_id = array_map('intval', $array_id);
        $array_id = array_filter($array_id);

        $listid = $array_id;
        $num = sizeof($array_id);
    }

    // Lay thong tin
    $sql = "SELECT id, status FROM " . $BL->table_prefix . "_newsletters WHERE id IN (" . implode(",", $listid) . ")";
    $result = $db->query($sql);
    $check = $result->rowCount();

    if ($check != $num) {
        nv_htmlOutput('NO');
    }

    $array_status = [];
    $array_title = [];
    while (list($id, $status) = $result->fetch(3)) {
        if (empty($controlstatus)) {
            $array_status[$id] = $status ? 0 : 1;
        } else {
            $array_status[$id] = ($controlstatus == 1) ? 1 : 0;
        }
    }

    foreach ($array_status as $id => $status) {
        $sql = "UPDATE " . $BL->table_prefix . "_newsletters SET status=" . $status . " WHERE id=" . $id;
        $db->query($sql);
    }

    nv_htmlOutput('OK');
}

// Tieu de trang
$page_title = $nv_Lang->getModule('nltList');

// Thong tin phan trang
$page = $nv_Request->get_absint('page', 'get', 1);
$per_page = 20;

// Query, url co so
$sql = "FROM " . $BL->table_prefix . "_newsletters";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$where = [];

// Tìm kiếm
$data_search = [
    'q' => nv_substr($nv_Request->get_title('q', 'get', ''), 0, 100),
];

if (!empty($data_search['q'])) {
    $base_url .= "&amp;q=" . urlencode($data_search['q']);
    $where[] = "email LIKE '%" . $db->dblikeescape($data_search['q']) . "%'";
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
    "email" => $nv_Lang->getModule('nltEmail'),
    "regtime" => $nv_Lang->getModule('nltregtime'),
    "lastsendtime" => $nv_Lang->getModule('nltlastsendtime'),
    "numemail" => $nv_Lang->getModule('nltnumemail'),
];

$order['email']['order'] = $nv_Request->get_title('order_email', 'get', 'NO');
$order['regtime']['order'] = $nv_Request->get_title('order_regtime', 'get', 'NO');
$order['lastsendtime']['order'] = $nv_Request->get_title('order_lastsendtime', 'get', 'NO');
$order['numemail']['order'] = $nv_Request->get_title('order_numemail', 'get', 'NO');

foreach ($order as $key => $check) {
    $order[$key]['data'] = [
        'key' => strtolower($order[$key]['order']),
        'url' => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
        'title' => $nv_Lang->getModule('filter_order_by', '&quot;' . $lang_order_2[$key] . '&quot;') . ' ' . $lang_order_1[$order[$key]['order']]
    ];

    if (!in_array($check['order'], $check_order)) {
        $order[$key]['order'] = "NO";
    } else {
        $base_url .= "&amp;order_" . $key . "=" . $order[$key]['order'];
    }
}

if ($order['email']['order'] != "NO") {
    $sql .= " ORDER BY email " . $order['email']['order'];
} elseif ($order['regtime']['order'] != "NO") {
    $sql .= " ORDER BY regtime " . $order['regtime']['order'];
} elseif ($order['lastsendtime']['order'] != "NO") {
    $sql .= " ORDER BY lastsendtime " . $order['lastsendtime']['order'];
} elseif ($order['numemail']['order'] != "NO") {
    $sql .= " ORDER BY numemail " . $order['numemail']['order'];
} else {
    $sql .= " ORDER BY status ASC, regtime DESC";
}

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

$sql = "SELECT * " . $sql . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);
$array = $result->fetchAll();

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'format', 'number_format');
$tpl->registerPlugin('modifier', 'date', 'nv_date');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('DATA_ORDER', $order);
$tpl->assign('ARRAY', $array);
$tpl->assign('DATA_SEARCH', $data_search);
$tpl->assign('GENERATE_PAGE', nv_generate_page($base_url, $all_page, $per_page, $page));

$contents = $tpl->fetch('newsletter-list.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
