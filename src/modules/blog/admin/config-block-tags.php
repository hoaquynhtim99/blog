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

$page_title = $nv_Lang->getModule('cfgBlockTags');
$set_active_op = 'config-master';
$array = [];

// Lay thong tin submit
if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    $array['blockTagsShowType'] = nv_substr($nv_Request->get_title('blockTagsShowType', 'post', 'random', 1), 0, 255);
    $array['blockTagsNums'] = $nv_Request->get_absint('blockTagsNums', 'post', 10);
    $array['blockTagsCacheIfRandom'] = intval($nv_Request->get_bool('blockTagsCacheIfRandom', 'post', false));
    $array['blockTagsCacheLive'] = $nv_Request->get_absint('blockTagsCacheLive', 'post', 0);

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

$template = get_tpl_dir([$global_config['module_theme'], $global_config['admin_theme']], 'admin_default', '/modules/' . $module_file . '/config-block-tags.tpl');
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$tpl->assign('SHOWTYPE', $BL->blockTagsShowType);
$tpl->assign('DATA', $BL->setting);

$contents = $tpl->fetch('config-block-tags.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
