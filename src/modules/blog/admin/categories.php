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

// Hàm xóa danh mục
function nv_del_cat($catid, $db, $module_data, $BL)
{
    global $admin_info, $nv_Lang;

    $sql = "SELECT parentid, title FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    list($parentid, $catTitle) = $db->query($sql)->fetch(3);

    $sql = "SELECT id FROM " . $BL->table_prefix . "_categories WHERE parentid=" . $catid;
    $result = $db->query($sql);

    while (list($id) = $result->fetch(3)) {
        nv_del_cat($id, $db, $module_data, $BL);
    }

    // Xóa trong bảng danh mục
    $sql = "DELETE FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    $db->query($sql);

    // Cập nhật thống kê danh mục
    $BL->updateCatStatistics($parentid);

    // Ghi nhật ký
    nv_insert_logs(NV_LANG_DATA, $module_data, $nv_Lang->getModule('categoriesDelete'), $catTitle, $admin_info['userid']);
}

// Thay đổi thứ tự danh mục
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $catid = $nv_Request->get_int('id', 'post', 0);
    $new = $nv_Request->get_int('new', 'post', 0);

    if (empty($catid)) {
        nv_htmlOutput('NO');
    }

    $sql = "SELECT parentid FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    $result = $db->query($sql);
    $numrows = $result->rowCount();
    if ($numrows != 1) {
        nv_htmlOutput('NO');
    }
    $parentid = $result->fetchColumn();

    $sql = "SELECT id FROM " . $BL->table_prefix . "_categories WHERE id!=" . $catid . " AND parentid=" . $parentid . " ORDER BY weight_level ASC";
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        $weight++;
        if ($weight == $new) {
            $weight++;
        }
        $sql = "UPDATE " . $BL->table_prefix . "_categories SET weight_level=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }
    $sql = "UPDATE " . $BL->table_prefix . "_categories SET weight_level=" . $new . " WHERE id=" . $catid;
    $db->query($sql);

    $BL->fixCatOrder();

    // Ghi nhật ký
    nv_insert_logs(NV_LANG_DATA, $module_data, 'LOG_CHANGE_CAT_WEIGHT', $catid, $admin_info['userid']);

    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK');
}

// Cho hoạt động, ngưng hoạt động
if ($nv_Request->isset_request('changestatus', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $catid = $nv_Request->get_int('id', 'post', 0);

    if (empty($catid)) {
        nv_htmlOutput('NO');
    }

    $query = "SELECT status FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    $result = $db->query($query);
    $numrows = $result->rowCount();
    if ($numrows != 1) {
        nv_htmlOutput('NO');
    }

    $status = $result->fetchColumn();
    $status = $status ? 0 : 1;

    $sql = "UPDATE " . $BL->table_prefix . "_categories SET status=" . $status . " WHERE id=" . $catid;
    $db->query($sql);

    // Ghi nhật ký
    nv_insert_logs(NV_LANG_DATA, $module_data, 'LOG_CHANGE_CAT_STATUS', $catid, $admin_info['userid']);

    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK');
}

// Xóa chuyên mục
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $catid = $nv_Request->get_int('id', 'post', 0);

    if (empty($catid)) {
        nv_htmlOutput('NO');
    }

    $sql = "SELECT COUNT(*) AS count, parentid FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    $result = $db->query($sql);
    list($count, $parentid) = $result->fetch(3);

    if ($count != 1) {
        nv_htmlOutput('NO');
    }

    nv_del_cat($catid, $db, $module_data, $BL);
    $BL->fixCatOrder();

    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK');
}

$page_title = $nv_Lang->getModule('categoriesManager');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('UPLOADS_PATH', NV_UPLOADS_DIR . '/' . $module_upload);

// Thêm và sửa chuyên mục
$id = $nv_Request->get_int('id', 'post,get', 0);
$error = '';
$is_submit = false;

if ($id) {
    $sql = "SELECT * FROM " . $BL->table_prefix . "_categories WHERE id=" . $id;
    $result = $db->query($sql);
    if ($result->rowCount() != 1) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'));
    }

    $row = $result->fetch();
    $data = $row;
} else {
    $data = [
        'parentid' => $nv_Request->get_int('parentid', 'post,get', 0),
        'title' => '',
        'alias' => '',
        'image' => '',
        'keywords' => '',
        'description' => '',
        'show_block' => 1,
    ];
}

