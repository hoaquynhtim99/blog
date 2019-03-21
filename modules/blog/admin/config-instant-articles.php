<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if (!defined('NV_BLOG_ADMIN')) {
    die('Stop!!!');
}

$page_title = $BL->lang('cfgInsArt');
$set_active_op = 'config-master';

$array = [];
$error = '';

// Lấy thông tin submit
if ($nv_Request->isset_request('submit', 'post')) {
    $array['instantArticlesActive'] = $nv_Request->get_int('instantArticlesActive', 'post', 0) ? 1 : 0;
    $array['instantArticlesTemplate'] = nv_substr($nv_Request->get_title('instantArticlesTemplate', 'post', ''), 0, 100);
    $array['instantArticlesHttpauth'] = $nv_Request->get_int('instantArticlesHttpauth', 'post', 0) ? 1 : 0;
    $array['instantArticlesUsername'] = nv_substr($nv_Request->get_title('instantArticlesUsername', 'post', ''), 0, 100);
    $array['instantArticlesPassword'] = nv_substr($nv_Request->get_title('instantArticlesPassword', 'post', ''), 0, 100);
    $array['instantArticlesLivetime'] = $nv_Request->get_int('instantArticlesLivetime', 'post', 0);
    $array['instantArticlesGettime'] = $nv_Request->get_int('instantArticlesGettime', 'post', 0);

    if ($array['instantArticlesLivetime'] <= 0) {
        $array['instantArticlesLivetime'] = 60;
    }
    if ($array['instantArticlesGettime'] <= 0) {
        $array['instantArticlesGettime'] = 120;
    }

    if ($array['instantArticlesHttpauth'] and (empty($array['instantArticlesUsername']) or empty($array['instantArticlesPassword']))) {
        $error = $BL->lang('cfgInsArtErrAccountAuth');
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

$xtpl = new XTemplate('config-instant-articles.tpl', NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('DATA', $array);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op);

$xtpl->assign('INSTANTARTICLESACTIVE', $array['instantArticlesActive'] ? " checked=\"checked\"" : "");
$xtpl->assign('INSTANTARTICLESHTTPAUTH', $array['instantArticlesHttpauth'] ? " checked=\"checked\"" : "");

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// Công cụ lấy link RSS bài viết tức thời
$xtpl->assign('RSS_LINK_ALL', NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=instant-rss', true));

$array_cats = $BL->listCat(0);
foreach ($array_cats as $cat) {
    $cat['rss_link'] = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=instant-rss/' . $cat['alias'], true);
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
}

//print_r($array_cats);
//die();

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
