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

use NukeViet\Module\blog\Parsedown;

// Xử lý thư mục upload
$username_alias = change_alias($admin_info['username']);
$array_structure_image = [];
$array_structure_image[''] = $module_upload;
$array_structure_image['Y'] = $module_upload . '/' . date('Y');
$array_structure_image['Ym'] = $module_upload . '/' . date('Y_m');
$array_structure_image['Y_m'] = $module_upload . '/' . date('Y/m');
$array_structure_image['Ym_d'] = $module_upload . '/' . date('Y_m/d');
$array_structure_image['Y_m_d'] = $module_upload . '/' . date('Y/m/d');
$array_structure_image['username'] = $module_upload . '/' . $username_alias;

$array_structure_image['username_Y'] = $module_upload . '/' . $username_alias . '/' . date('Y');
$array_structure_image['username_Ym'] = $module_upload . '/' . $username_alias . '/' . date('Y_m');
$array_structure_image['username_Y_m'] = $module_upload . '/' . $username_alias . '/' . date('Y/m');
$array_structure_image['username_Ym_d'] = $module_upload . '/' . $username_alias . '/' . date('Y_m/d');
$array_structure_image['username_Y_m_d'] = $module_upload . '/' . $username_alias . '/' . date('Y/m/d');

$currentpath = isset($array_structure_image[$BL->setting['folderStructure']]) ? $array_structure_image[$BL->setting['folderStructure']] : '';

if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $currentpath)) {
    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
} else {
    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_upload;
    $e = explode("/", $currentpath);
    if (!empty($e)) {
        $cp = "";
        foreach ($e as $p) {
            if (!empty($p) and !is_dir(NV_UPLOADS_REAL_DIR . '/' . $cp . $p)) {
                $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $cp, $p);
                if ($mk[0] > 0) {
                    $upload_real_dir_page = $mk[2];
                    try {
                        $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)");
                    } catch (PDOException $e) {
                        trigger_error(print_r($e, true));
                    }
                }
            } elseif (!empty($p)) {
                $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
            }
            $cp .= $p . '/';
        }
    }
    $upload_real_dir_page = str_replace("\\", "/", $upload_real_dir_page);
}

$currentpath = str_replace(NV_ROOTDIR . "/", "", $upload_real_dir_page);
$page_title = $nv_Lang->getModule('blogManager');

// Lay va khoi tao cac bien
$error = "";
$complete = false;
$id = $nv_Request->get_absint('id', 'get, post', 0);