if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    $is_submit = true;
    $data['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 250);
    $data['alias'] = nv_substr($nv_Request->get_title('alias', 'post', '', 1), 0, 250);
    $data['image'] = nv_substr($nv_Request->get_string('image', 'post', ''), 0, 255);
    $data['keywords'] = nv_substr($nv_Request->get_title('keywords', 'post', '', 1), 0, 255);
    $data['description'] = nv_substr($nv_Request->get_title('description', 'post', '', 1), 0, 255);
    $data['show_block'] = (int) $nv_Request->get_bool('show_block', 'post', false);

    $data['alias'] = $data['alias'] ? strtolower(change_alias($data['alias'])) : strtolower(change_alias($data['title']));
    $data['keywords'] = $data['keywords'] ? implode(', ', array_filter(array_unique(array_map('trim', explode(',', $data['keywords']))))) : '';
    if (nv_is_file($data['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $data['image'] = substr($data['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $data['image'] = '';
    }

    if (empty($data['title'])) {
        $error = $nv_Lang->getModule('categoriesErrorTitle');
    } elseif (empty($data['keywords'])) {
        $error = $nv_Lang->getModule('errorKeywords');
    } elseif (empty($data['description'])) {
        $error = $nv_Lang->getModule('errorSescription');
    } elseif ($BL->checkExistsAlias($data['alias'], 'cat', $id)) {
        $error = $nv_Lang->getModule('errorAliasExists');
    } else {
        // Xác định thứ tự mới
        $new_weight = 1;
        if (!$id or ($id and $data['parentid'] != $row['parentid'])) {
            $sql = "SELECT MAX(weight_level) FROM " . $BL->table_prefix . "_categories WHERE parentid=" . $data['parentid'];
            $new_weight = ((int) $db->query($sql)->fetchColumn()) + 1;
        } else {
            $new_weight = $row['weight_level'];
        }

        if ($id) {
            $sql = "UPDATE " . $BL->table_prefix . "_categories SET
                parentid=" . $data['parentid'] . ",
                title=" . $db->quote($data['title']) . ",
                alias=" . $db->quote($data['alias']) . ",
                image=" . $db->quote($data['image']) . ",
                keywords=" . $db->quote($data['keywords']) . ",
                description=" . $db->quote($data['description']) . ",
                show_block=" . $data['show_block'] . ",
                weight_level=" . $new_weight . "
            WHERE id=" . $id;

            if ($db->query($sql)) {
                $BL->updateCatStatistics($id);

                if ($data['parentid'] != $row['parentid']) {
                    $BL->updateCatStatistics($row['parentid']);
                    $BL->fixCatOrder();
                }

                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('categoriesEditLog'), $row['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=categories&parentid=' . $data['parentid']);
            } else {
                $error = $nv_Lang->getModule('errorUpdateUnknow');
            }
        } else {
            $sql = "INSERT INTO " . $BL->table_prefix . "_categories (
                parentid, title, alias, image, keywords, description, weight_level, status, show_block
            ) VALUES (
                " . $data['parentid'] . ",
                " . $db->quote($data['title']) . ",
                " . $db->quote($data['alias']) . ",
                " . $db->quote($data['image']) . ",
                " . $db->quote($data['keywords']) . ",
                " . $db->quote($data['description']) . ",
                " . $new_weight . ", 1, " . $data['show_block'] . "
            )";

            $newid = $db->insert_id($sql);

            if ($newid) {
                $BL->updateCatStatistics($newid);
                $BL->fixCatOrder();
                nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('categoriesAdd'), $data['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=categories&parentid=' . $data['parentid']);
            } else {
                $error = $nv_Lang->getModule('errorSaveUnknow');
            }
        }
    }
}

if (!empty($data['image'])) {
    $data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data['image'];
}

$tpl->assign('IS_SUBMIT', $is_submit);
$tpl->assign('ID', $id);
$tpl->assign('DATA', $data);
$tpl->assign('LISTCATS', $global_array_cat);

// Cap danh muc can lay
$array_search = [];
$array_search['parentid'] = $nv_Request->get_absint('parentid', 'post,get', 0);

// Breadcrumbs
if ($array_search['parentid'] == 0) {
    $breadcrumbs = [[
        'title' => $nv_Lang->getModule('categoriesListMainCat'),
        'link' => -1
    ]];
} else {
    $parentid = $array_search['parentid'];
    $breadcrumbs = [];

    while ($parentid > 0) {
        if (isset($global_array_cat[$parentid])) {
            $breadcrumbs[] = [
                'title' => $global_array_cat[$parentid]['title'],
                'link' => $parentid
            ];
            $parentid = $global_array_cat[$parentid]['parentid'];
        } else {
            $parentid = 0;
        }
    }
    $breadcrumbs[] = [
        'title' => $nv_Lang->getModule('categoriesListMainCat'),
        'link' => 0
    ];
    krsort($breadcrumbs, SORT_NUMERIC);
}

// Danh sách các danh mục
$sql = "SELECT * FROM " . $BL->table_prefix . "_categories WHERE parentid=" . $array_search['parentid'] . " ORDER BY weight_level ASC";
$result = $db->query($sql);

$tpl->assign('BREADCRUMBS', $breadcrumbs);
$tpl->assign('NUMCAT', $result->rowCount());
$tpl->assign('ARRAY', $result->fetchAll());
$tpl->assign('ERROR', $error);

$contents = $tpl->fetch('categories.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
