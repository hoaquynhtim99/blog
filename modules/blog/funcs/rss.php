<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_FILM' ) ) die( 'Stop!!!' );

$channel = array();
$items = array();

$channel['title'] = $global_config['site_name'] . ' RSS: ' . $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['atomlink'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
$channel['description'] = $global_config['site_description'];

if ( ! empty( $nv_film_listcat ) )
{
    $catalias = isset( $array_op[1] ) ? $array_op[1] : "";
    $catid = 0;
    
    if ( ! empty( $catalias ) )
    {
        foreach ( $nv_film_listcat as $c )
        {
            if ( $c['alias'] == $catalias )
            {
                $catid = $c['id'];
                break;
            }
        }
    }
    
    if ( $catid > 0 )
    {
        $channel['title'] = $global_config['site_name'] . ' RSS: ' . $module_info['custom_title'] . ' - ' . $nv_film_listcat[$catid]['title'];
        $channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;cat=" . $nv_film_listcat[$catid]['alias'];
        $channel['description'] = $nv_film_listcat[$catid]['description'];
        
        $sql = "SELECT `id`, `catid`, `addtime`, `title`, `introtext`, `description`, `images`
        FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid`=" . $catid . " 
        AND `status`=1 ORDER BY `addtime` DESC LIMIT 30";
    }
    else
    {
        $in = array_keys( $nv_film_listcat );
        $in = implode( ",", $in );
        $sql = "SELECT `id`, `catid`, `addtime`, `title`, `introtext`, `description`, `images` 
        FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid` IN (" . $in . ") 
        AND `status`=1 ORDER BY `addtime` DESC LIMIT 30";
    }
    if ( $module_info['rss'] )
    {
        if ( ( $result = $db->sql_query( $sql ) ) !== false )
        {
            while ( list( $id, $cid, $publtime, $title, $hometext, $description, $images ) = $db->sql_fetchrow( $result ) )
            {
				$hometext = empty( $hometext ) ? nv_clean60( strip_tags( $description ), 300 ) : $hometext;
                $rimages = ( ! empty( $images ) ) ? "<img src=\"" . NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . $images . "\" width=\"100\" align=\"left\" border=\"0\">" : "";
                $items[] = array(
                    'title' => $title,
					'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $nv_film_listcat[$cid]['alias'] . "/" . change_alias( $title . "-" . $id ),
					'guid' => $module_name . '_' . $id,
					'description' => $rimages . $hometext,
					'pubdate' => $publtime
                );
            }
        }
    }
}

nv_rss_generate( $channel, $items );
die();

?>