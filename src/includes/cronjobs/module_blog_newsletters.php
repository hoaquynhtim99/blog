<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!defined('NV_IS_CRON')) {
    die('Stop!!!');
}

/**
 * @param string $table_data
 * @param integer $num_onesend
 * @return boolean
 */
function cron_blog_newsletters($table_data, $num_onesend)
{
    global $db, $global_config, $db_config, $nv_Lang;

    $num_onesend = empty($num_onesend) ? 5 : intval($num_onesend);

    if (!empty($table_data)) {
        $table_data = explode("_", $table_data);
        $table_data = array_map("trim", $table_data);

        $lang = $table_data[0];
        unset($table_data[0]);
        $module_data = implode("_", $table_data);

        // Lay module file, module name
        $sql = "SELECT title, module_file FROM " . NV_MODULES_TABLE . " WHERE module_data=" . $db->quote($module_data);
        $result = $db->query($sql);
        list($module_name, $module_file) = $result->fetch(3);

        $table_data = $db_config['prefix'] . "_" . $lang . "_" . $module_data;

        // Lay cau hinh gui mail
        $sql = "SELECT config_value FROM " . $table_data . "_config WHERE config_name=" . $db->quote('numberResendNewsletter');
        $result = $db->query($sql);
        $numberResendNewsletter = $result->fetchColumn();

        // Lay tin can gui (uu tien cac tin round 1 hon)
        $sql = "SELECT a.*, b.title, b.alias, b.hometext FROM " . $table_data . "_send a INNER JOIN " . $table_data . "_rows b ON a.pid=b.id WHERE a.status!=2 AND b.status=1 ORDER BY a.status DESC, a.id ASC LIMIT 0,1";
        $result = $db->query($sql);

        if ($result->rowCount()) {
            // Du lieu gui
            $data_send = $result->fetch();

            $data_send['lastid'] = intval($data_send['lastid']);
            $data_send['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . $lang . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $data_send['alias'];
            $data_send['resenddata'] = explode(",", $data_send['resenddata']);
            $data_send['errordata'] = explode(",", $data_send['errordata']);

            // Danh dau la dang gui
            if (empty($data_send['status'])) {
                $db->query("UPDATE " . $table_data . "_send SET status=1, starttime=" . NV_CURRENTTIME . ", round=1 WHERE id=" . $data_send['id']);
            }

            // Goi ngon ngu
            $nv_Lang->loadModule($module_file, false, true);

            // Lay nguoi gui lan gui 1
            if ($data_send['round'] <= 1) {
                $sql = "SELECT id, email FROM " . $table_data . "_newsletters WHERE status=1 AND id>" . $data_send['lastid'] . " ORDER BY id ASC LIMIT 0," . $num_onesend;
            }
            // Nguoi gui o cac lan gui tiep theo
            else {
                $sql = "SELECT id, email FROM " . $table_data . "_newsletters WHERE status=1 AND id>" . $data_send['lastid'] . " AND id IN(" . implode(",", $data_send['resenddata']) . ") ORDER BY id ASC LIMIT 0," . $num_onesend;
            }
            $result = $db->query($sql);
            $num = $result->rowCount();

            while ($row = $result->fetch()) {
                if (nv_sendmail([$global_config['site_name'], $global_config['site_email']], $row['email'], nv_unhtmlspecialchars($data_send['title']), $data_send['hometext'] . "<br /><br />" . $nv_Lang->getModule('newsletterMessage') . ": <a href=\"" . $data_send['link'] . "\">" . $data_send['link'] . "</a>")) {
                    // Cap nhat lan gui cuoi va so email da gui
                    $db->query("UPDATE " . $table_data . "_newsletters SET numemail=numemail+1, lastsendtime=" . NV_CURRENTTIME . " WHERE id=" . $row['id']);
                } else {
                    // Danh dau cac email bi loi trong lan gui nay
                    $data_send['errordata'][$row['id']] = $row['id'];
                }

                $data_send['lastid'] = $row['id'];
            }

            // Cap nhat lai thong tin
            $sql = [];

            $sql[] = "lastid=" . $data_send['lastid'];

            if ($num < $num_onesend) {
                // Tang so lan gui len 1
                $data_send['round'] = $data_send['round'] + 1;

                // Danh dau da gui xong neu qua so lan gui hoac khong co email loi
                if ($data_send['round'] > $numberResendNewsletter or empty($data_send['errordata'])) {
                    $sql[] = "status=2";
                    $sql[] = "endtime=" . NV_CURRENTTIME;
                } else {
                    // Gui chua xong thi moi tang va ghi v�o CSDL
                    $sql[] = "round=" . $data_send['round'];

                    // Du lieu gui lan tiep theo la du lieu loi cua lan nay
                    $sql[] = "resenddata=" . $db->quote(implode(",", $data_send['errordata']));
                    $sql[] = "errordata=''";
                }
            } else {
                // Ghi lai du lieu loi
                $sql[] = "errordata=" . $db->quote($data_send['errordata'] ? implode(",", $data_send['errordata']) : '');
            }

            $db->query("UPDATE " . $table_data . "_send SET " . implode(", ", $sql) . " WHERE id=" . $data_send['id']);
        }

        // Xóa ngôn ngữ tạm
        $nv_Lang->changeLang();
    }

    return true;
}
