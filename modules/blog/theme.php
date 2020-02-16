<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if (!defined('NV_IS_MOD_BLOG'))
    die('Stop!!!');

// Thêm vào meta tag
if (!empty($BL->setting['sysFbAppID'])) {
    $meta_property['fb:app_id'] = $BL->setting['sysFbAppID'];
}

// Thêm id admin vào meta tag
if (!empty($BL->setting['sysFbAdminID'])) {
    $meta_property['fb:admins'] = $BL->setting['sysFbAdminID'];
}

// Ngôn ngữ SDK facebook
$meta_property['og:locale'] = $BL->setting['sysLocale'];

/**
 * nv_blog_alias_page()
 *
 * @param mixed $title
 * @param mixed $base_url
 * @param mixed $num_items
 * @param mixed $per_page
 * @param mixed $on_page
 * @param bool $add_prevnext_text
 * @param bool $full_theme
 * @return
 */
function nv_blog_alias_page($title, $base_url, $num_items, $per_page, $on_page, $add_prevnext_text = true, $full_theme = true)
{
    global $lang_global;

    $total_pages = ceil($num_items / $per_page);

    if ($total_pages < 2) {
        return '';
    }

    $title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'];
    $page_string = ($on_page == 1) ? '<li class="active"><a href="#">1</a></li>' : '<li><a rel="prev" title="' . $title . ' 1" href="' . $base_url . '">1</a></li>';

    if ($total_pages > 7) {
        if ($on_page < 4) {
            $init_page_max = ($total_pages > 2) ? 2 : $total_pages;
            for ($i = 2; $i <= $init_page_max; ++$i) {
                if ($i == $on_page) {
                    $page_string .= '<li class="active"><a href="#">' . $i . '</a></li>';
                } else {
                    $rel = ($i > $on_page) ? 'next' : 'prev';
                    $page_string .= '<li><a rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
                }
            }
        }

        if ($on_page > 1 and $on_page < $total_pages) {
            if ($on_page > 3) {
                $page_string .= '<li class="disabled"><span>...</span></li>';
            }

            $init_page_min = ($on_page > 3) ? $on_page : 4;
            $init_page_max = ($on_page < $total_pages - 3) ? $on_page : $total_pages - 3;

            for ($i = $init_page_min - 1; $i < $init_page_max + 2; ++$i) {
                if ($i == $on_page) {
                    $page_string .= '<li class="active"><a href="#">' . $i . '</a></li>';
                } else {
                    $rel = ($i > $on_page) ? 'next' : 'prev';
                    $page_string .= '<li><a rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
                }
            }

            if ($on_page < $total_pages - 3) {
                $page_string .= '<li class="disabled"><span>...</span></li>';
            }
        } else {
            $page_string .= '<li class="disabled"><span>...</span></li>';
        }

        $init_page_min = ($total_pages - $on_page > 3) ? $total_pages : $total_pages - 1;
        for ($i = $init_page_min; $i <= $total_pages; ++$i) {
            if ($i == $on_page) {
                $page_string .= '<li class="active"><a href="#">' . $i . '</a></li>';
            } else {
                $rel = ($i > $on_page) ? 'next' : 'prev';
                $page_string .= '<li><a rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
            }
        }
    } else {
        for ($i = 2; $i < $total_pages + 1; ++$i) {
            if ($i == $on_page) {
                $page_string .= '<li class="active"><a href="#">' . $i . '</a><li>';
            } else {
                $rel = ($i > $on_page) ? 'next' : 'prev';
                $page_string .= '<li><a rel="' . $rel . '" title="' . $title . ' ' . $i . '" href="' . $base_url . '/page-' . $i . '">' . $i . '</a></li>';
            }
        }
    }

    if ($add_prevnext_text) {
        if ($on_page > 1) {
            $href = ($on_page > 2) ? $base_url . '/page-' . ($on_page - 1) : $base_url;
            $page_string = '<li><a rel="prev" title="' . $title . ' ' . ($on_page - 1) . '" href="' . $href . '">&laquo;</a></li>' . $page_string;
        } else {
            $page_string = '<li class="disabled"><a href="#">&laquo;</a></li>' . $page_string;
        }

        if ($on_page < $total_pages) {
            $page_string .= '<li><a rel="next" title="' . $title . ' ' . ($on_page + 1) . '" href="' . $base_url . '/page-' . ($on_page + 1) . '">&raquo;</a></li>';
        } else {
            $page_string .= '<li class="disabled"><a href="#">&raquo;</a></li>';
        }
    }

    if ($full_theme !== true) {
        return $page_string;
    }

    return '<ul class="pagination">' . $page_string . '</ul>';
}

