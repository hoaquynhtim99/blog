<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $mod_data . "_categories ORDER BY weight_all ASC";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $array_item[$row['id']] = [
        'parentid' => $row['parentid'],
        'groups_view' => '6',
        'key' => $row['id'],
        'title' => $row['title'],
        'alias' => $row['alias']
    ];
}
