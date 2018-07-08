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

// Ham xoa danh muc
function nv_del_cat($catid, $db, $module_data, $BL)
{
    global $admin_info;

    $sql = "SELECT parentid, title FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    list($parentid, $catTitle) = $db->query($sql)->fetch(3);

    $sql = "SELECT id FROM " . $BL->table_prefix . "_categories WHERE parentid=" . $catid;
    $result = $db->query($sql);

    while (list($id) = $result->fetch(3)) {
        nv_del_cat($id, $db, $module_data, $BL);
    }

    // Xoa bang danh muc
    $sql = "DELETE FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    $db->query($sql);

    // Cap nhat thong ke danh muc
    $BL->fixCat($parentid);

    // Ghi nhat ky
    nv_insert_logs(NV_LANG_DATA, $module_data, $BL->lang('categoriesDelete'), $catTitle, $admin_info['userid']);
}

// Thay doi thu tu chuyen muc
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $catid = $nv_Request->get_int('id', 'post', 0);
    $new = $nv_Request->get_int('new', 'post', 0);

    if (empty($catid))
        die('NO');

    $sql = "SELECT parentid FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    $result = $db->query($sql);
    $numrows = $result->rowCount();
    if ($numrows != 1)
        die('NO');
    $parentid = $result->fetchColumn();

    $sql = "SELECT id FROM " . $BL->table_prefix . "_categories WHERE id!=" . $catid . " AND parentid=" . $parentid . " ORDER BY weight ASC";
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        $weight++;
        if ($weight == $new)
            $weight++;
        $sql = "UPDATE " . $BL->table_prefix . "_categories SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }
    $sql = "UPDATE " . $BL->table_prefix . "_categories SET weight=" . $new . " WHERE id=" . $catid;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    nv_htmlOutput('OK');
}

// Cho hoat dong, dung hoat dong
if ($nv_Request->isset_request('changestatus', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $catid = $nv_Request->get_int('id', 'post', 0);

    if (empty($catid))
        die('NO');

    $query = "SELECT status FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    $result = $db->query($query);
    $numrows = $result->rowCount();
    if ($numrows != 1)
        die('NO');

    $status = $result->fetchColumn();
    $status = $status ? 0 : 1;

    $sql = "UPDATE " . $BL->table_prefix . "_categories SET status=" . $status . " WHERE id=" . $catid;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    nv_htmlOutput('OK');
}

// Xoa chuyen muc
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $catid = $nv_Request->get_int('id', 'post', 0);

    if (empty($catid)) {
        die('NO');
    }

    $sql = "SELECT COUNT(*) AS count, parentid FROM " . $BL->table_prefix . "_categories WHERE id=" . $catid;
    $result = $db->query($sql);
    list($count, $parentid) = $result->fetch(3);

    if ($count != 1) {
        die('NO');
    }

    nv_del_cat($catid, $db, $module_data, $BL);
    $BL->fixWeightCat($parentid);

    $nv_Cache->delMod($module_name);

    nv_htmlOutput('OK');
}

$page_title = $BL->lang('categoriesManager');