// Xu ly
if ($id) {
    $sql = "SELECT * FROM " . $BL->table_prefix . "_rows WHERE id=" . $id;
    $result = $db->query($sql);

    if ($result->rowCount() != 1) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'));
    }

    $row = $result->fetch();

    $array_old = $array = [
        "postid" => (int) $row['postid'],
        "postgoogleid" => $row['postgoogleid'],
        "sitetitle" => $row['sitetitle'],
        "title" => $row['title'],
        "alias" => $row['alias'],
        "keywords" => $row['keywords'],
        "images" => $row['images'],
        "mediatype" => (int) $row['mediatype'],
        "mediashowlist" => (int) $row['mediashowlist'],
        "mediashowdetail" => (int) $row['mediashowdetail'],
        "mediaheight" => (int) $row['mediaheight'],
        "mediawidth" => (int) $row['mediawidth'],
        "mediaresponsive" => (int) $row['mediaresponsive'],
        "mediavalue" => $row['mediavalue'],
        "hometext" => nv_br2nl($row['hometext']),
        "bodytext" => $row['bodytext'],
        "bodyhtml" => '',
        "posttype" => (int) $row['posttype'],
        "fullpage" => (int) $row['fullpage'],
        "inhome" => (int) $row['inhome'],
        "catids" => $BL->string2array($row['catids']),
        "tagids" => $BL->string2array($row['tagids']),
        "numwords" => (int) $row['numwords'],
        "pubtime" => (int) $row['pubtime'],
        "pubtime_h" => date("G", $row['pubtime']),
        "pubtime_m" => (int) date("i", $row['pubtime']),
        "exptime" => (int) $row['exptime'],
        "exptime_h" => $row['exptime'] ? date("G", $row['exptime']) : 0,
        "exptime_m" => $row['exptime'] ? (int) date("i", $row['exptime']) : 0,
        "expmode" => (int) $row['expmode'],
        "status" => (int) $row['status'],
        'markdown_text' => ''
    ];
    $postingMode = $row['post_mode'];

    $sql = "SELECT * FROM " . $BL->table_prefix . "_rows_detail WHERE id=" . $id;
    $result = $db->query($sql);

    if ($result->rowCount()) {
        $row = $result->fetch();
        $array_old['bodyhtml'] = $array['bodyhtml'] = $row['bodyhtml'];
        $array_old['markdown_text'] = $array['markdown_text'] = $row['markdown_text'];
    }

    // Gui email den cac email dang ky nhan tin
    $newsletters = 0;
    $isAutoKeywords = 0;
} else {
    $array = [
        "postid" => $admin_info['userid'],
        "postgoogleid" => $BL->setting['sysGoogleAuthor'],
        "sitetitle" => '',
        "title" => '',
        "alias" => '',
        "keywords" => '',
        "images" => '',
        "mediatype" => $BL->setting['initMediaType'],
        "mediashowlist" => 1,
        "mediashowdetail" => 1,
        "mediaheight" => $BL->setting['initMediaHeight'],
        "mediawidth" => $BL->setting['initMediaWidth'],
        "mediaresponsive" => $BL->setting['initMediaResponsive'],
        "mediavalue" => '',
        "hometext" => '',
        "bodytext" => '',
        "bodyhtml" => '',
        "posttype" => $BL->setting['initPostType'],
        "fullpage" => 0,
        "inhome" => 1,
        "catids" => [],
        "tagids" => [],
        "numwords" => 0,
        "pubtime" => NV_CURRENTTIME,
        "pubtime_h" => date("G", NV_CURRENTTIME),
        "pubtime_m" => (int)date("i", NV_CURRENTTIME),
        "exptime" => 0,
        "exptime_h" => 0,
        "exptime_m" => 0,
        "expmode" => $BL->setting['initPostExp'],
        "status" => -2,
        'markdown_text' => ''
    ];

    // Kiểu đăng bài mới
    $postingMode = $BL->setting['postingMode'];

    // Gui email den cac email dang ky nhan tin
    $newsletters = $BL->setting['initNewsletters'];

    // Tự động xác định từ khóa
    $isAutoKeywords = $BL->setting['initAutoKeywords'];
}

// Thao tac xu ly
$prosessMode = "none";
if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    if ($nv_Request->isset_request('submit', 'post')) {
        $prosessMode = "public";
    } elseif ($nv_Request->isset_request('draft', 'post')) {
        $prosessMode = "draft";
    }
}

