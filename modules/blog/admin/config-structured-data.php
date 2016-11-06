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

$BL->callFrameWorks('shadowbox');

$page_title = $BL->lang('cfgStructureData');

$array = array();

$_array_locales = nv_object2array(simplexml_load_file(NV_ROOTDIR . '/modules/' . $module_file . '/locales/locales.xml')->xpath('locale'));
$array_locales = array();

foreach ($_array_locales as $locale) {
    $array_locales[$locale['codes']['code']['standard']['representation']] = $locale['englishName'];
}

unset($_array_locales, $locale);

// Lay thong tin submit
if ($nv_Request->isset_request('submit', 'post')) {
    $array['sysGoogleAuthor'] = nv_substr($nv_Request->get_title('sysGoogleAuthor', 'post', '', 0), 0, 30);
    $array['sysFbAppID'] = nv_substr($nv_Request->get_title('sysFbAppID', 'post', '', 0), 0, 30);
    $array['sysFbAdminID'] = nv_substr($nv_Request->get_title('sysFbAdminID', 'post', '', 0), 0, 30);
    $array['sysLocale'] = nv_substr($nv_Request->get_title('sysLocale', 'post', '', 0), 0, 255);
    $array['sysDefaultImage'] = $nv_Request->get_string('sysDefaultImage', 'post', '');

    if (!preg_match("/^([0-9]+)$/", $array['sysGoogleAuthor'])) {
        $array['sysGoogleAuthor'] = '';
    }
    if (!preg_match("/^([0-9]+)$/", $array['sysFbAppID'])) {
        $array['sysFbAppID'] = '';
    }
    if (!preg_match("/^([0-9]+)$/", $array['sysFbAdminID'])) {
        $array['sysFbAdminID'] = '';
    }
    if (!empty($array['sysDefaultImage'])) {
        if (preg_match("/^\//i", $array['sysDefaultImage'])) {
            $array['sysDefaultImage'] = substr($array['sysDefaultImage'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name));

            if (!is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . $array['sysDefaultImage'])) {
                $array['sysDefaultImage'] = '';
            }
        } elseif (!nv_is_url($array['sysDefaultImage'])) {
            $array['sysDefaultImage'] = '';
        }
    }

    foreach ($array as $config_name => $config_value) {
        $sql = "REPLACE INTO " . $BL->table_prefix . "_config VALUES (" . $db->quote($config_name) . "," . $db->quote($config_value) . ")";
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);

    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    die();
}

$xtpl = new XTemplate("config-structured-data.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('UPLOADS_PATH', NV_UPLOADS_DIR . '/' . $module_name);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('DATA', $BL->setting);

$xtpl->assign('INITNEWSLETTERS', $BL->setting['initNewsletters'] ? " checked=\"checked\"" : "");

// Xuất ngôn ngữ và quốc gia
foreach ($array_locales as $k => $v) {
    $xtpl->assign('SYSLOCALE', array(
        'key' => $k,
        'title' => $v,
        'selected' => $k == $BL->setting['sysLocale'] ? " selected=\"selected\"" : "",
    ));
    $xtpl->parse('main.sysLocale');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
