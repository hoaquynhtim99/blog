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

$page_title = $BL->lang('cfgSys');
$set_active_op = 'config-master';

$array = array();

// Lay thong tin submit
if ($nv_Request->isset_request('submit', 'post')) {
    $array['sysDismissAdminCache'] = $nv_Request->get_int('sysDismissAdminCache', 'post', 0) ? 1 : 0;
    $array['sysRedirect2Home'] = $nv_Request->get_int('sysRedirect2Home', 'post', 0) ? 1 : 0;

    foreach ($array as $config_name => $config_value) {
        $sql = "REPLACE INTO " . $BL->table_prefix . "_config VALUES (" . $db->quote($config_name) . "," . $db->quote($config_value) . ")";
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);

    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    die();
}

$xtpl = new XTemplate("config-sys.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('DATA', $BL->setting);

$xtpl->assign('SYSDISMISSADMINCACHE', $BL->setting['sysDismissAdminCache'] ? " checked=\"checked\"" : "");
$xtpl->assign('SYSREDIRECT2HOME', $BL->setting['sysRedirect2Home'] ? " checked=\"checked\"" : "");

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
