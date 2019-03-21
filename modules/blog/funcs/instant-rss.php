<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
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

require NV_ROOTDIR . '/modules/' . $module_file . '/vendor/autoload.php';

use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Elements\Header;
use Facebook\InstantArticles\Elements\Time;
use Facebook\InstantArticles\Elements\Author;
use Facebook\InstantArticles\Elements\Image;
use Facebook\InstantArticles\Elements\Caption;
use Facebook\InstantArticles\Elements\Paragraph;

$article = InstantArticle::create();
$articleHeader = Header::create();

/*
 * Một số thiết lập
 */

// Kiểu bài viết
$article->withStyle('NukeVietBLog');

// URL gốc của bài viết
$article->withCanonicalURL('https://writeblabla.com/blog/hien-thi-bao-loi-khi-website-bi-loi-500-internal-server-error.html');

/*
 * Phần đầu của bài viết
 */

// Tiêu đề chính của bài viết
$articleHeader->withTitle('Hiển thị báo lỗi khi website bị lỗi 500 internal server error');

// Tiêu đề phụ. Thông thường không có cái này
$articleHeader->withSubTitle('');

// Thời gian xuất bản
$published = Time::create(Time::PUBLISHED);
$published->withDatetime((new \DateTime())->setTimestamp(1553184657));
$articleHeader->withPublishTime($published);

// Thời gian sửa
$modified = Time::create(Time::MODIFIED);
$modified->withDatetime((new \DateTime())->setTimestamp(1553184657));
$articleHeader->withModifyTime($modified);

// Tác giả (có thể add nhiều)
$author = Author::create();
$author->withName('Phan Tấn Dũng');
$author->withDescription('Fullstack Developer');

$articleHeader->addAuthor($author);

// Mô tả ngắn gọn của bài viết
$articleHeader->withKicker('Đối với phiên bản NukeViet 4.3.02 về sau, khi lỗi trang trắng nếu bật chức năng nhà phát triển lỗi sẽ hiển thị trừ một số trường hợp bị 500 internal server error có thể thực hiện theo cách sau để hiển thị');

// Ảnh đại diện của bài viết. Ngoài ảnh đại diện thì có thể là Video đại diện
$cover = Image::create();
$cover->withURL('https://writeblabla.com/uploads/blog/default-logo.jpg');
$cover->withCaption(Caption::create()->appendText('Bật lỗi trang trắng khi 500 internal server error'));

$articleHeader->withCover($cover);

$article->withHeader($articleHeader);

// Đoạn văn 1
$paragraph = Paragraph::create();
$paragraph->appendText('Mở file index.php ở thư mục gốc của website tìm dòng define(\'NV_SYSTEM\', true);, thêm xuống dưới dòng đó');

$article->addChild($paragraph);

$article_html = $article->render('<!doctype html>', true);

// FIXME
echo '<pre><code>' . htmlspecialchars($article_html) . '</code></pre>';
//nv_xmlOutput($article_html, NV_CURRENTTIME);
