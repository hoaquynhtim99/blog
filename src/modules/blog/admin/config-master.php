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

$page_title = $nv_Lang->getModule('cfgMaster');
$array = [];

// Thu muc uploads
$array_structure_image = [];
$array_structure_image[''] = NV_UPLOADS_DIR . '/' . $module_upload;
$array_structure_image['Y'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y');
$array_structure_image['Ym'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m');
$array_structure_image['Y_m'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y/m');
$array_structure_image['Ym_d'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m/d');
$array_structure_image['Y_m_d'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y/m/d');
$array_structure_image['username'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username';

$array_structure_image['username_Y'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username/' . date('Y');
$array_structure_image['username_Ym'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username/' . date('Y_m');
$array_structure_image['username_Y_m'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username/' . date('Y/m');
$array_structure_image['username_Ym_d'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username/' . date('Y_m/d');
$array_structure_image['username_Y_m_d'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username/' . date('Y/m/d');

// Giao diện của highlight js
$array_highlight_themes = nv_scandir(NV_ROOTDIR . '/themes/default/images/' . $module_file . '/frameworks/highlight/styles', "/^(.*?)\.css$/i");

// Fix du lieu
$numberResendNewsletterMax = 5;

// Lay thong tin submit
if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    $array['indexViewType'] = nv_substr($nv_Request->get_title('indexViewType', 'post', 'type_blog', 1), 0, 255);
    $array['catViewType'] = nv_substr($nv_Request->get_title('catViewType', 'post', 'type_blog', 1), 0, 255);
    $array['tagsViewType'] = nv_substr($nv_Request->get_title('tagsViewType', 'post', 'type_blog', 1), 0, 255);
    $array['numPostPerPage'] = $nv_Request->get_int('numPostPerPage', 'post', 0);
    $array['initPostExp'] = $nv_Request->get_int('initPostExp', 'post', 0);
    $array['initPostType'] = $nv_Request->get_int('initPostType', 'post', 0);
    $array['initMediaType'] = $nv_Request->get_int('initMediaType', 'post', 0);
    $array['initMediaHeight'] = $nv_Request->get_int('initMediaHeight', 'post', 250);
    $array['initMediaWidth'] = $nv_Request->get_int('initMediaWidth', 'post', 960);
    $array['initMediaResponsive'] = ($nv_Request->get_int('initMediaResponsive', 'post', 0) ? 1 : 0);
    $array['initNewsletters'] = $nv_Request->get_int('initNewsletters', 'post', 0);
    $array['initAutoKeywords'] = $nv_Request->get_int('initAutoKeywords', 'post', 0);
    $array['folderStructure'] = nv_substr($nv_Request->get_title('folderStructure', 'post', '', 0), 0, 255);
    $array['numberResendNewsletter'] = $nv_Request->get_int('numberResendNewsletter', 'post', 0);
    $array['strCutHomeText'] = $nv_Request->get_int('strCutHomeText', 'post', 0);
    $array['sysHighlightTheme'] = nv_substr($nv_Request->get_title('sysHighlightTheme', 'post', ''), 0, 255);
    $array['numSearchResult'] = $nv_Request->get_int('numSearchResult', 'post', 20);
    $array['showAdsInDetailPage'] = intval($nv_Request->get_bool('showAdsInDetailPage', 'post', false));
    $array['postingMode'] = $nv_Request->get_title('postingMode', 'post', '');

    // Lấy cấu hình lớp của icon loại bài viết
    foreach ($BL->blogposttype as $type) {
        $array['iconClass' . $type] = nv_substr($nv_Request->get_title('iconClass' . $type, 'post', 'icon-pencil', 1), 0, 255);
    }

    // Kiem tra xac nhan
    if (!in_array($array['indexViewType'], $BL->indexViewType)) {
        $array['indexViewType'] = $BL->indexViewType[0];
    }
    if (!in_array($array['catViewType'], $BL->catViewType)) {
        $array['catViewType'] = $BL->catViewType[0];
    }
    if (!in_array($array['tagsViewType'], $BL->tagsViewType)) {
        $array['tagsViewType'] = $BL->tagsViewType[0];
    }
    if (!in_array($array['initPostExp'], $BL->blogExpMode)) {
        $array['initPostExp'] = $BL->blogExpMode[0];
    }
    if (!in_array($array['initPostType'], $BL->blogposttype)) {
        $array['initPostType'] = $BL->blogposttype[0];
    }
    if (!in_array($array['initMediaType'], $BL->blogMediaType)) {
        $array['initMediaType'] = $BL->blogMediaType[0];
    }
    if ($array['initMediaHeight'] <= 0 or $array['initMediaHeight'] > 9000) {
        $array['initMediaHeight'] = 250;
    }
    if ($array['initMediaWidth'] <= 0 or $array['initMediaWidth'] > 9000) {
        $array['initMediaWidth'] = 960;
    }
    if (!isset($array_structure_image[$array['folderStructure']])) {
        $array['folderStructure'] = "Ym";
    }
    if (!in_array($array['postingMode'], $BL->postingMode)) {
        $array['postingMode'] = $BL->postingMode[0];
    }

    $array['initNewsletters'] = $array['initNewsletters'] ? 1 : 0;
    $array['initAutoKeywords'] = $array['initAutoKeywords'] ? 1 : 0;

    if ($array['numberResendNewsletter'] > $numberResendNewsletterMax or $array['numberResendNewsletter'] < 0) {
        $array['numberResendNewsletter'] = 0;
    }
    if ($array['numPostPerPage'] > 100 or $array['numPostPerPage'] < 1) {
        $array['numPostPerPage'] = 10;
    }
    if ($array['strCutHomeText'] < 0) {
        $array['strCutHomeText'] = 0;
    }
    if ($array['numSearchResult'] < 0 or $array['numSearchResult'] > 200) {
        $array['numSearchResult'] = 20;
    }

    // Kiểm tra giao diện highlight tồn tại
    if (!in_array($array['sysHighlightTheme'] . '.css', $array_highlight_themes)) {
        $array['sysHighlightTheme'] = 'default';
    }

    foreach ($array as $config_name => $config_value) {
        $sql = "REPLACE INTO " . $BL->table_prefix . "_config VALUES (" . $db->quote($config_name) . "," . $db->quote($config_value) . ")";
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
}

$template = get_tpl_dir([$global_config['module_theme'], $global_config['admin_theme']], 'admin_default', '/modules/' . $module_file . '/config-master.tpl');
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
$tpl->registerPlugin('modifier', 'substr', 'substr');
$tpl->registerPlugin('modifier', 'ucfirst', 'ucfirst');
$tpl->registerPlugin('modifier', 'str_replace', 'str_replace');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$tpl->assign('INDEXVIEWTYPE', $BL->indexViewType);
$tpl->assign('TAGSVIEWTYPE', $BL->tagsViewType);
$tpl->assign('CATVIEWTYPE', $BL->catViewType);
$tpl->assign('DATA', $BL->setting);
$tpl->assign('HIGHLIGHT_THEMES', $array_highlight_themes);
$tpl->assign('BLOGPOSTTYPE', $BL->blogposttype);
$tpl->assign('BLOGEXPMODE', $BL->blogExpMode);
$tpl->assign('BLOGPOSTTYPE', $BL->blogposttype);
$tpl->assign('BLOGMEDIATYPE', $BL->blogMediaType);
$tpl->assign('POSTINGMODE', $BL->postingMode);
$tpl->assign('STRUCTURE_IMAGE', $array_structure_image);
$tpl->assign('RESENDNEWSLETTERMAX', $numberResendNewsletterMax);

$contents = $tpl->fetch('config-master.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
