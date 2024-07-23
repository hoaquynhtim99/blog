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

$page_title = $nv_Lang->getModule('cfgStructureData');
$array = [];
$_array_locales = nv_object2array(simplexml_load_file(NV_ROOTDIR . '/modules/' . $module_file . '/locales/locales.xml')->xpath('locale'));
$array_locales = [];

foreach ($_array_locales as $locale) {
    $array_locales[$locale['codes']['code']['standard']['representation']] = $locale['englishName'];
}
unset($_array_locales, $locale);

// Lay thong tin submit
if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
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
            if (nv_is_file($array['sysDefaultImage'], NV_UPLOADS_DIR . '/' . $module_upload) !== true) {
                $array['sysDefaultImage'] = '';
            } else {
                $array['sysDefaultImage'] = substr($array['sysDefaultImage'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload));
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
    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('UPLOADS_PATH', NV_UPLOADS_DIR . '/' . $module_upload);

$tpl->assign('DATA', $BL->setting);
$tpl->assign('ARRAY_LOCALES', $array_locales);

$contents = $tpl->fetch('config-structured-data.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