$xtpl = new XTemplate("categories.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

// Them va sua chuyen muc
$id = $nv_Request->get_int("id", "post,get", 0);
$error = "";

if ($id) {
    $sql = "SELECT id, parentid, title, alias, keywords, description, weight FROM " . $BL->table_prefix . "_categories WHERE id=" . $id;
    $result = $db->query($sql);
    if ($result->rowCount() != 1)
        nv_info_die($BL->glang('error_404_title'), $BL->glang('error_404_title'), $BL->glang('error_404_content'));

    $row = $result->fetch();
    $data = $row;
} else {
    $data = array(
        "parentid" => $nv_Request->get_int("parentid", "post,get", 0),
        "title" => "",
        "alias" => "",
        "keywords" => "",
        "description" => "",
    );
}

if ($nv_Request->isset_request("submit", "post")) {
    $data['parentid'] = $nv_Request->get_int("parentid", "post", 0);
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $data['alias'] = nv_substr($nv_Request->get_title('alias', 'post', '', 1), 0, 255);
    $data['keywords'] = nv_substr($nv_Request->get_title('keywords', 'post', '', 1), 0, 255);
    $data['description'] = nv_substr($nv_Request->get_title('description', 'post', '', 1), 0, 255);

    $data['alias'] = $data['alias'] ? strtolower(change_alias($data['alias'])) : strtolower(change_alias($data['title']));
    $data['keywords'] = $data['keywords'] ? implode(", ", array_filter(array_unique(array_map("trim", explode(",", $data['keywords']))))) : "";

    if (empty($data['title'])) {
        $error = $BL->lang('categoriesErrorTitle');
    } elseif (empty($data['keywords'])) {
        $error = $BL->lang('errorKeywords');
    } elseif (empty($data['description'])) {
        $error = $BL->lang('errorSescription');
    } elseif ($BL->checkExistsAlias($data['alias'], "cat", $id)) {
        $error = $BL->lang('errorAliasExists');
    } else {
        // Xac dinh thu tu moi
        $new_weight = 1;
        if (!$id or ($id and $data['parentid'] != $row['parentid'])) {
            $sql = "SELECT MAX(weight) AS new_weight FROM " . $BL->table_prefix . "_categories WHERE parentid=" . $data['parentid'];
            $result = $db->query($sql);
            $new_weight = $result->fetchColumn();
            $new_weight = (int)$new_weight;
            $new_weight++;
        } else {
            $new_weight = $row['weight'];
        }

        if ($id) {
            $sql = "UPDATE " . $BL->table_prefix . "_categories SET 
				parentid=" . $data['parentid'] . ", 
				title=" . $db->quote($data['title']) . ", 
				alias=" . $db->quote($data['alias']) . ", 
				keywords=" . $db->quote($data['keywords']) . ", 
				description=" . $db->quote($data['description']) . ", 
				weight=" . $new_weight . "
			WHERE id=" . $id;

            if ($db->query($sql)) {
                $BL->fixCat($id);

                if ($data['parentid'] != $row['parentid']) {
                    $BL->fixWeightCat($row['parentid']);
                    $BL->fixCat($row['parentid']);
                }

                nv_insert_logs(NV_LANG_DATA, $module_name, $BL->lang('categoriesEditLog'), $row['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=categories&parentid=" . $data['parentid']);
            } else {
                $error = $BL->lang('errorUpdateUnknow');
            }
        } else {
            $sql = "INSERT INTO " . $BL->table_prefix . "_categories VALUES (
				NULL, 
				" . $data['parentid'] . ", 
				" . $db->quote($data['title']) . ", 
				" . $db->quote($data['alias']) . ", 
				" . $db->quote($data['keywords']) . ", 
				" . $db->quote($data['description']) . ", 
				0, 0, " . $new_weight . ", 1
			)";

            $newid = $db->insert_id($sql);

            if ($newid) {
                $BL->fixCat($newid);
                nv_insert_logs(NV_LANG_DATA, $module_name, $BL->lang('categoriesAdd'), $data['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=categories&parentid=" . $data['parentid']);
            } else {
                $error = $BL->lang('errorSaveUnknow');
            }
        }
    }
}

// Xuat thong tin them, sua
$xtpl->assign('TABLE_CONTENT_CAPTION', !$id ? $BL->lang('categoriesAdd') : sprintf($BL->lang('categoriesEdit'), $row['title']));
$xtpl->assign('DATA', $data);

$listcats = array(array(
    'id' => 0,
    'name' => $BL->lang('categoriesMainCat'),
    'selected' => ""
));
$listcats = $listcats + $BL->listCat($data['parentid'], $id);
foreach ($listcats as $cat) {
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
}

// Cap danh muc can lay
$array_search = array();
$array_search['parentid'] = $nv_Request->get_int("parentid", "post,get", 0);

// Breadcrumbs
if ($array_search['parentid'] == 0) {
    $breadcrumbs = array($BL->lang('categoriesListMainCat'));
} else {
    $parentid = $array_search['parentid'];
    $breadcrumbs = array();

    while ($parentid > 0) {
        if (isset($listcats[$parentid])) {
            $breadcrumbs[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=categories&amp;parentid=" . $parentid . "\">" . $listcats[$parentid]['title'] . "</a>";
            $parentid = $listcats[$parentid]['parentid'];
        } else {
            $parentid = 0;
        }
    }
    $breadcrumbs[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=categories\">" . $BL->lang('categoriesListMainCat') . "</a>";
    sort($breadcrumbs, SORT_NUMERIC);
}

$xtpl->assign('BREADCRUMBS', implode(" &gt; ", $breadcrumbs));

// Danh sach cac danh muc
$sql = "SELECT * FROM " . $BL->table_prefix . "_categories WHERE parentid=" . $array_search['parentid'] . " ORDER BY weight ASC";
$result = $db->query($sql);
$numCat = $result->rowCount();

if (empty($numCat)) {
    $xtpl->parse('main.empty');
} else {
    $array = array();
    $i = 0;

    while ($_row = $result->fetch()) {
        if (!empty($_row['numsubs'])) {
            $_row['title'] .= " (<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=categories&amp;parentid=" . $_row['id'] . "\">" . sprintf($BL->lang('categoriesHasSub'), $_row['numsubs']) . "</a>)";
        }

        $_row['class'] = $i++ % 2 == 0 ? " class=\"second\"" : "";
        $_row['status'] = $_row['status'] ? " checked=\"checked\"" : "";
        $_row['urlEdit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=categories&amp;id=" . $_row['id'] . "#edit";

        $xtpl->assign('ROW', $_row);

        for ($j = 1; $j <= $numCat; $j++) {
            $weight = array();
            $weight['title'] = $j;
            $weight['pos'] = $j;
            $weight['selected'] = ($j == $_row['weight']) ? " selected=\"selected\"" : "";

            $xtpl->assign('WEIGHT', $weight);
            $xtpl->parse('main.data.loop.weight');
        }

        $xtpl->parse('main.data.loop');
    }

    $xtpl->parse('main.data');
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
