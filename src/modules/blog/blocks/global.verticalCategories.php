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

use NukeViet\Module\blog\BlogInit;

if (!nv_function_exists('nv_blog_verticalCategories')) {
    /**
     * nv_blog_verticalCategories()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_blog_verticalCategories($block_config)
    {
        global $module_info, $global_config, $site_mods, $client_info, $global_array_cat, $module_name;

        $module = $block_config['module'];
        $module_file = $site_mods[$module]['module_file'];
        $module_data = $site_mods[$module]['module_data'];

        if (file_exists(NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/block.verticalCategories.tpl")) {
            $block_theme = $module_info['template'];
        } elseif (file_exists(NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $module_file . "/block.verticalCategories.tpl")) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = "default";
        }

        // Lay danh sach chuyen muc
        if ($module_name == $module) {
            $list_cats = $global_array_cat;
        } else {
            $BL = new BlogInit($module_data, $module, $module_file);

            $list_cats = $BL->listCat(0, 0);

            if (!defined('NV_IS_BLOG_CSS')) {
                global $my_head;

                $css_file = 'themes/' . $block_theme . '/css/' . $module_file . '.css';

                if (file_exists(NV_ROOTDIR . '/' . $css_file)) {
                    define('NV_IS_BLOG_CSS', true);

                    $my_head .= "<link rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . $css_file . "\"/>\n";
                }
            }
        }

        $xtpl = new XTemplate("block.verticalCategories.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file);

        foreach ($list_cats as $cat) {
            if ($cat['parentid'] == 0) {
                $cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];

                $xtpl->assign("ROW", $cat);
                $xtpl->assign("SUB", nv_blog_verticalCategoriesSubs($list_cats, $cat, $block_theme, $module_file, $module));

                $xtpl->parse('main.loop');
            }
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }

    /**
     * nv_blog_verticalCategoriesSubs()
     *
     * @param mixed $list_cats
     * @param mixed $cat
     * @param mixed $block_theme
     * @param mixed $module_file
     * @param mixed $module
     * @return
     */
    function nv_blog_verticalCategoriesSubs($list_cats, $cat, $block_theme, $module_file, $module)
    {
        if (empty($cat['subcats'])) {
            return "";
        }

        $xtpl = new XTemplate("block.verticalCategories.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file);

        foreach ($cat['subcats'] as $catid) {
            $list_cats[$catid]['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$catid]['alias'];

            $xtpl->assign("ROW", $list_cats[$catid]);
            $xtpl->assign("SUB", nv_blog_verticalCategoriesSubs($list_cats, $list_cats[$catid], $block_theme, $module_file, $module));

            $xtpl->parse('sub.loop');
        }

        $xtpl->parse('sub');
        return $xtpl->text('sub');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_blog_verticalCategories($block_config);
}
