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

$page_title = $nv_Lang->getModule('mainTitle');

$tpl = new \NukeViet\Template\Smarty();
$tpl->registerPlugin('modifier', 'date', 'nv_date');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);

$array_notice = [];

// Tags chua co mo ta hoac keywords
$sql = "SELECT COUNT(*) FROM " . $BL->table_prefix . "_tags WHERE keywords='' OR description=''";
$num = $db->query($sql)->fetchColumn();

if ($num > 0) {
    $array_notice[] = [
        'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=tags&amp;noComplete=1",
        'title' => sprintf($nv_Lang->getModule('mainTagsWarning'), $num),
    ];
}

// Bai viet het han, cho dang, nhap
$sql = "SELECT COUNT(*) AS number, status FROM " . $BL->table_prefix . "_rows GROUP BY status";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    if ($row['number'] > 0) {
        if ($row['status'] == 2) {
            $array_notice[] = [
                'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=blog-list&amp;status=2",
                'title' => sprintf($nv_Lang->getModule('mainPostExpriedWarning'), $row['number']),
            ];
        } elseif ($row['status'] == -2) {
            $array_notice[] = [
                'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=blog-list&amp;status=-2",
                'title' => sprintf($nv_Lang->getModule('mainPostDraftWarning'), $row['number']),
            ];
        } elseif ($row['status'] == -1) {
            $array_notice[] = [
                'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=blog-list&amp;status=-1",
                'title' => sprintf($nv_Lang->getModule('mainPostWaitWarning'), $row['number']),
            ];
        }
    }
}

// Cảnh báo Google Author chưa được đặt.
if (empty($BL->setting['sysGoogleAuthor'])) {
    $array_notice[] = [
        'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=config-structured-data",
        'title' => $nv_Lang->getModule('mainCfgGoogleAuthorWarning'),
    ];
}

// Cảnh báo Facebook App ID chưa được đặt.
if (empty($BL->setting['sysFbAppID'])) {
    $array_notice[] = [
        'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=config-structured-data",
        'title' => $nv_Lang->getModule('mainCfgFbAppIDWarning'),
    ];
}


$array_statistics = [];

// Thong ke so bai viet
$sql = "SELECT COUNT(*) FROM " . $BL->table_prefix . "_rows";
$num = $db->query($sql)->fetchColumn();

$array_statistics[] = [
    'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=blog-list",
    'title' => sprintf($nv_Lang->getModule('mainStatPostTotal'), $num),
];

// Thong ke so tags
$sql = "SELECT COUNT(*) FROM " . $BL->table_prefix . "_tags";
$num = $db->query($sql)->fetchColumn();

$array_statistics[] = [
    'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=tags",
    'title' => sprintf($nv_Lang->getModule('mainStatTagsTotal'), $num),
];

// Thong ke so email dang ky nhan tin
$sql = "SELECT COUNT(*) FROM " . $BL->table_prefix . "_newsletters";
$num = $db->query($sql)->fetchColumn();

$array_statistics[] = [
    'link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=newsletter-manager",
    'title' => sprintf($nv_Lang->getModule('mainStatNewsletters'), $num),
];

// Xuat thong tin module
$module_version = [];
include NV_ROOTDIR . '/modules/' . $module_file . '/version.php';

$module_version['date'] = intval(strtotime($module_version['date']));
$module_version['author'] = nv_htmlspecialchars(trim(preg_replace('/\<.*\>/', '', $module_version['author'])));

$tpl->assign('ARRAY_NOTICE', $array_notice);
$tpl->assign('ARRAY_STATISTICS', $array_statistics);
$tpl->assign('MODULE_INFO', $module_version);
$tpl->assign('AUTHOR_CONTACT', nv_EncodeEmail($BL->author_email));

$tpl->assign('TEMPLATE', $global_config['module_theme']);
$tpl->assign('MODULE_FILE', $module_file);

$tpl->assign('DONATE_EMAIL', 'phantandung92@gmail.com');
$tpl->assign('DONATE_ORDERID', NV_CURRENTTIME);
$tpl->assign('DONATE_AMOUNT', 200000);
$tpl->assign('DONATE_RETURN', NV_MY_DOMAIN . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . "=" . $module_name);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