// Xu ly khi submit
if ($prosessMode != 'none') {
    $array['postgoogleid'] = nv_substr($nv_Request->get_title('postgoogleid', 'post', '', 1), 0, 255);
    $array['sitetitle'] = nv_substr($nv_Request->get_title('sitetitle', 'post', '', 1), 0, 255);
    $array['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $array['alias'] = nv_substr($nv_Request->get_title('alias', 'post', '', 1), 0, 255);
    $array['keywords'] = nv_substr($nv_Request->get_title('keywords', 'post', '', 1), 0, 255);
    $array['images'] = $nv_Request->get_string('images', 'post', '');
    $array['mediatype'] = $nv_Request->get_int('mediatype', 'post', 0);
    $array['mediashowlist'] = $nv_Request->get_int('mediashowlist', 'post', 0);
    $array['mediashowdetail'] = $nv_Request->get_int('mediashowdetail', 'post', 0);
    $array['mediaheight'] = $nv_Request->get_int('mediaheight', 'post', 0);
    $array['mediawidth'] = $nv_Request->get_int('mediawidth', 'post', 0);
    $array['mediaresponsive'] = ($nv_Request->get_int('mediaresponsive', 'post', 0) ? 1 : 0);
    $array['mediavalue'] = $nv_Request->get_string('mediavalue', 'post', '');
    $array['hometext'] = $nv_Request->get_textarea('hometext', '', NV_ALLOWED_HTML_TAGS);
    $array['bodyhtml'] = $nv_Request->get_editor('bodyhtml', '', NV_ALLOWED_HTML_TAGS);
    $array['posttype'] = $nv_Request->get_int('posttype', 'post', 0);
    $array['fullpage'] = $nv_Request->get_int('fullpage', 'post', 0);
    $array['inhome'] = $nv_Request->get_int('inhome', 'post', 0);
    $array['catids'] = $nv_Request->get_typed_array('catids', 'post', 'int');
    $array['tagids'] = nv_substr($nv_Request->get_title('tagids', 'post', '', 1), 0, 255);
    $array['pubtime'] = $nv_Request->get_string('pubtime', 'post', '');
    $array['pubtime_h'] = $nv_Request->get_int('pubtime_h', 'post', 0);
    $array['pubtime_m'] = $nv_Request->get_int('pubtime_m', 'post', 0);
    $array['exptime'] = $nv_Request->get_string('exptime', 'post', '');
    $array['exptime_h'] = $nv_Request->get_int('exptime_h', 'post', 0);
    $array['exptime_m'] = $nv_Request->get_int('exptime_m', 'post', 0);
    $array['expmode'] = $nv_Request->get_int('expmode', 'post', 0);
    $array['markdown_text'] = isset($_POST['markdown_text']) ? (trim($_POST['markdown_text']) . "\n") : '';

    $newsletters = $nv_Request->get_int('newsletters', 'post', 0);
    $isAutoKeywords = $nv_Request->get_int('isAutoKeywords', 'post', 0);
    $postingMode = $nv_Request->get_title('postingMode', 'post', '');

    // Chuẩn hóa google author
    if (!preg_match("/^([0-9]{1,30})$/", $array['postgoogleid'])) {
        $array['postgoogleid'] = $BL->setting['sysGoogleAuthor'];
    }

    // Kiểu đăng
    if (!in_array($postingMode, $BL->postingMode)) {
        $postingMode = $BL->postingMode[0];
    }
    if ($postingMode == 'markdown') {
        $Parsedown = new Parsedown();
        $array['bodyhtml'] = $Parsedown->text($array['markdown_text']);
    }

    // Tự động lấy từ khóa
    if (!empty($isAutoKeywords) and empty($array['keywords'])) {
        $array['keywords'] = nv_get_keywords($array['bodyhtml']);
    }

    // Chuan hoa tu khoa
    $array['keywords'] = $array['keywords'] ? implode(", ", array_filter(array_unique(array_map("trim", explode(",", $array['keywords']))))) : "";

    // Chinh duong dan anh
    if (!empty($array['images'])) {
        if (preg_match("/^\//i", $array['images'])) {
            $array['images'] = substr($array['images'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name));
        }
    }

    // Chinh duong dan media
    if (!empty($array['mediavalue'])) {
        if (preg_match("/^\//i", $array['mediavalue'])) {
            $array['mediavalue'] = substr($array['mediavalue'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name));
        }
    }

    // Chuan hoa, khoi tao lien ket tinh
    if (!empty($array['alias'])) {
        $array['alias'] = strtolower(change_alias($array['alias']));
    } else {
        if (!empty($array['title'])) {
            $array['alias'] = strtolower(change_alias($array['title']));
        } elseif ($prosessMode == "draft") {
            $array['alias'] = "draft";
        }
    }

    // Tao bodytext
    $array['bodytext'] = trim(nv_nl2br(strip_tags($array['bodyhtml']), " "));

    // Chuan hoa loai bai viet
    if (!in_array($array['posttype'], $BL->blogposttype)) {
        $array['posttype'] = 0;
    }

    // Chuan hoa loai media
    if (!in_array($array['mediatype'], $BL->blogMediaType)) {
        $array['mediatype'] = 0;
    }

    // Thay doi danh muc, tags string => array
    $array['tagids'] = $BL->string2array($array['tagids']);

    // Chuan hoa va xu ly thoi gian
    if ($array['pubtime_h'] > 23 or $array['pubtime_h'] < 0) {
        $array['pubtime_h'] = 0;
    }
    if ($array['pubtime_m'] > 59 or $array['pubtime_m'] < 0) {
        $array['pubtime_m'] = 0;
    }
    if ($array['exptime_h'] > 23 or $array['exptime_h'] < 0) {
        $array['exptime_h'] = 0;
    }
    if ($array['exptime_m'] > 59 or $array['exptime_m'] < 0) {
        $array['exptime_m'] = 0;
    }

    $array['pubtime'] = nv_d2u_post($array['pubtime'], $array['pubtime_h'], $array['pubtime_m'], 0);
    $array['exptime'] = nv_d2u_post($array['exptime'], $array['exptime_h'], $array['exptime_m'], 0);

    // Chuan hoa kieu xu ly khi het han
    if (!in_array($array['expmode'], $BL->blogExpMode)) {
        $array['expmode'] = 0;
    }

    // Chuẩn hóa giá trị 0, 1
    $array['fullpage'] = $array['fullpage'] ? 1 : 0;
    $array['inhome'] = $array['inhome'] ? 1 : 0;

    // Kiem tra loi, khong kiem tra neu luu ban nhap
    if ($prosessMode == "public") {
        if (empty($array['title'])) {
            $error = $nv_Lang->getModule('blogErrorTitle');
        } elseif (empty($array['keywords'])) {
            $error = $nv_Lang->getModule('errorKeywords');
        } elseif (empty($array['hometext'])) {
            $error = $nv_Lang->getModule('blogErrorHometext');
        } elseif (empty($array['bodytext'])) {
            $error = $nv_Lang->getModule('blogErrorBodyhtml');
        } elseif (empty($array['catids'])) {
            $error = $nv_Lang->getModule('blogErrorCategories');
        } elseif (!empty($array['exptime']) and $array['exptime'] <= $array['pubtime']) {
            $error = $nv_Lang->getModule('blogErrorExpThanPub');
        } elseif (!empty($array['exptime']) and $array['exptime'] <= NV_CURRENTTIME and $array['expmode'] == 2) {
            $error = $nv_Lang->getModule('blogErrorExp');
        } elseif (!empty($array['mediatype']) and empty($array['mediavalue'])) {
            $error = $nv_Lang->getModule('blogErrorMediaValue');
        } elseif ($array['mediatype'] > 1 and empty($array['mediaheight'])) {
            $error = $nv_Lang->getModule('blogErrorMediaHeight');
        } elseif ($array['mediaresponsive'] and empty($array['mediawidth'])) {
            $error = $nv_Lang->getModule('blogErrorMediaWidth');
        }
    }

    // Kiem tra lien ket tinh ton tai va tao lien ket tinh khac neu la luu ban nhap
    $sql = "SELECT * FROM " . $BL->table_prefix . "_rows WHERE alias=" . $db->quote($array['alias']) . (!empty($id) ? " AND id!=" . $id : "");
    $result = $db->query($sql);

    if ($result->rowCount()) {
        // Neu la xuat ban thi bao loi ton tai
        if ($prosessMode == "public") {
            $error = $nv_Lang->getModule('errorAliasExists');
        } else {
            // Lưu bản nháp, trùng thì tạo liên kết tĩnh khác tự động
            $array['alias'] = $BL->creatAlias($array['alias'], 'post');
        }
    }

    // Xac dinh status
    if ($prosessMode == "draft") {
        $array['status'] = -2;
    } elseif ($array['exptime'] <= NV_CURRENTTIME and !empty($array['exptime'])) {
        // Bai viet het han
        $array['status'] = $array['expmode'] == 0 ? 0 : 2;
    } elseif ($array['pubtime'] > NV_CURRENTTIME) {
        // Bai viet cho dang
        // Tao bai viet thi -1
        if (empty($id)) {
            $array['status'] = -1;
        } else {
            // Sua bai viet
            // Neu khong bi dung thi cho dang
            if (!in_array($array_old['status'], [0])) {
                $array['status'] = -1;
            } else {
                // Bi dung thi tiep tuc dung, nhap thi tiep tuc nhap
                $array['status'] = $array_old['status'];
            }
        }
    } else {
        // Tao bai viet thi 1
        if (empty($id)) {
            $array['status'] = 1;
        } else {
            // Sua bai viet
            // Neu khong bi dung thi cho dang
            if (!in_array($array_old['status'], [0])) {
                $array['status'] = 1;
            } else {
                // Bi dung thi tiep tuc dung, nhap thi tiep tuc nhap
                $array['status'] = $array_old['status'];
            }
        }
    }

    if (empty($error)) {
        $array['hometext'] = nv_nl2br($array['hometext']);

        if (empty($id)) {
            $sql = "INSERT INTO " . $BL->table_prefix . "_rows (
                postid, postgoogleid, sitetitle, title, alias, keywords, images, mediatype, mediashowlist, mediashowdetail,
                mediaheight, mediawidth, mediaresponsive, mediavalue, hometext, bodytext, posttype, fullpage, inhome, catids, tagids,
                numwords, numviews, numcomments, numvotes, votetotal, votedetail, posttime, updatetime, pubtime, exptime, expmode, status,
                post_mode
            ) VALUES(
                " . $array['postid'] . ",
                " . $db->quote($array['postgoogleid']) . ",
                " . $db->quote($array['sitetitle']) . ",
                " . $db->quote($array['title']) . ",
                " . $db->quote($array['alias']) . ",
                " . $db->quote($array['keywords']) . ",
                " . $db->quote($array['images']) . ",
                " . $array['mediatype'] . ",
                " . $array['mediashowlist'] . ",
                " . $array['mediashowdetail'] . ",
                " . $array['mediaheight'] . ",
                " . $array['mediawidth'] . ",
                " . $array['mediaresponsive'] . ",
                " . $db->quote($array['mediavalue']) . ",
                " . $db->quote($array['hometext']) . ",
                " . $db->quote($array['bodytext']) . ",
                " . $array['posttype'] . ",
                " . $array['fullpage'] . ",
                " . $array['inhome'] . ",
                " . $db->quote($array['catids'] ? implode(',', $array['catids']) : '') . ",
                " . $db->quote($array['tagids'] ? implode(',', $array['tagids']) : '') . ",
                " . $array['numwords'] . ",
                0, 0, 0, 0, '',
                " . NV_CURRENTTIME . ",
                " . NV_CURRENTTIME . ",
                " . $array['pubtime'] . ",
                " . $array['exptime'] . ",
                " . $array['expmode'] . ",
                " . $array['status'] . ",
                " . $db->quote($postingMode) . "
            )";

            $id = $db->insert_id($sql);

            if ($id) {
                // Luu noi dung bodyhtml vao
                $sql = "INSERT INTO " . $BL->table_prefix . "_rows_detail (
                    id, bodyhtml, markdown_text
                ) VALUES (
                    " . $id . ", " . $db->quote($array['bodyhtml']) . ",
                    " . $db->quote($array['markdown_text']) . "
                )";
                if (!$db->query($sql) and $prosessMode != "draft") {
                    $error = $nv_Lang->getModule('blogErrorSaveHtml');
                }

                if (empty($error)) {
                    $complete = true;

                    if ($prosessMode != "draft") {
                        // Cap nhat danh muc
                        $BL->updateCatStatistics($array['catids']);

                        // Cap nhat tags
                        $BL->fixTags($array['tagids']);

                        // Gui newsletters
                        if (!empty($newsletters)) {
                            $sql = "INSERT INTO " . $BL->table_prefix . "_send VALUES( NULL, " . $id . ", 0, 0, 0, 1, 0, '', '' )";
                            $db->query($sql);
                        }

                        // Xoa cache
                        $nv_Cache->delMod($module_name);
                    }

                    // Xu ly tin
                    $BL->executeData();
                }
            } else {
                $error = $nv_Lang->getModule('errorSaveUnknow');
            }
        } else {
            $sql = "UPDATE " . $BL->table_prefix . "_rows SET
                postid=" . $array['postid'] . ",
                postgoogleid=" . $db->quote($array['postgoogleid']) . ",
                sitetitle=" . $db->quote($array['sitetitle']) . ",
                title=" . $db->quote($array['title']) . ",
                alias=" . $db->quote($array['alias']) . ",
                keywords=" . $db->quote($array['keywords']) . ",
                images=" . $db->quote($array['images']) . ",
                mediatype=" . $array['mediatype'] . ",
                mediashowlist=" . $array['mediashowlist'] . ",
                mediashowdetail=" . $array['mediashowdetail'] . ",
                mediaheight=" . $array['mediaheight'] . ",
                mediawidth=" . $array['mediawidth'] . ",
                mediaresponsive=" . $array['mediaresponsive'] . ",
                mediavalue=" . $db->quote($array['mediavalue']) . ",
                hometext=" . $db->quote($array['hometext']) . ",
                bodytext=" . $db->quote($array['bodytext']) . ",
                posttype=" . $array['posttype'] . ",
                fullpage=" . $array['fullpage'] . ",
                inhome=" . $array['inhome'] . ",
                catids=" . $db->quote($array['catids'] ? implode(',', $array['catids']) : '') . ",
                tagids=" . $db->quote($array['tagids'] ? implode(',', $array['tagids']) : '') . ",
                numwords=" . $array['numwords'] . ",
                updatetime=" . NV_CURRENTTIME . ",
                pubtime=" . $array['pubtime'] . ",
                exptime=" . $array['exptime'] . ",
                expmode=" . $array['expmode'] . ",
                status=" . $array['status'] . ",
                post_mode=" . $db->quote($postingMode) . "
            WHERE id=" . $id;

            if ($db->query($sql)) {
                // Luu noi dung bodyhtml vao
                $sql = "UPDATE " . $BL->table_prefix . "_rows_detail SET
                    bodyhtml=" . $db->quote($array['bodyhtml']) . ",
                    markdown_text=" . $db->quote($array['markdown_text']) . "
                WHERE id=" . $id;
                $db->query($sql);

                if (empty($error)) {
                    $complete = true;

                    if ($prosessMode != "draft") {
                        // Cap nhat danh muc
                        $BL->updateCatStatistics(array_unique(array_filter(array_merge_recursive($array_old['catids'], $array['catids']))));

                        // Cap nhat tags
                        $BL->fixTags(array_unique(array_filter(array_merge_recursive($array_old['tagids'], $array['tagids']))));

                        // Xoa cache
                        $nv_Cache->delMod($module_name);
                    }

                    // Xu ly tin
                    $BL->executeData();
                }
            } else {
                $error = $nv_Lang->getModule('errorUpdateUnknow');
            }
        }
    }
}

