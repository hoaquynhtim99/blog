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

$page_title = $nv_Lang->getModule('cfgInsArt');
$set_active_op = 'config-master';

$array = [];
$error = '';

// Lấy thông tin submit
if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    $array['instantArticlesActive'] = $nv_Request->get_int('instantArticlesActive', 'post', 0) ? 1 : 0;
    $array['instantArticlesTemplate'] = nv_substr($nv_Request->get_title('instantArticlesTemplate', 'post', ''), 0, 100);
    $array['instantArticlesHttpauth'] = $nv_Request->get_int('instantArticlesHttpauth', 'post', 0) ? 1 : 0;
    $array['instantArticlesUsername'] = nv_substr($nv_Request->get_title('instantArticlesUsername', 'post', ''), 0, 100);
    $array['instantArticlesPassword'] = nv_substr($nv_Request->get_title('instantArticlesPassword', 'post', ''), 0, 100);
    $array['instantArticlesLivetime'] = $nv_Request->get_absint('instantArticlesLivetime', 'post', 0);
    $array['instantArticlesGettime'] = $nv_Request->get_absint('instantArticlesGettime', 'post', 0);

    if ($array['instantArticlesLivetime'] <= 0) {
        $array['instantArticlesLivetime'] = 60;
    }
    if ($array['instantArticlesGettime'] <= 0) {
        $array['instantArticlesGettime'] = 120;
    }

    if ($array['instantArticlesHttpauth'] and (empty($array['instantArticlesUsername']) or empty($array['instantArticlesPassword']))) {
        $error = $nv_Lang->getModule('cfgInsArtErrAccountAuth');
    } else {
        if (!empty($array['instantArticlesPassword'])) {
            $array['instantArticlesPassword'] = $crypt->encrypt($array['instantArticlesPassword']);
        }

        foreach ($array as $config_name => $config_value) {
            $sql = "REPLACE INTO " . $BL->table_prefix . "_config VALUES (" . $db->quote($config_name) . "," . $db->quote($config_value) . ")";
            $db->query($sql);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_INSART_CONFIG', '', $admin_info['userid']);
        $nv_Cache->delMod($module_name);
        nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    }
} else {
    $array['instantArticlesActive'] = $BL->setting['instantArticlesActive'];
    $array['instantArticlesTemplate'] = $BL->setting['instantArticlesTemplate'];
    $array['instantArticlesHttpauth'] = $BL->setting['instantArticlesHttpauth'];
    $array['instantArticlesUsername'] = $BL->setting['instantArticlesUsername'];
    $array['instantArticlesPassword'] = $BL->setting['instantArticlesPassword'] ? $crypt->decrypt($BL->setting['instantArticlesPassword']) : '';
    $array['instantArticlesLivetime'] = $BL->setting['instantArticlesLivetime'];
    $array['instantArticlesGettime'] = $BL->setting['instantArticlesGettime'];
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->registerPlugin('modifier', 'rewrite', 'nv_url_rewrite');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$tpl->assign('DATA', $array);
$tpl->assign('ERROR', $error);
$tpl->assign('ARRAY_CATS', $BL->listCat(0));
$tpl->assign('NV_MY_DOMAIN', NV_MY_DOMAIN);
$tpl->assign('MODULE_NAME', $module_name);

$contents = $tpl->fetch('config-instant-articles.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
