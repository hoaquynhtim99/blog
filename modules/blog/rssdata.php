<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if (!defined('NV_IS_MOD_RSS'))
    die('Stop!!!');

$rssarray = array();
$sql = "SELECT id AS catid, parentid, title, alias FROM " . NV_PREFIXLANG . "_" . $mod_data . "_categories ORDER BY weight";
$list = $nv_Cache->db($sql, '', $mod_name);
foreach ($list as $value) {
    $value['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . $value['alias'];
    $rssarray[] = $value;
}
