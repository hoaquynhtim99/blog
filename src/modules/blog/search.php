<?php

/**
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

if (!defined('NV_IS_MOD_SEARCH')) {
    die('Stop!!!');
}

$db_slave->sqlreset()
->select('COUNT(*)')
->from(NV_PREFIXLANG . '_' . $m_values['module_data'] . '_rows')
->where('(' . nv_like_logic('title', $dbkeywordhtml, $logic) . ' OR ' . nv_like_logic('keywords', $dbkeyword, $logic) . ' OR ' . nv_like_logic('hometext', $dbkeyword, $logic) . ' OR ' . nv_like_logic('bodytext', $dbkeyword, $logic) . ')	AND status=1');

$num_items = $db_slave->query($db_slave->sql())->fetchColumn();
if ($num_items) {
    $db_slave->select('title, alias, hometext')
    ->order('pubtime DESC')
    ->limit($limit)
    ->offset(($page - 1) * $limit);
    $result = $db_slave->query($db_slave->sql());
    while ($row = $result->fetch()) {
        $result_array[] = [
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'],
            'title' => BoldKeywordInStr($row['title'], $key, $logic),
            'content' => BoldKeywordInStr($row['hometext'], $key, $logic)
        ];
    }
}
