<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_FILM' ) )
{
    die( 'Stop!!!' );
}

$url = array();
$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_name . "_Sitemap.cache";
$pa = NV_CURRENTTIME - 7200;

if ( ( $cache = nv_get_cache( $cacheFile ) ) != false AND filemtime($cacheFile) >= $pa )
{
    $url = unserialize( $cache );
}
else
{
    $sql = "SELECT `id`, `catid`, `title`, `addtime` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1 ORDER BY `addtime` DESC LIMIT 1000";
    $result = $db->sql_query( $sql );

    while ( list( $id, $catid, $title, $addtime ) = $db->sql_fetchrow( $result ) )
    {
        $url[] = array( //
            'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $nv_film_listcat[$catid]['alias'] . "/" . change_alias( $title . "-" . $id ), //
            'publtime' => $addtime //
		);
    }
    
    $cache = serialize( $url );
    nv_set_cache( $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();

?>