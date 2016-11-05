<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if (!defined('NV_BLOG_ADMIN'))
    die('Stop!!!');

// Xoa email dang ky nhan ban tin
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $id = $nv_Request->get_int('id', 'post', 0);
    $list_levelid = $nv_Request->get_title('listid', 'post', '');

    if (empty($id) and empty($list_levelid))
        die('NO');

    $listid = array();
    if ($id) {
        $listid[] = $id;
        $num = 1;
    } else {
        $list_levelid = explode(",", $list_levelid);
        $list_levelid = array_map("trim", $list_levelid);
        $list_levelid = array_filter($list_levelid);

        $listid = $list_levelid;
        $num = sizeof($list_levelid);
    }

    foreach ($listid as $id) {
        $sql = "DELETE FROM " . $BL->table_prefix . "_newsletters WHERE id=" . $id;
        $db->query($sql);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $BL->lang('nltDelete'), implode(", ", $listid), $admin_info['userid']);

    die('OK');
}

// Thay doi hoat dong email nhan tin
if ($nv_Request->isset_request('changestatus', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $id = $nv_Request->get_int('id', 'post', 0);
    $controlstatus = $nv_Request->get_int('status', 'post', 0);
    $array_id = $nv_Request->get_title('listid', 'post', '');

    if (empty($id) and empty($array_id))
        die('NO');

    $listid = array();
    if ($id) {
        $listid[] = $id;
        $num = 1;
    } else {
        $array_id = explode(",", $array_id);
        $array_id = array_map("trim", $array_id);
        $array_id = array_filter($array_id);

        $listid = $array_id;
        $num = count($array_id);
    }

    // Lay thong tin
    $sql = "SELECT id, status FROM " . $BL->table_prefix . "_newsletters WHERE id IN (" . implode(",", $listid) . ")";
    $result = $db->query($sql);
    $check = $result->rowCount();

    if ($check != $num)
        die('NO');

    $array_status = array();
    $array_title = array();
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

    die('OK');
}

// Tieu de trang
$page_title = $BL->lang('nltList');

// Thong tin phan trang
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 50;

// Query, url co so
$sql = "FROM " . $BL->table_prefix . "_newsletters WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Du lieu tim kiem
$data_search = array("q" => nv_substr($nv_Request->get_title('q', 'get', '', 1), 0, 100), "disabled" => " disabled=\"disabled\"");

// Cam an nut huy tim kiem
if (!empty($data_search['q']) or !empty($data_search['singer'])) {
    $data_search['disabled'] = "";
}

// Query tim kiem
if (!empty($data_search['q'])) {
    $base_url .= "&amp;q=" . urlencode($data_search['q']);
    $sql .= " AND email LIKE '%" . $db->dblikeescape($data_search['q']) . "%'";
}

// Du lieu sap xep
$order = array();
$check_order = array(
    "ASC",
    "DESC",
    "NO"
);
$opposite_order = array(
    "NO" => "ASC",
    "DESC" => "ASC",
    "ASC" => "DESC"
);
$lang_order_1 = array(
    "NO" => $BL->lang('filter_lang_asc'),
    "DESC" => $BL->lang('filter_lang_asc'),
    "ASC" => $BL->lang('filter_lang_desc')
);
$lang_order_2 = array(
    "email" => $BL->lang('nltEmail'),
    "regtime" => $BL->lang('nltregtime'),
    "lastsendtime" => $BL->lang('nltlastsendtime'),
    "numemail" => $BL->lang('nltnumemail'),
);

$order['email']['order'] = $nv_Request->get_title('order_email', 'get', 'NO');
$order['regtime']['order'] = $nv_Request->get_title('order_regtime', 'get', 'NO');
$order['lastsendtime']['order'] = $nv_Request->get_title('order_lastsendtime', 'get', 'NO');
$order['numemail']['order'] = $nv_Request->get_title('order_numemail', 'get', 'NO');

foreach ($order as $key => $check) {
    $order[$key]['data'] = array(
        "class" => "order" . strtolower($order[$key]['order']),
        "url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
        "title" => sprintf($BL->lang('filter_order_by'), "&quot;" . $lang_order_2[$key] . "&quot;") . " " . $lang_order_1[$order[$key]['order']]
    );

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

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

// Xay dung du lieu
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);

$array = array();
while ($row = $result->fetch()) {
    $array[] = array(
        "id" => $row['id'],
        "email" => $row['email'],
        "regip" => $row['regip'],
        "numemail" => number_format($row['numemail'], 0, '.', '.'),
        "regtime" => nv_date("H:i d/m/Y", $row['regtime']),
        "confirmtime" => nv_date("H:i d/m/Y", $row['confirmtime']),
        "lastsendtime" => nv_date("H:i d/m/Y", $row['lastsendtime']),
        "status" => $BL->lang('nltstatus' . $row['status']),
        "class" => ($i % 2 == 0) ? " class=\"second\"" : ""
    );
    $i++;
}

// Cac thao tac
$list_action = array(
    0 => array(
        "key" => 1,
        "class" => "delete",
        "title" => $BL->glang('delete')
    ),
    1 => array(
        "key" => 2,
        "class" => "status-ok",
        "title" => $BL->lang('action_status_ok')
    ),
    2 => array(
        "key" => 3,
        "class" => "status-no",
        "title" => $BL->lang('action_status_no')
    )
);

// Phan trang
$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

$xtpl = new XTemplate("newsletter-list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA_SEARCH', $data_search);
$xtpl->assign('DATA_ORDER', $order);
$xtpl->assign('URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);

foreach ($list_action as $action) {
    $xtpl->assign('ACTION', $action);
    $xtpl->parse('main.action');
}

foreach ($array as $row) {
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
