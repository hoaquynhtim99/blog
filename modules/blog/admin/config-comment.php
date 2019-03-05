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

$page_title = $BL->lang('cfgComment');
$set_active_op = 'config-master';
$array_commentFacebookColorscheme = array('light' => 'Light', 'dark' => 'Dark');

$array = array();

// Lay thong tin submit
if ($nv_Request->isset_request('submit', 'post')) {
    $array['commentType'] = nv_substr($nv_Request->get_title('commentType', 'post', 'random', 1), 0, 255);
    $array['commentPerPage'] = $nv_Request->get_int('commentPerPage', 'post', 8);
    $array['commentDisqusShortname'] = nv_substr($nv_Request->get_title('commentDisqusShortname', 'post', '', 1), 0, 255);
    $array['commentFacebookColorscheme'] = nv_substr($nv_Request->get_title('commentFacebookColorscheme', 'post', 'light', 1), 0, 255);

    // Kiem tra xac nhan
    if (!in_array($array['commentType'], $BL->commentType)) {
        $array['commentType'] = $BL->commentType[0];
    }
    if ($array['commentPerPage'] > 50 or $array['commentPerPage'] < 1) {
        $array['commentPerPage'] = 8;
    }
    if (!isset($array_commentFacebookColorscheme[$array['commentFacebookColorscheme']])) {
        $array['commentFacebookColorscheme'] = 'light';
    }

    foreach ($array as $config_name => $config_value) {
        $sql = "REPLACE INTO " . $BL->table_prefix . "_config VALUES (" . $db->quote($config_name) . "," . $db->quote($config_value) . ")";
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);

    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
}

$xtpl = new XTemplate("config-comment.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('DATA', $BL->setting);

// Xuat cac kieu hien thi
foreach ($BL->commentType as $type) {
    $xtpl->assign('COMMENTTYPE', array(
        "key" => $type,
        "title" => $BL->lang('cfgCommentType_' . $type),
        "selected" => $type == $BL->setting['commentType'] ? " selected=\"selected\"" : "")
    );
    $xtpl->parse('main.commentType');
}

// Xu?t giao di?n b?nh lu?n c?a facebook
foreach ($array_commentFacebookColorscheme as $commentFacebookColorscheme => $commentFacebookColorschemeVal) {
    $xtpl->assign('COMMENTFACEBOOKCOLORSCHEME', array(
        "key" => $commentFacebookColorscheme,
        "title" => $commentFacebookColorschemeVal,
        "selected" => $commentFacebookColorscheme == $BL->setting['commentFacebookColorscheme'] ? " selected=\"selected\"" : "")
    );
    $xtpl->parse('main.commentFacebookColorscheme');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
