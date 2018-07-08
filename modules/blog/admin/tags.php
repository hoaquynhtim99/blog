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

// Autocomplete tags
if ($nv_Request->isset_request('ajaxTags', 'post')) {
    $contents = array();
    $q = nv_substr($nv_Request->get_title("ajaxTags", "post", "", 1), 0, 255);

    if (!empty($q)) {
        $sql = "SELECT * FROM " . $BL->table_prefix . "_tags WHERE title LIKE '%" . $db->dblikeescape($q) . "%' ORDER BY title ASC LIMIT 0, 20";
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            $contents[] = array(
                "value" => $row['id'],
                "label" => nv_unhtmlspecialchars($row['title']),
            );
        }
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo json_encode($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    die();
}

// Tim chinh xac tags
if ($nv_Request->isset_request('searchTags', 'post')) {
    $contents = array("id" => 0, "title" => "");

    $tags = nv_substr($nv_Request->get_title("searchTags", "post", "", 1), 0, 255);

    if (!empty($tags)) {
        $sql = "SELECT * FROM " . $BL->table_prefix . "_tags WHERE title=" . $db->quote($tags) . " LIMIT 0,1";
        $result = $db->query($sql);

        if ($result->rowCount()) {
            $row = $result->fetch();

            $contents['id'] = $row['id'];
            $contents['title'] = $row['title'];
        }
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo json_encode($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    die();
}

// Xoa tags
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
        $sql = "DELETE FROM " . $BL->table_prefix . "_tags WHERE id=" . $id;
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $BL->lang('tagsDelete'), implode(", ", $listid), $admin_info['userid']);

    nv_htmlOutput('OK');
}

$page_title = $BL->lang('tagsMg');

$xtpl = new XTemplate("tags.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

// Them va sua tags
$id = $nv_Request->get_int("id", "post,get", 0);
$error = "";

if ($id) {
    $sql = "SELECT title, alias, keywords, description FROM " . $BL->table_prefix . "_tags WHERE id=" . $id;
    $result = $db->query($sql);
    if ($result->rowCount() != 1)
        nv_info_die($BL->glang('error_404_title'), $BL->glang('error_404_title'), $BL->glang('error_404_content'));

    $row = $result->fetch();
    $data = $row;
} else {
    $data = array(
        "title" => "",
        "alias" => "",
        "keywords" => "",
        "description" => "",
    );
}

// Tao nhanh (chi can kiem tra tieu de)
$quickCreat = $nv_Request->get_int("quick", 'post', 0);

if ($nv_Request->isset_request("submit", "post")) {
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $data['alias'] = nv_substr($nv_Request->get_title('alias', 'post', '', 1), 0, 255);
    $data['keywords'] = nv_substr($nv_Request->get_title('keywords', 'post', '', 1), 0, 255);
    $data['description'] = nv_substr($nv_Request->get_title('description', 'post', '', 1), 0, 255);

    // Tao lien ket tinh + chuan hoa lien ket tin
    if (empty($data['alias'])) {
        if ($quickCreat) {
            $data['alias'] = $BL->creatAlias($data['title'], 'tags');
        } else {
            $data['alias'] = strtolower(change_alias($data['title']));
        }
    } else {
        $data['alias'] = strtolower(change_alias($data['alias']));
    }

    // Chuan hoa tu khoa
    $data['keywords'] = $data['keywords'] ? implode(", ", array_filter(array_unique(array_map("trim", explode(",", $data['keywords']))))) : "";

    if (empty($data['title'])) {
        $error = $BL->lang('tagsErrorTitle');
    } elseif (empty($data['keywords']) and empty($quickCreat)) {
        $error = $BL->lang('errorKeywords');
    } elseif (empty($data['description']) and empty($quickCreat)) {
        $error = $BL->lang('errorSescription');
    } elseif ($BL->checkExistsAlias($data['alias'], "tags", $id)) {
        $error = $BL->lang('errorAliasExists');
    } else {
        if (empty($id)) {
            $sql = "INSERT INTO " . $BL->table_prefix . "_tags VALUES (
				NULL, 
				" . $db->quote($data['title']) . ", 
				" . $db->quote($data['alias']) . ", 
				" . $db->quote($data['keywords']) . ", 
				" . $db->quote($data['description']) . ", 
				0
			)";

            $newid = $db->insert_id($sql);

            if ($newid) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $BL->lang('tagsAdd'), $data['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                // Tra ve neu tao nhanh
                if ($quickCreat) {
                    $contents = array(
                        "error" => 0,
                        "id" => $newid,
                        "title" => $data['title'],
                        "message" => "",
                        );

                    include NV_ROOTDIR . '/includes/header.php';
                    echo json_encode($contents);
                    include NV_ROOTDIR . '/includes/footer.php';
                    die();
                }

                nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=tags");
            } else {
                $error = $BL->lang('errorSaveUnknow');
            }
        } elseif (empty($quickCreat)) {
            $sql = "UPDATE " . $BL->table_prefix . "_tags SET 
				title=" . $db->quote($data['title']) . ", 
				alias=" . $db->quote($data['alias']) . ", 
				keywords=" . $db->quote($data['keywords']) . ", 
				description=" . $db->quote($data['description']) . "
			WHERE id=" . $id;

            if ($db->query($sql)) {
                $BL->fixTags($id);

                nv_insert_logs(NV_LANG_DATA, $module_name, $BL->lang('tagsEditLog'), $row['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=tags");
            } else {
                $error = $BL->lang('errorUpdateUnknow');
            }
        }
    }
}

// Xuat loi neu la tao nhanh
if ($quickCreat and !empty($error)) {
    $contents = array(
        "error" => 1,
        "message" => $error,
    );

    include NV_ROOTDIR . '/includes/header.php';
    echo json_encode($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    die();
}

// Xuat thong tin them, sua
$xtpl->assign('TABLE_CONTENT_CAPTION', !$id ? $BL->lang('tagsAdd') : sprintf($BL->lang('tagsEdit'), $row['title']));
$xtpl->assign('DATA', $data);

// Phan trang
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 60;

// Base data
$sql = "FROM " . $BL->table_prefix . "_tags WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Du lieu tim kiem
$data_search = array(
    "q" => nv_substr($nv_Request->get_title('q', 'get', '', 1), 0, 100),
    "noComplete" => $nv_Request->get_int('noComplete', 'get', 0),
    "disabled" => " disabled=\"disabled\""
);

// Cam an nut huy tim kiem
if (!empty($data_search['q']) or !empty($data_search['noComplete'])) {
    $data_search['disabled'] = "";
}

// Query tim kiem
if (!empty($data_search['q'])) {
    $base_url .= "&amp;q=" . urlencode($data_search['q']);
    $sql .= " AND title LIKE '%" . $db->dblikeescape($data_search['q']) . "%'";
}
if (!empty($data_search['noComplete'])) {
    $base_url .= "&amp;noComplete=1";
    $sql .= " AND ( keywords='' OR description='' )";
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
    "title" => $BL->lang('title'),
    "numposts" => $BL->lang('numposts'),
);

$order['title']['order'] = $nv_Request->get_title('order_title', 'get', 'NO');
$order['numposts']['order'] = $nv_Request->get_title('order_numposts', 'get', 'NO');

foreach ($order as $key => $check) {
    $order[$key]['data'] = array(
        "class" => "order" . strtolower($order[$key]['order']),
        "url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
        "title" => sprintf($lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;") . " " . $lang_order_1[$order[$key]['order']]
    );

    if (!in_array($check['order'], $check_order)) {
        $order[$key]['order'] = "NO";
    } else {
        $base_url .= "&amp;order_" . $key . "=" . $order[$key]['order'];
    }
}

if ($order['title']['order'] != "NO") {
    $sql .= " ORDER BY title " . $order['title']['order'];
} elseif ($order['numposts']['order'] != "NO") {
    $sql .= " ORDER BY numposts " . $order['numposts']['order'];
} else {
    $sql .= " ORDER BY id DESC";
}

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

// Xay dung du lieu
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);

// Xuat tags
while ($row = $result->fetch()) {
    $row['urlEdit'] = $base_url . "&amp;page=" . $page . "&amp;id=" . $row['id'] . "#edit";
    $row['class'] = ($i++ % 2 == 0) ? " class=\"second\"" : "";

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

// Cac thao tac
$list_action = array(
    0 => array(
        "key" => 1,
        "class" => "delete",
        "title" => $BL->glang('delete')
    ), 
);

foreach ($list_action as $action) {
    $xtpl->assign('ACTION', $action);
    $xtpl->parse('main.action');
}

// Xuat du lieu phuc vu tim kiem
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA_SEARCH', $data_search);
$xtpl->assign('DATA_ORDER', $order);
$xtpl->assign('URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);

// Phan trang
$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

// Xuat loi
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
