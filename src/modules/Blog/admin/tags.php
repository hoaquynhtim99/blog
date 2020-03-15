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

// Autocomplete tags
if ($nv_Request->isset_request('ajaxTags', 'post')) {
    $contents = [];
    $q = nv_substr($nv_Request->get_title("ajaxTags", "post", ""), 0, 250);

    if (!empty($q)) {
        $sql = "SELECT * FROM " . $BL->table_prefix . "_tags WHERE title LIKE '%" . $db->dblikeescape($q) . "%' ORDER BY title ASC LIMIT 20";
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            $contents[] = [
                "value" => $row['id'],
                "label" => nv_unhtmlspecialchars($row['title']),
            ];
        }
    }

    nv_jsonOutput($contents);
}

// Tim chinh xac tags
if ($nv_Request->isset_request('searchTags', 'post')) {
    $contents = [
        "id" => 0,
        "title" => ""
    ];

    $tags = nv_substr($nv_Request->get_title("searchTags", "post", ""), 0, 250);

    if (!empty($tags)) {
        $sql = "SELECT * FROM " . $BL->table_prefix . "_tags WHERE title=" . $db->quote($tags) . " LIMIT 1";
        $result = $db->query($sql);

        if ($result->rowCount()) {
            $row = $result->fetch();

            $contents['id'] = $row['id'];
            $contents['title'] = $row['title'];
        }
    }

    nv_jsonOutput($contents);
}

// Xóa bỏ
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $id = $nv_Request->get_int('id', 'post', 0);
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
        $sql = "DELETE FROM " . $BL->table_prefix . "_tags WHERE id=" . $id;
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('tagsDelete'), implode(", ", $listid), $admin_info['userid']);

    nv_htmlOutput('OK');
}

$page_title = $nv_Lang->getModule('tagsMg');

$tpl = new \NukeViet\Template\Smarty();
$tpl->registerPlugin('modifier', 'format', 'number_format');
$tpl->registerPlugin('modifier', 'date', 'nv_date');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

// Them va sua tags
$id = $nv_Request->get_absint("id", "post,get", 0);
$error = "";
$is_submit = false;

if ($id) {
    $sql = "SELECT title, alias, keywords, description FROM " . $BL->table_prefix . "_tags WHERE id=" . $id;
    $result = $db->query($sql);
    if ($result->rowCount() != 1) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'));
    }

    $row = $result->fetch();
    $data = $row;
} else {
    $data = [
        "title" => "",
        "alias" => "",
        "keywords" => "",
        "description" => "",
    ];
}

// Tao nhanh (chi can kiem tra tieu de)
$quickCreat = $nv_Request->get_int("quick", 'post', 0);

if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    $is_submit = true;
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 250);
    $data['alias'] = nv_substr($nv_Request->get_title('alias', 'post', ''), 0, 250);
    $data['keywords'] = nv_substr($nv_Request->get_title('keywords', 'post', ''), 0, 255);
    $data['description'] = nv_substr($nv_Request->get_title('description', 'post', ''), 0, 255);

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
        $error = $nv_Lang->getModule('tagsErrorTitle');
    } elseif (empty($data['keywords']) and empty($quickCreat)) {
        $error = $nv_Lang->getModule('errorKeywords');
    } elseif (empty($data['description']) and empty($quickCreat)) {
        $error = $nv_Lang->getModule('errorSescription');
    } elseif ($BL->checkExistsAlias($data['alias'], "tags", $id)) {
        $error = $nv_Lang->getModule('errorAliasExists');
    } else {
        if (empty($id)) {
            $sql = "INSERT INTO " . $BL->table_prefix . "_tags (
                title, alias, keywords, description, numposts
            ) VALUES (
                " . $db->quote($data['title']) . ",
                " . $db->quote($data['alias']) . ",
                " . $db->quote($data['keywords']) . ",
                " . $db->quote($data['description']) . ",
                0
            )";

            $newid = $db->insert_id($sql);

            if ($newid) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('tagsAdd'), $data['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                // Tra ve neu tao nhanh
                if ($quickCreat) {
                    $contents = [
                        "error" => 0,
                        "id" => $newid,
                        "title" => $data['title'],
                        "message" => "",
                    ];

                    nv_jsonOutput($contents);
                }

                nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            } else {
                $error = $nv_Lang->getModule('errorSaveUnknow');
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

                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('tagsEditLog'), $row['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            } else {
                $error = $nv_Lang->getModule('errorUpdateUnknow');
            }
        }
    }
}

// Xuat loi neu la tao nhanh
if ($quickCreat and !empty($error)) {
    $contents = [
        "error" => 1,
        "message" => $error,
    ];
    nv_jsonOutput($contents);
}

$tpl->assign('IS_SUBMIT', $is_submit);
$tpl->assign('ID', $id);
$tpl->assign('DATA', $data);

// Phan trang
$page = $nv_Request->get_absint('page', 'get', 1);
$per_page = 30;

// Base data
$sql = "FROM " . $BL->table_prefix . "_tags";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$where = [];

// Du lieu tim kiem
$data_search = [
    "q" => nv_substr($nv_Request->get_title('q', 'get', ''), 0, 100),
    "noComplete" => $nv_Request->get_int('noComplete', 'get', 0),
];

// Query tim kiem
if (!empty($data_search['q'])) {
    $base_url .= "&amp;q=" . urlencode($data_search['q']);
    $where[] = "title LIKE '%" . $db->dblikeescape($data_search['q']) . "%'";
}
if (!empty($data_search['noComplete'])) {
    $base_url .= "&amp;noComplete=1";
    $where[] = " ( keywords='' OR description='' )";
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
    "numposts" => $nv_Lang->getModule('numposts'),
];

$order['title']['order'] = $nv_Request->get_title('order_title', 'get', 'NO');
$order['numposts']['order'] = $nv_Request->get_title('order_numposts', 'get', 'NO');

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

if ($order['title']['order'] != "NO") {
    $sql .= " ORDER BY title " . $order['title']['order'];
} elseif ($order['numposts']['order'] != "NO") {
    $sql .= " ORDER BY numposts " . $order['numposts']['order'];
} else {
    $sql .= " ORDER BY id DESC";
}

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

$sql = "SELECT * " . $sql . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);
$array = $result->fetchAll();

$tpl->assign('PREFIX_EDIT', $base_url . '&amp;page=' . $page);
$tpl->assign('DATA_ORDER', $order);
$tpl->assign('ARRAY', $array);
$tpl->assign('DATA_SEARCH', $data_search);
$tpl->assign('GENERATE_PAGE', nv_generate_page($base_url, $all_page, $per_page, $page));
$tpl->assign('ERROR', $error);

$contents = $tpl->fetch('tags.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
