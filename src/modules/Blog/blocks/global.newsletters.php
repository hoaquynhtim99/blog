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

if (!nv_function_exists('nv_blog_newsletters')) {
    /**
     * nv_blog_newsletters()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_blog_newsletters($block_config)
    {
        global $module_info, $global_config, $site_mods, $client_info, $module_name, $my_head;

        $module = $block_config['module'];
        $module_file = $site_mods[$module]['module_file'];

        if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/block.newsletters.tpl')) {
            $block_theme = $module_info['template'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module_file . '/block.newsletters.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        // Goi css
        if ($module_name != $module and !defined('NV_IS_BLOG_CSS')) {
            $css_file = 'themes/' . $block_theme . '/css/' . $module_file . '.css';
            if (file_exists(NV_ROOTDIR . '/' . $css_file)) {
                $my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . $css_file . '"/>';
            } else {
                $my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . 'themes/default/css/' . $module_file . '.css"/>';
            }
            define('NV_IS_BLOG_CSS', true);
        }

        // Gọi js
        if ($module_name != $module and !defined('NV_IS_BLOG_JS')) {
            $js_file = 'themes/' . $block_theme . '/js/' . $module_file . '.js';
            if (file_exists(NV_ROOTDIR . '/' . $js_file)) {
                $my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . $js_file . '"></script>';
            } else {
                $my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . 'themes/default/js/' . $module_file . '.js"></script>';
            }
            define('NV_IS_BLOG_JS', true);
        }

        // Goi ngon ngu module
        include (NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_DATA . '.php');

        $xtpl = new XTemplate('block.newsletters.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module_file);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
        $xtpl->assign('CHECKSESS', md5($global_config['sitekey'] . $client_info['session_id']));
        $xtpl->assign('MODULE_NAME', $module);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_blog_newsletters($block_config);
}
