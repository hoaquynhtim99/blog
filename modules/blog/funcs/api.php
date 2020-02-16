<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if (!defined('NV_IS_MOD_BLOG')) {
    die('Stop!!!');
}

// Chặn robot vào đây
$nv_BotManager->setNoFollow()->setNoIndex()->printToHeaders();

// Cập nhật số bình luận khi đăng bình luận facebook
if ($nv_Request->isset_request('addCommentOnly', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    $fbcmtid = $nv_Request->get_title('fbcmtid', 'post', '');

    if ($tokend === NV_CHECK_SESSION and $id > 0) {
        $sql = "SELECT * FROM " . $BL->table_prefix . "_rows WHERE status=1 AND id=" . $id;
        $row = $db->query($sql)->fetch();
        if (empty($row)) {
            nv_htmlOutput('NO');
        }

        $session_fbcmtids = $nv_Request->get_string($module_data . '_fbcmtids', 'session', '');
        $session_fbcmtids = empty($session_fbcmtids) ? [] : json_decode($session_fbcmtids, true);
        if (!is_array($session_fbcmtids)) {
            $session_fbcmtids = [];
        }
        if (!in_array($fbcmtid, $session_fbcmtids)) {
            $sql = "UPDATE " . $BL->table_prefix . "_rows SET numcomments=numcomments+1 WHERE id=" . $id;
            $db->query($sql);

            $session_fbcmtids[] = $fbcmtid;
            $session_fbcmtids = json_encode($session_fbcmtids);
            $nv_Request->set_Session($module_data . '_fbcmtids', $session_fbcmtids);

            // Gửi email thông báo
            if (!empty($BL->setting['emailWhenComment'])) {
                $emails = empty($BL->setting['emailWhenCommentList']) ? [] : json_decode($BL->setting['emailWhenCommentList'], true);
                if (!empty($emails)) {
                    // Gửi email
                    $link = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'] . $global_config['rewrite_exturl'], true) . '#comment';
                    $message = sprintf($BL->lang('email_ncomment_b'), $row['title'], $link, $link);
                    nv_sendmail([$global_config['site_name'], $global_config['site_email']], $emails, $BL->lang('email_ncomment_s'), $message);
                }
            }

            $nv_Cache->delMod($module_name);
        }
    }

    nv_htmlOutput('OK');
}

// Cập nhật số bình luận khi xóa bình luận facebook
if ($nv_Request->isset_request('delCommentOnly', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    $fbcmtid = $nv_Request->get_title('fbcmtid', 'post', '');

    if ($tokend === NV_CHECK_SESSION and $id > 0) {
        $session_fbcmtids = $nv_Request->get_string($module_data . '_del_fbcmtids', 'session', '');
        $session_fbcmtids = empty($session_fbcmtids) ? [] : json_decode($session_fbcmtids, true);
        if (!is_array($session_fbcmtids)) {
            $session_fbcmtids = [];
        }
        if (!in_array($fbcmtid, $session_fbcmtids)) {
            try {
                $sql = "UPDATE " . $BL->table_prefix . "_rows SET numcomments=numcomments-1 WHERE id=" . $id;
                $db->query($sql);
            } catch (Exception $e) {
                trigger_error(print_r($e, true));
            }

            $session_fbcmtids[] = $fbcmtid;
            $session_fbcmtids = json_encode($session_fbcmtids);
            $nv_Request->set_Session($module_data . '_del_fbcmtids', $session_fbcmtids);

            $nv_Cache->delMod($module_name);
        }
    }

    nv_htmlOutput('OK');
}

nv_htmlOutput('Error Access!!!');
