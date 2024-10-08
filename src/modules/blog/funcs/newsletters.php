<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_IS_MOD_BLOG')) {
    die('Stop!!!');
}

$nv_BotManager->setPrivate();

// Dang ky nhan ban tin
if ($nv_Request->isset_request('newsletters', 'post')) {
    $array['email'] = nv_substr($nv_Request->get_title('newsletters', 'post', '', 1), 0, 255);
    $array['checksess'] = nv_substr($nv_Request->get_title('checksess', 'post', '', 1), 0, 255);

    if (empty($array['email']) or empty($array['checksess']) or $array['checksess'] != md5($global_config['sitekey'] . $client_info['session_id'])) {
        die('Error Access!!!');
    }

    // Kiem tra email hop le
    $checkEmail = nv_check_valid_email($array['email']);
    if ($checkEmail != '') {
        die($checkEmail);
    }

    // Kiem tra email da dang ky
    $sql = "SELECT * FROM " . $BL->table_prefix . "_newsletters WHERE email=" . $db->quote($array['email']);
    $result = $db->query($sql);

    if ($result->rowCount()) {
        $row = $result->fetch();

        if ($row['status'] == 0) {
            die(sprintf($nv_Lang->getModule('newsletterIsBan'), $array['email']));
        } elseif ($row['status'] == 1) {
            die(sprintf($nv_Lang->getModule('newsletterIsActive'), $array['email']));
        } else {
            if (!$db->query("DELETE FROM " . $BL->table_prefix . "_newsletters WHERE email=" . $db->quote($array['email']))) {
                die('Unknow Error!!!');
            }
        }
    }

    // Khoa unique
    $tokenkey = md5($array['checksess'] . $array['email']);

    $linkConfirm = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;confirm=" . $tokenkey;
    $linkCancel = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;cancel=" . $tokenkey;

    $mailContent = sprintf($nv_Lang->getModule('newsletterMailConfirm'), $linkConfirm, $linkConfirm, $linkCancel, $linkCancel, $global_config['site_name']);

    if (nv_sendmail(array($global_config['site_name'], $global_config['site_email']), $array['email'], $nv_Lang->getModule('newsletterMailSubject'), $mailContent)) {
        if ($db->query("INSERT INTO " . $BL->table_prefix . "_newsletters (id, email, regip, regtime, tokenkey, status) VALUES(NULL, " . $db->quote($array['email']) . ", " . $db->quote($client_info['ip']) . ", " . NV_CURRENTTIME . ", " . $db->quote($tokenkey) . ", -1)")) {
            die(sprintf($nv_Lang->getModule('newsletterMailOk'), $array['email']));
        }

        nv_htmlOutput("Unknow Error!!!");
    } else {
        die($nv_Lang->getModule('newsletterMailError'));
    }
}

$page_title = $mod_title = $nv_Lang->getModule('newsletter');

// Khoi tao
$array = [
    'status' => 0, // 0: ok, 1: error
    'message' => ''
];

// Xac thuc hoac huy dang ky nhan tin
if ($nv_Request->isset_request("confirm", "get") or $nv_Request->isset_request("cancel", "get")) {
    $confirm = nv_substr($nv_Request->get_title('confirm', 'get', '', 1), 0, 255);
    $cancel = nv_substr($nv_Request->get_title('cancel', 'get', '', 1), 0, 255);
    $tokenkey = $confirm ? $confirm : $cancel;

    if (empty($tokenkey) or !preg_match("/^[a-z0-9]{32}$/", $tokenkey)) {
        $array['status'] = 1;
        $array['message'] = $nv_Lang->getModule('newsletterConfirmErrorVar');
    } else {
        $sql = "SELECT * FROM " . $BL->table_prefix . "_newsletters WHERE tokenkey=" . $db->quote($tokenkey);
        $result = $db->query($sql);

        if ($result->rowCount()) {
            $row = $result->fetch();

            // Huy dang ky nhan tin
            if (!empty($cancel)) {
                $db->query("DELETE FROM " . $BL->table_prefix . "_newsletters WHERE id=" . $row['id']);

                $array['status'] = 0;
                $array['message'] = sprintf($nv_Lang->getModule('newsletterCancelComplete'), $row['email']);
            }
            // Xac nhan dang ky
            else {
                // Dang bi cam
                if ($row['status'] == 0) {
                    $array['status'] = 1;
                    $array['message'] = sprintf($nv_Lang->getModule('newsletterIsBan'), $row['email']);
                } else {
                    // Xoa tokenkey
                    $db->query("UPDATE " . $BL->table_prefix . "_newsletters SET tokenkey='' WHERE id=" . $row['id']);

                    // Kich hoat
                    if ($row['status'] == -1) {
                        $db->query("UPDATE " . $BL->table_prefix . "_newsletters SET confirmtime=" . NV_CURRENTTIME . ", status=1 WHERE id=" . $row['id']);
                    }

                    $array['status'] = 0;
                    $array['message'] = sprintf($nv_Lang->getModule('newsletterConfirmComplete'), $row['email']);
                }
            }
        } else {
            $array['status'] = 1;
            $array['message'] = $nv_Lang->getModule('newsletterConfirmErrorVar');
        }
    }
} else {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true);
}

$contents = nv_newsletters_theme($array);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
