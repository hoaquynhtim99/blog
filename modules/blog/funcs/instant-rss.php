<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if (!defined('NV_IS_MOD_BLOG')) {
    die('Stop!!!');
}

if (empty($BL->setting['instantArticlesActive'])) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}

require NV_ROOTDIR . '/modules/' . $module_file . '/vendor/autoload.php';

use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Elements\Header;
use Facebook\InstantArticles\Elements\Time;
use Facebook\InstantArticles\Elements\Author;
use Facebook\InstantArticles\Elements\Image;
use Facebook\InstantArticles\Elements\Caption;
use Facebook\InstantArticles\Elements\Paragraph;

if (!empty($BL->setting['instantArticlesHttpauth'])) {
    $auth = nv_set_authorization();

    if (empty($auth['auth_user']) or empty($auth['auth_pw']) or $auth['auth_user'] !== $BL->setting['instantArticlesUsername'] or $auth['auth_pw'] !== $crypt->decrypt($BL->setting['instantArticlesPassword'])) {
        header('WWW-Authenticate: Basic realm="Private Area"');
        header(NV_HEADERSTATUS . ' 401 Unauthorized');
        if (php_sapi_name() !== 'cgi-fcgi') {
            header('status: 401 Unauthorized');
        }
        nv_info_die($global_config['site_description'], $lang_global['site_info'], $BL->lang('insrss_not_auth'), 401);
    }
}

// Nguồn cấp RSS theo danh mục bài viết
$catid = 0;
if (isset($array_op[1])) {
    $defis = -1;

    foreach ($global_array_cat as $_cat) {
        $catlev = strlen($_cat['defis']);

        // Xem theo danh mục, ưu tiên danh mục con càng nhỏ càng tốt
        if ($_cat['alias'] == $array_op[1] and $catlev > $defis) {
            $defis = $catlev;
            $catid = $_cat['id'];
        }
    }
}

$channel = [];
$where = [];
$db->sqlreset()->from($BL->table_prefix . '_rows')->select('*');
$gettime = empty($BL->setting['instantArticlesGettime']) ? 0 : (NV_CURRENTTIME - ($BL->setting['instantArticlesGettime'] * 60));
$where['status'] = 'status=1';

if ($catid) {
    $channel['title'] = $global_array_cat[$catid]['title'] . ' - ' . $module_info['custom_title'];
    $channel['link'] = NV_MAIN_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'], true);
    $channel['description'] = $global_array_cat[$catid]['description'];
    $where['cat'] = $BL->build_query_search_id($catid, 'catids', 'AND');
} else {
    $channel['title'] = $module_info['custom_title'];
    $channel['link'] = NV_MAIN_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
    $channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];
}

// Lấy giới hạn trong khoảng thời gian
if ($gettime) {
    $where['time'] = 'pubtime>= ' . $gettime;
}
$db->order('pubtime DESC');
$db->where(implode(' AND ', $where));
$db->limit(100)->offset(0);

// Lấy RSS từ cache
$cacheFile = NV_LANG_DATA . '_instantrss' . $catid . '_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 60 * intval($BL->setting['instantArticlesLivetime']);

