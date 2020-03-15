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

$page_title = $nv_Lang->getModule('cfgComment');
$set_active_op = 'config-master';
$array_commentFacebookColorscheme = ['light' => 'Light', 'dark' => 'Dark'];

$array = [];

// Lay thong tin submit
if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    $array['commentType'] = nv_substr($nv_Request->get_title('commentType', 'post', 'random', 1), 0, 255);
    $array['commentPerPage'] = $nv_Request->get_absint('commentPerPage', 'post', 8);
    $array['commentDisqusShortname'] = nv_substr($nv_Request->get_title('commentDisqusShortname', 'post', '', 1), 0, 255);
    $array['commentFacebookColorscheme'] = nv_substr($nv_Request->get_title('commentFacebookColorscheme', 'post', 'light', 1), 0, 255);
    $array['emailWhenComment'] = intval($nv_Request->get_bool('emailWhenComment', 'post', false));

    $emailWhenCommentList = $nv_Request->get_textarea('emailWhenCommentList', '', NV_ALLOWED_HTML_TAGS);
    $array['emailWhenCommentList'] = [];
    $emailWhenCommentList = array_filter(array_map('trim', explode('|||', nv_nl2br($emailWhenCommentList, '|||'))));
    foreach ($emailWhenCommentList as $email) {
        $email = nv_check_valid_email($email, true);
        if ($email[0] == '') {
            $array['emailWhenCommentList'][] = $email[1];
        }
    }
    $array['emailWhenCommentList'] = json_encode(array_unique($array['emailWhenCommentList']));

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

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$BL->setting['emailWhenCommentList'] = empty($BL->setting['emailWhenCommentList']) ? '' : implode("\n", json_decode($BL->setting['emailWhenCommentList'], true));

$tpl->assign('DATA', $BL->setting);
$tpl->assign('COMMENTTYPE', $BL->commentType);
$tpl->assign('COLORSCHEME', $array_commentFacebookColorscheme);

$contents = $tpl->fetch('config-comment.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
