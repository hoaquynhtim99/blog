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

$page_title = $BL->lang('cfgBlockTags');
$set_active_op = 'config-master';

$array = array();

// Lay thong tin submit
if ($nv_Request->isset_request('submit', 'post')) {
    $array['blockTagsShowType'] = nv_substr($nv_Request->get_title('blockTagsShowType', 'post', 'random', 1), 0, 255);
    $array['blockTagsNums'] = $nv_Request->get_int('blockTagsNums', 'post', 10);
    $array['blockTagsCacheIfRandom'] = $nv_Request->get_int('blockTagsCacheIfRandom', 'post', 0);
    $array['blockTagsCacheLive'] = $nv_Request->get_int('blockTagsCacheLive', 'post', 0);

    // Kiem tra xac nhan
    if (!in_array($array['blockTagsShowType'], $BL->blockTagsShowType)) {
        $array['blockTagsShowType'] = $BL->blockTagsShowType[0];
    }
    if ($array['blockTagsNums'] <= 0) {
        $array['blockTagsNums'] = 1;
    }
    if ($array['blockTagsCacheLive'] <= 0) {
        $array['blockTagsCacheLive'] = 1;
    }

    $array['blockTagsCacheIfRandom'] = $array['blockTagsCacheIfRandom'] ? 1 : 0;

    foreach ($array as $config_name => $config_value) {
        $sql = "REPLACE INTO " . $BL->table_prefix . "_config VALUES (" . $db->quote($config_name) . "," . $db->quote($config_value) . ")";
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);

    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
}

$xtpl = new XTemplate("config-block-tags.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('DATA', $BL->setting);

$xtpl->assign('BLOCKTAGSCACHEIFRANDOM', $BL->setting['blockTagsCacheIfRandom'] ? " checked=\"checked\"" : "");

// Xuat cac kieu hien thi
foreach ($BL->blockTagsShowType as $type) {
    $xtpl->assign('BLOCKTAGSSHOWTYPE', array(
        "key" => $type,
        "title" => $BL->lang('cfgblockTagsShowType_' . $type),
        "selected" => $type == $BL->setting['blockTagsShowType'] ? " selected=\"selected\"" : "")
    );
    $xtpl->parse('main.blockTagsShowType');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