if (!defined('NV_IS_MODADMIN') and ($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $items = unserialize($cache);

    // Thời điểm tạo RSS này khi cache là thời điểm tạo file cache lấy từ dữ liệu lưu trong cache
    $lastBuildDate = $items[0];

    // Nội dung các bài viết thực là phần tử số 1
    $items = $items[1];
} else {
    $items = '';

    $sql = $db->sql();
    $result = $db->query($sql);

    // Khi không lấy được bài nào thì luôn luôn lấy 10 bài viết mới nhất cho chắc ăn
    if ($result->rowCount() < 1) {
        if ($gettime) {
            unset($where['time']);
        }
        $db->where(implode(' AND ', $where));
        $db->limit(10)->offset(0);

        $sql = $db->sql();
        $result = $db->query($sql);
    }

    // Lấy danh sách các bài viết
    $array_post = $array_post_table = [];
    while ($row = $result->fetch()) {
        $array_post[$row['id']] = $row;
        $table_number = ceil($row['id'] / 4000);
        $array_post_table[$table_number][] = $row['id'];
    }

    // Nội dung HTML của các bài viết lấy được
    $array_post_detail = [];
    if (!empty($array_post_table)) {
        foreach ($array_post_table as $table_number => $ids) {
            try {
                $sql = "SELECT id, bodyhtml FROM " . $BL->table_prefix . "_data_" . $table_number . " WHERE id IN(" . implode(',', $ids) . ")";
                $result = $db->query($sql);
                while ($row = $result->fetch()) {
                    $array_post_detail[$row['id']] = $row['bodyhtml'];
                }
            } catch (PDOException $ext) {
                trigger_error(print_r($ext, true));
            }
        }
    }

    foreach ($array_post as $row) {
        $row['link'] = NV_MAIN_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'], true);

        $article = InstantArticle::create();
        $articleHeader = Header::create();

        /*
         * Một số thiết lập
         */

        // Kiểu bài viết
        $article->withStyle($BL->setting['instantArticlesTemplate']);

        // URL gốc của bài viết
        $article->withCanonicalURL($row['link']);

        /*
         * Phần đầu của bài viết
         */

        // Tiêu đề chính của bài viết
        $articleHeader->withTitle($row['title']);

        // Tiêu đề phụ. Thông thường không có cái này
        $articleHeader->withSubTitle('');

        // Thời gian xuất bản
        $published = Time::create(Time::PUBLISHED);
        $published->withDatetime((new \DateTime())->setTimestamp($row['pubtime']));
        $articleHeader->withPublishTime($published);

        // Thời gian sửa
        $modified = Time::create(Time::MODIFIED);
        $modified->withDatetime((new \DateTime())->setTimestamp($row['updatetime']));
        $articleHeader->withModifyTime($modified);

        // Tác giả (có thể add nhiều)
        $author = Author::create();
        $author->withName('Phan Tấn Dũng');
        $author->withDescription('Fullstack Developer');

        $articleHeader->addAuthor($author);

        // Mô tả ngắn gọn của bài viết
        $articleHeader->withKicker($row['hometext']);

        // Ảnh đại diện của bài viết. Ngoài ảnh đại diện thì có thể là Video đại diện
        $cover = Image::create();
        $cover->withURL('https://writeblabla.com/uploads/blog/default-logo.jpg');
        $cover->withCaption(Caption::create()->appendText('Bật lỗi trang trắng khi 500 internal server error'));

        $articleHeader->withCover($cover);

        $article->withHeader($articleHeader);

        // Đoạn văn 1
        $paragraph = Paragraph::create();
        $paragraph->appendText('Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm. Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm. Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm.');

        $article->addChild($paragraph);

        // Đoạn văn 2
        $paragraph = Paragraph::create();
        $paragraph->appendText('Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm. Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm. Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm.');

        $article->addChild($paragraph);

        // Đoạn văn 3
        $paragraph = Paragraph::create();
        $paragraph->appendText('Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm. Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm. Đây là nội dung thử nghiệm của bài viết, sau này sẽ viết thêm.');

        $article->addChild($paragraph);

        $article_html = $article->render('<!doctype html>', true);

        unset($article, $articleHeader);

        $items .= '    <item>' . PHP_EOL;
        $items .= '      <title>' . $row['title'] . '</title>' . PHP_EOL;
        $items .= '      <link>' . $row['link'] . '</link>' . PHP_EOL;
        $items .= '      <guid>' . $module_name . '-post-' . $row['id'] . '</guid>' . PHP_EOL;
        $items .= '      <pubDate>' . date('c', $row['pubtime']) . '</pubDate>' . PHP_EOL;
        $items .= '      <author>Phan Tấn Dũng</author>' . PHP_EOL;
        $items .= '      <description>' . $row['hometext'] . '</description>' . PHP_EOL;
        $items .= '      <content:encoded>' . PHP_EOL;
        $items .= '        <![CDATA[' . $article_html . ']]>' . PHP_EOL;
        $items .= '      </content:encoded>' . PHP_EOL;
        $items .= '    </item>' . PHP_EOL;
    }

    if (!defined('NV_IS_MODADMIN') and !empty($items)) {
        $cache = serialize([NV_CURRENTTIME, $items]);
        $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
    }

    // Thời điểm tạo RSS này khi không cache là thời điểm hiện tại
    $lastBuildDate = NV_CURRENTTIME;
}

$rss = '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . PHP_EOL;
$rss .= '  <channel>' . PHP_EOL;
$rss .= '    <title>' . $channel['title'] . '</title>' . PHP_EOL;
$rss .= '    <link>' . $channel['link'] . '</link>' . PHP_EOL;
$rss .= '    <description>' . PHP_EOL;
$rss .= '      <![CDATA[' . $channel['description'] . ']]>' . PHP_EOL;
$rss .= '    </description>' . PHP_EOL;
$rss .= '    <language>' . $BL->setting['sysLocale'] . '</language>' . PHP_EOL;
$rss .= '    <lastBuildDate>' . date('c', $lastBuildDate) . '</lastBuildDate>' . PHP_EOL;
$rss .= $items;
$rss .= '  </channel>' . PHP_EOL;
$rss .= '</rss>' . PHP_EOL;

nv_xmlOutput($rss, $lastBuildDate);