$array['hometext'] = nv_htmlspecialchars($array['hometext']);
$array['bodyhtml'] = nv_htmlspecialchars($array['bodyhtml']);
$array['markdown_text'] = htmlspecialchars($array['markdown_text'], ENT_QUOTES);

// Trinh soan thao
if (defined('NV_EDITOR')) {
    require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['bodyhtml'] = nv_aleditor('bodyhtml', '100%', '400px', $array['bodyhtml'], '', NV_UPLOADS_DIR . '/' . $module_upload, $currentpath);
} else {
    $array['bodyhtml'] = "<textarea name=\"bodyhtml\" id=\"bodyhtml\" class=\"form-control\" rows=\"10\">" . $array['bodyhtml'] . "</textarea>";
}

// Chuyen so thanh chuoi
if (empty($array['mediaheight'])) {
    $array['mediaheight'] = "";
}
if (empty($array['mediawidth'])) {
    $array['mediawidth'] = "";
}
if (!empty($array['images']) and preg_match("/^\//i", $array['images'])) {
    $array['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . $array['images'];
}
if (!empty($array['mediavalue']) and preg_match("/^\//i", $array['mediavalue'])) {
    $array['mediavalue'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . $array['mediavalue'];
}
$array['pubtime'] = nv_u2d_post($array['pubtime']);
$array['exptime'] = nv_u2d_post($array['exptime']);

$template = get_tpl_dir([$global_config['module_theme'], $global_config['admin_theme']], 'admin_default', '/modules/' . $module_file . '/blog-content.tpl');
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TOKEND', NV_CHECK_SESSION);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('MODULE_FILE', $module_file);

$tpl->assign('ID', $id);
$tpl->assign('DATA', $array);
$tpl->assign('TAGIDS', implode(',', $array['tagids']));
$tpl->assign('EDITOR', (defined('NV_EDITOR') and nv_function_exists('nv_aleditor') and $postingMode == 'editor') ? 'true' : 'false');
$tpl->assign('DATE_PLACEHOLDER', nv_region_config('jsdate_post'));

// Trả về JSON nếu lưu bản nháp
if ($prosessMode == "draft") {
    $contents = [
        "error" => empty($error) ? 0 : 1,
        "title" => $complete ? $nv_Lang->getModule('success') : $nv_Lang->getModule('error'),
        "message" => $complete ? $nv_Lang->getModule('blogSaveDraftOk') : $error,
        "id" => $id,
    ];
    nv_jsonOutput($contents);
}
if ($complete) {
    $url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=blog-list";
    nv_redirect_location($url);
}

// Một số tag nhiều bài viết
$sql = "SELECT * FROM " . $BL->table_prefix . "_tags ORDER BY numposts DESC LIMIT 10";
$mosttags = $db->query($sql)->fetchAll();

// Các tag của bài viết này
$tags = $BL->getTagsByID($array['tagids'], true);

$tpl->assign('ERROR', $error);
$tpl->assign('LIST_CATS', $global_array_cat);
$tpl->assign('BLOGMEDIATYPE', $BL->blogMediaType);
$tpl->assign('BLOGEXPMODE', $BL->blogExpMode);
$tpl->assign('BLOGPOSTTYPE', $BL->blogposttype);
$tpl->assign('MOSTTAGS', $mosttags);
$tpl->assign('TAGS', $tags);
$tpl->assign('POSTINGMODE', $postingMode);

$tpl->assign('NEWSLETTERS', $newsletters);
$tpl->assign('ISAUTOKEYWORDS', $isAutoKeywords);
$tpl->assign('UPLOADS_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('CURRENT_PATH', $currentpath);

$contents = $tpl->fetch('blog-content.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