/**
 * nv_main_theme()
 *
 * @param mixed $array
 * @param mixed $generate_page
 * @param mixed $cfg
 * @param mixed $page
 * @param mixed $total_pages
 * @param mixed $BL
 * @return
 */
function nv_main_theme($array, $generate_page, $cfg, $page, $total_pages, $BL)
{
    global $lang_global, $lang_module, $module_file, $module_info, $my_head;

    // Nếu không có bài viết thì chỉ cần thông báo
    if (empty($array)) {
        return nv_message_theme($lang_module['noPost'], 3);
    }

    if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/images/' . $module_file . '/jwplayer/jwplayer.js')) {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/jwplayer/jwplayer.js\"></script>" . NV_EOL;
    } else {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/default/images/" . $module_file . "/jwplayer/jwplayer.js\"></script>" . NV_EOL;
    }
    $my_head .= "<script type=\"text/javascript\">jwplayer.key=\"KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==\";</script>" . NV_EOL;

    if ($BL->setting['indexViewType'] == 'type_blog') {
        // Kieu danh sach blog
        $xtpl = new XTemplate("list_blog.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    } else {
        // Kieu danh sach tin tuc
        $xtpl = new XTemplate("list_news.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    }

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $xtpl->assign('PAGE_TOTAL', $total_pages);
    $xtpl->assign('PAGE_CURRENT', $page);

    // Có gọi lighlight js không
    $call_highlight = false;

    foreach ($array as $row) {
        $row['pubtime'] = str_replace(array(' AM ', ' PM '), array(' SA ', ' CH '), nv_date('g:i A d/m/Y', $row['pubtime']));
        $row['numcomments'] = number_format($row['numcomments'], 0, ',', '.');
        $row['linkComment'] = nv_url_rewrite($row['link'], true) . '#comment';
        $row['icon'] = empty($BL->setting['iconClass' . $row['posttype']]) ? 'icon-pencil' : $BL->setting['iconClass' . $row['posttype']];

        // Cat phan gioi thieu ngan gon
        if ($BL->setting['strCutHomeText']) {
            $row['hometext'] = nv_clean60($row['hometext'], $BL->setting['strCutHomeText']);
        }

        // Hinh anh mac dinh neu khong co anh mo ta
        if (empty($row['images'])) {
            if ($BL->setting['indexViewType'] == 'type_blog') {
                $row['images'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/comingsoon-large.jpg';
            } else {
                $row['images'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/comingsoon-medium.jpg';
            }
        }

        $xtpl->assign('ROW', $row);

        // Chi xuat media neu nhu kieu hien thi la danh sach dang blog
        if (!empty($row['mediavalue']) and $BL->setting['indexViewType'] == 'type_blog' and !empty($row['mediashowlist'])) {
            if (in_array($row['mediatype'], array(0, 1))) {
                // Kieu hinh anh
                $xtpl->parse('main.loop.media.image');
            } elseif ($row['mediatype'] == 2) {
                // Kieu am thanh
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.audio.height');
                } else {
                    $xtpl->assign('ASPECTRATIO', $BL->GetAspectRatio($row['mediawidth'], $row['mediaheight']));
                    $xtpl->parse('main.loop.media.audio.aspectratio');
                }
                $xtpl->parse('main.loop.media.audio');
            } elseif ($row['mediatype'] == 3) {
                // Kieu video
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.video.height');
                } else {
                    $xtpl->assign('ASPECTRATIO', $BL->GetAspectRatio($row['mediawidth'], $row['mediaheight']));
                    $xtpl->parse('main.loop.media.video.aspectratio');
                }
                $xtpl->parse('main.loop.media.video');
            } elseif ($row['mediatype'] == 4) {
                // Kieu iframe
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.iframe.height');
                } else {
                    $xtpl->parse('main.loop.media.iframe.aspectratio');
                }
                $xtpl->parse('main.loop.media.iframe');
            }

            $xtpl->parse('main.loop.media');
        }

        // Xuất html, text
        if (!empty($row['fullpage'])) {
            $call_highlight = true;

            $xtpl->parse('main.loop.bodyhtml');
        } else {
            $xtpl->parse('main.loop.hometext');
        }

        $xtpl->parse('main.loop');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    // Gọi framewokrs highlight nếu có full page
    if ($call_highlight == true) {
        $BL->callFrameWorks('highlight');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_viewcat_theme()
 *
 * @param mixed $array
 * @param mixed $generate_page
 * @param mixed $cfg
 * @param mixed $page
 * @param mixed $total_pages
 * @param mixed $BL
 * @return
 */
function nv_viewcat_theme($array, $generate_page, $cfg, $page, $total_pages, $BL)
{
    global $lang_global, $lang_module, $module_file, $module_info, $my_head;

    // Nếu không có bài viết thuộc danh mục thì chỉ cần thông báo
    if (empty($array)) {
        return nv_message_theme($lang_module['catNoPost'], 3);
    }

    if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/images/' . $module_file . '/jwplayer/jwplayer.js')) {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/jwplayer/jwplayer.js\"></script>" . NV_EOL;
    } else {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/default/images/" . $module_file . "/jwplayer/jwplayer.js\"></script>" . NV_EOL;
    }
    $my_head .= "<script type=\"text/javascript\">jwplayer.key=\"KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==\";</script>" . NV_EOL;

    if ($BL->setting['catViewType'] == 'type_blog') {
        // Kieu danh sach blog
        $xtpl = new XTemplate("list_blog.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    } else {
        // Kieu danh sach tin tuc
        $xtpl = new XTemplate("list_news.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    }

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $xtpl->assign('PAGE_TOTAL', $total_pages);
    $xtpl->assign('PAGE_CURRENT', $page);

    // Có gọi lighlight js không
    $call_highlight = false;

    foreach ($array as $row) {
        $row['pubtime'] = str_replace(array(' AM ', ' PM '), array(' SA ', ' CH '), nv_date('g:i A d/m/Y', $row['pubtime']));
        $row['numcomments'] = number_format($row['numcomments'], 0, ',', '.');
        $row['linkComment'] = nv_url_rewrite($row['link'], true) . '#comment';
        $row['icon'] = empty($BL->setting['iconClass' . $row['posttype']]) ? 'icon-pencil' : $BL->setting['iconClass' . $row['posttype']];

        // Cat phan gioi thieu ngan gon
        if ($BL->setting['strCutHomeText']) {
            $row['hometext'] = nv_clean60($row['hometext'], $BL->setting['strCutHomeText']);
        }

        // Hinh anh mac dinh neu khong co anh mo ta
        if (empty($row['images'])) {
            if ($BL->setting['indexViewType'] == 'type_blog') {
                $row['images'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/comingsoon-large.jpg';
            } else {
                $row['images'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/comingsoon-medium.jpg';
            }
        }

        $xtpl->assign('ROW', $row);

        // Chi xuat media neu nhu kieu hien thi la danh sach dang blog
        if (!empty($row['mediavalue']) and $BL->setting['indexViewType'] == 'type_blog' and !empty($row['mediashowlist'])) {
            if (in_array($row['mediatype'], array(0, 1))) {
                // Kieu hinh anh
                $xtpl->parse('main.loop.media.image');
            } elseif ($row['mediatype'] == 2) {
                // Kieu am thanh
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.audio.height');
                } else {
                    $xtpl->assign('ASPECTRATIO', $BL->GetAspectRatio($row['mediawidth'], $row['mediaheight']));
                    $xtpl->parse('main.loop.media.audio.aspectratio');
                }
                $xtpl->parse('main.loop.media.audio');
            } elseif ($row['mediatype'] == 3) {
                // Kieu video
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.video.height');
                } else {
                    $xtpl->assign('ASPECTRATIO', $BL->GetAspectRatio($row['mediawidth'], $row['mediaheight']));
                    $xtpl->parse('main.loop.media.video.aspectratio');
                }
                $xtpl->parse('main.loop.media.video');
            } elseif ($row['mediatype'] == 4) {
                // Kieu iframe
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.iframe.height');
                } else {
                    $xtpl->parse('main.loop.media.iframe.aspectratio');
                }
                $xtpl->parse('main.loop.media.iframe');
            }

            $xtpl->parse('main.loop.media');
        }

        // Xuất html, text
        if (!empty($row['fullpage'])) {
            $call_highlight = true;

            $xtpl->parse('main.loop.bodyhtml');
        } else {
            $xtpl->parse('main.loop.hometext');
        }

        $xtpl->parse('main.loop');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    // Gọi framewokrs highlight nếu có full page
    if ($call_highlight == true) {
        $BL->callFrameWorks('highlight');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_newsletters_theme()
 *
 * @param mixed $array
 * @return
 */
function nv_newsletters_theme($array)
{
    global $lang_global, $lang_module, $module_file, $module_info;

    $xtpl = new XTemplate("newsletters.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $array['class'] = $array['status'] ? "notification-box-error" : "notification-box-success";

    $xtpl->assign('DATA', $array);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_detail_theme()
 *
 * @param mixed $blog_data
 * @param mixed $BL
 * @return
 */
function nv_detail_theme($blog_data, $BL)
{
    global $lang_global, $lang_module, $module_file, $module_info, $my_head, $module_name;

    if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/images/' . $module_file . '/jwplayer/jwplayer.js')) {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/jwplayer/jwplayer.js\"></script>" . NV_EOL;
    } else {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/default/images/" . $module_file . "/jwplayer/jwplayer.js\"></script>" . NV_EOL;
    }
    $my_head .= "<script type=\"text/javascript\">jwplayer.key=\"KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==\";</script>" . NV_EOL;

    $xtpl = new XTemplate("detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

    $blog_data['pubtimeGoogle'] = nv_date('c', $blog_data['pubtime']);
    $blog_data['updatetimeGoogle'] = $blog_data['updatetime'] ? nv_date('c', $blog_data['updatetime']) : $blog_data['pubtimeGoogle'];
    $blog_data['pubtime'] = str_replace(array(' AM ', ' PM '), array(' SA ', ' CH '), nv_date('g:i A d/m/Y', $blog_data['pubtime']));
    $blog_data['numcomments'] = number_format($blog_data['numcomments'], 0, ',', '.');
    $blog_data['icon'] = empty($BL->setting['iconClass' . $blog_data['posttype']]) ? 'icon-pencil' : $BL->setting['iconClass' . $blog_data['posttype']];
    $blog_data['postName'] = $blog_data['postName'] ? $blog_data['postName'] : 'N/A';
    $blog_data['headlineGoogle'] = nv_clean60(strip_tags($blog_data['hometext']), 107); // Không vượt qua 110 ký tự theo Google

    $xtpl->assign('DATA', $blog_data);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);

    // Gọi frameworks định dạng code
    $BL->callFrameWorks('highlight');

    // Xuất media - ảnh minh họa
    if (!empty($blog_data['mediavalue']) and !empty($blog_data['mediashowdetail'])) {
        if (in_array($blog_data['mediatype'], array(0, 1))) {
            // Kieu hinh anh
            $xtpl->parse('main.media.image');
        } elseif ($blog_data['mediatype'] == 2) {
            // Kieu am thanh
            if (empty($blog_data['mediaresponsive'])) {
                $xtpl->parse('main.media.audio.height');
            } else {
                $xtpl->assign('ASPECTRATIO', $BL->GetAspectRatio($blog_data['mediawidth'], $blog_data['mediaheight']));
                $xtpl->parse('main.media.audio.aspectratio');
            }
            $xtpl->parse('main.media.audio');
        } elseif ($blog_data['mediatype'] == 3) {
            // Kieu video
            if (empty($blog_data['mediaresponsive'])) {
                $xtpl->parse('main.media.video.height');
            } else {
                $xtpl->assign('ASPECTRATIO', $BL->GetAspectRatio($blog_data['mediawidth'], $blog_data['mediaheight']));
                $xtpl->parse('main.media.video.aspectratio');
            }
            $xtpl->parse('main.media.video');
        } elseif ($blog_data['mediatype'] == 4) {
            // Kieu iframe
            if (empty($blog_data['mediaresponsive'])) {
                $xtpl->parse('main.media.iframe.height');
            } else {
                $xtpl->parse('main.media.iframe.aspectratio');
            }
            $xtpl->parse('main.media.iframe');
        }

        $xtpl->parse('main.media');
    }

    // Hiển thị quảng cáo nếu có cấu hình
    if (!empty($BL->setting['showAdsInDetailPage'])) {
        $xtpl->parse('main.ads');
    }

    // Xuất tags nếu có
    if (!empty($blog_data['tags'])) {
        foreach ($blog_data['tags'] as $tag) {
            $tag['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tags/' . $tag['alias'];

            $xtpl->assign('TAG', $tag);
            $xtpl->parse('main.tags.loop');
        }

        $xtpl->parse('main.tags');
    }

    // Xuất bài viết tiếp theo, bài viết trước đó
    if (!empty($blog_data['nextPost']) or !empty($blog_data['prevPost'])) {
        if (!empty($blog_data['nextPost'])) {
            $xtpl->parse('main.navPost.nextPost');
        }

        if (!empty($blog_data['prevPost'])) {
            $xtpl->parse('main.navPost.prevPost');
        }

        $xtpl->parse('main.navPost');
    }

    // Xuất google authorship
    // Không còn Google Author nữa do Google+ đã khai tử
    //if (!empty($blog_data['postgoogleid'])) {
    //    $xtpl->parse('main.postgoogleid');
    //} else {
    $xtpl->parse('main.postName');
    //}

    // Xuất facebook like nếu có cấu hình facebook App ID
    if (!empty($BL->setting['sysFbAppID']) and !empty($BL->setting['sysLocale'])) {
        $xtpl->assign('LOCALE', $BL->setting['sysLocale']);
        $xtpl->assign('FB_APP_ID', $BL->setting['sysFbAppID']);
        $xtpl->parse('main.fbShare');
    }

    // Xuất bình luận
    if ($BL->setting['commentType'] != 'none') {
        $xtpl->assign('COMMENT_PER_PAGE', $BL->setting['commentPerPage']);

        if (!empty($BL->setting['sysFbAppID']) and !empty($BL->setting['sysLocale']) and $BL->setting['commentType'] == 'facebook') {
            $xtpl->assign('COLORSCHEME', $BL->setting['commentFacebookColorscheme']);

            $xtpl->parse('main.comment.facebook');
        } elseif ($BL->setting['commentType'] == 'disqus' and !empty($BL->setting['commentDisqusShortname'])) {
            $xtpl->assign('DISQUS_SHORTNAME', $BL->setting['commentDisqusShortname']);

            $xtpl->parse('main.comment.disqus');
        }

        $xtpl->parse('main.comment');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_all_tags_theme()
 *
 * @param mixed $array
 * @param mixed $BL
 * @return
 */
function nv_all_tags_theme($array, $BL)
{
    global $lang_global, $lang_module, $module_file, $module_info;

    $xtpl = new XTemplate("tags-list.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if (empty($array)) {
        $xtpl->parse('empty');
        return $xtpl->text('empty');
    }

    $xtpl->assign('MESSAGE', sprintf($BL->lang('tagsInfoNumbers'), sizeof($array)));

    foreach ($array as $row) {
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_detail_tags_theme()
 *
 * @param mixed $array
 * @param mixed $generate_page
 * @param mixed $cfg
 * @param mixed $page
 * @param mixed $total_pages
 * @param mixed $BL
 * @return
 */
function nv_detail_tags_theme($array, $generate_page, $cfg, $page, $total_pages, $BL)
{
    global $lang_global, $lang_module, $module_file, $module_info, $my_head;

    // Nếu không có bài viết thì chỉ cần thông báo
    if (empty($array)) {
        return nv_message_theme($lang_module['noPost'], 3);
    }

    if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/images/' . $module_file . '/jwplayer/jwplayer.js')) {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/jwplayer/jwplayer.js\"></script>" . NV_EOL;
    } else {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/default/images/" . $module_file . "/jwplayer/jwplayer.js\"></script>" . NV_EOL;
    }
    $my_head .= "<script type=\"text/javascript\">jwplayer.key=\"KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==\";</script>" . NV_EOL;

    if ($BL->setting['catViewType'] == 'type_blog') {
        // Kieu danh sach blog
        $xtpl = new XTemplate("list_blog.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    } else {
        // Kieu danh sach tin tuc
        $xtpl = new XTemplate("list_news.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    }

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $xtpl->assign('PAGE_TOTAL', $total_pages);
    $xtpl->assign('PAGE_CURRENT', $page);

    // Có gọi lighlight js không
    $call_highlight = false;

    foreach ($array as $row) {
        $row['pubtime'] = str_replace(array(' AM ', ' PM '), array(' SA ', ' CH '), nv_date('g:i A d/m/Y', $row['pubtime']));
        $row['numcomments'] = number_format($row['numcomments'], 0, ',', '.');
        $row['linkComment'] = nv_url_rewrite($row['link'], true) . '#comment';
        $row['icon'] = empty($BL->setting['iconClass' . $row['posttype']]) ? 'icon-pencil' : $BL->setting['iconClass' . $row['posttype']];

        // Cat phan gioi thieu ngan gon
        if ($BL->setting['strCutHomeText']) {
            $row['hometext'] = nv_clean60($row['hometext'], $BL->setting['strCutHomeText']);
        }

        // Hinh anh mac dinh neu khong co anh mo ta
        if (empty($row['images'])) {
            if ($BL->setting['indexViewType'] == 'type_blog') {
                $row['images'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/comingsoon-large.jpg';
            } else {
                $row['images'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/comingsoon-medium.jpg';
            }
        }

        $xtpl->assign('ROW', $row);

        // Chi xuat media neu nhu kieu hien thi la danh sach dang blog
        if (!empty($row['mediavalue']) and $BL->setting['indexViewType'] == 'type_blog' and !empty($row['mediashowlist'])) {
            if (in_array($row['mediatype'], array(0, 1))) {
                // Kieu hinh anh
                $xtpl->parse('main.loop.media.image');
            } elseif ($row['mediatype'] == 2) {
                // Kieu am thanh
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.audio.height');
                } else {
                    $xtpl->assign('ASPECTRATIO', $BL->GetAspectRatio($row['mediawidth'], $row['mediaheight']));
                    $xtpl->parse('main.loop.media.audio.aspectratio');
                }
                $xtpl->parse('main.loop.media.audio');
            } elseif ($row['mediatype'] == 3) {
                // Kieu video
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.video.height');
                } else {
                    $xtpl->assign('ASPECTRATIO', $BL->GetAspectRatio($row['mediawidth'], $row['mediaheight']));
                    $xtpl->parse('main.loop.media.video.aspectratio');
                }
                $xtpl->parse('main.loop.media.video');
            } elseif ($row['mediatype'] == 4) {
                // Kieu iframe
                if (empty($row['mediaresponsive'])) {
                    $xtpl->parse('main.loop.media.iframe.height');
                } else {
                    $xtpl->parse('main.loop.media.iframe.aspectratio');
                }
                $xtpl->parse('main.loop.media.iframe');
            }

            $xtpl->parse('main.loop.media');
        }

        // Xuất html, text
        if (!empty($row['fullpage'])) {
            $call_highlight = true;

            $xtpl->parse('main.loop.bodyhtml');
        } else {
            $xtpl->parse('main.loop.hometext');
        }

        $xtpl->parse('main.loop');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    // Gọi framewokrs highlight nếu có full page
    if ($call_highlight == true) {
        $BL->callFrameWorks('highlight');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_message_theme()
 *
 * @param mixed $message
 * @param integer $lev: 0: Error, 1: Warning, 2: Success, 3: Info
 * @return void
 */
function nv_message_theme($message, $lev = 0)
{
    global $lang_global, $lang_module, $module_file, $module_info;

    $xtpl = new XTemplate("message.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('MESSAGE', $message);
    $xtpl->assign('CLASS', $lev == 0 ? 'alert-danger' : ($lev == 1 ? 'alert-warning' : ($lev == 2 ? 'alert-success' : 'alert-info')));
    $xtpl->assign('ICON', $lev == 0 ? 'fa-times-circle' : ($lev == 1 ? 'fa-exclamation-triangle' : ($lev == 2 ? 'fa-check-circle' : 'fa-info-circle')));

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_search_theme()
 *
 * @param mixed $array
 * @param mixed $page
 * @param mixed $total_pages
 * @param mixed $all_page
 * @param mixed $generate_page
 * @param mixed $BL
 * @return
 */
function nv_search_theme($array, $page, $total_pages, $all_page, $generate_page, $BL)
{
    global $lang_global, $lang_module, $module_file, $module_info;

    $xtpl = new XTemplate("search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('LANG', $lang_module);

    if (empty($array['contents']) and !empty($array['q'])) {
        $xtpl->assign('NORESULT_MESSAGE', nv_message_theme(sprintf($BL->lang('searchNoResult'), $array['q']), 1));
        $xtpl->parse('main.noResult');
    }

    if (!empty($array['contents'])) {
        $xtpl->assign('RESULT_INFO', sprintf($BL->lang('searchResultInfo'), $all_page, $array['q']));
        $xtpl->assign('PAGE_TOTAL', $total_pages);
        $xtpl->assign('PAGE_CURRENT', $page);

        foreach ($array['contents'] as $row) {
            $row['title'] = $BL->BoldKeywordInStr($row['title'], $array['q']);
            $row['hometext'] = $BL->BoldKeywordInStr($row['hometext'], $array['q']);
            $row['pubtime'] = str_replace(array(' AM ', ' PM '), array(' SA ', ' CH '), nv_date('g:i A d/m/Y', $row['pubtime']));
            $row['numcomments'] = number_format($row['numcomments'], 0, ',', '.');
            $row['linkComment'] = nv_url_rewrite($row['link'], true) . '#comment';
            $row['icon'] = empty($BL->setting['iconClass' . $row['posttype']]) ? 'icon-pencil' : $BL->setting['iconClass' . $row['posttype']];

            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.result.loop');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.result.generate_page');
        }

        $xtpl->parse('main.result');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
