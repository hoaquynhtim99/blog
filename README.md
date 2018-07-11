# Hướng dẫn nâng cấp module blog từ bản 3.4.01 lên 4.1.02

> Chú ý: Hệ thống phiên bản 3x lên 4x có rất nhiều thay đổi nên tại đây hướng dẫn các bạn nâng cấp module với hình thức thủ công. Hướng dẫn chỉ dành cho những bạn đã có kỹ năng cơ bản về MySQL, NukeViet. Các bạn mới sử dụng hoặc chưa am hiểu có thể nhờ bạn bè, tổ chức, các nhân khác giúp đỡ.

## Bước 1: Sao lưu dữ liệu cũ.

### Sao lưu CSDL.

Backup các bảng bắt đầu bằng `nv3_vi_blog`. Trong đó chú ý:

- `nv3` là giá trị tiếp đầu tố của CSDL.
- `vi` là ngôn ngữ mà module được cài đặt.
- `blog` là tên module cần nâng cấp.

Từ ba giá trị trên để xác định được các bảng CSDL đúng, có khoảng 7 bảng cho mỗi module.

### Sao lưu file uploads.

Backup thư mục `uploads/blog` lại. Nếu module bạn là module ảo thì thư mục blog thay bằng tên module ảo đó.

## Bước 2: Nâng cấp CSDL.

Tạo một DATABASE mới, sau đó import CSDL vừa backup được và chạy các câu lệnh sau:

```sql
ALTER TABLE `nv3_vi_blog_categories` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_categories` 
CHANGE `title` `title` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', 
CHANGE `alias` `alias` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', 
CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm', 
CHANGE `description` `description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Mô tả cho máy chủ tìm kiếm',
CHANGE `numSubs` `numsubs` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số danh mục con', 
CHANGE `numPosts` `numposts` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số bài viết';

ALTER TABLE `nv3_vi_blog_config` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_config` 
CHANGE `config_name` `config_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, 
CHANGE `config_value` `config_value` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE `nv3_vi_blog_data_1` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_data_1` CHANGE `bodyhtml` `bodyhtml` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE `nv3_vi_blog_newsletters` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_newsletters` 
CHANGE `email` `email` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Email đăng ký', 
CHANGE `regIP` `regip` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP đã đăng ký', 
CHANGE `regTime` `regtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian đăng ký', 
CHANGE `confirmTime` `confirmtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian xác nhận', 
CHANGE `lastSendTime` `lastsendtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Lần gửi cuối', 
CHANGE `tokenKey` `tokenkey` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Khóa xác nhận', 
CHANGE `numEmail` `numemail` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số email đã gửi';

ALTER TABLE `nv3_vi_blog_rows` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_rows` 
CHANGE `postGoogleID` `postgoogleid` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Google Author', 
CHANGE `siteTitle` `sitetitle` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Tiêu đề của trang, mặc định là tiêu đề bài viết', 
CHANGE `title` `title` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Tên bài viết', 
CHANGE `alias` `alias` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Liên kết tĩnh', 
CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm', 
CHANGE `images` `images` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm', 
CHANGE `mediaType` `mediatype` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: Dùng ảnh đại diện, 1: Dùng hình ảnh tùy chọn, 2: File âm thanh, 3: File video, 4: Iframe', 
CHANGE `mediaHeight` `mediaheight` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Chiều cao media', 
CHANGE `mediaValue` `mediavalue` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung media', 
CHANGE `hometext` `hometext` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mô tả ngắn gọn', 
CHANGE `bodytext` `bodytext` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung bài viết dạng text', 
CHANGE `postType` `posttype` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: Bình thường, 1: Ảnh, 2: Video, 3: Audio, 4: Ghi chú, 5: Liên kết, 6: Thư viện', 
CHANGE `fullPage` `fullpage` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Nếu là 1, đối với kiểu hiển thị dạng blog, sẽ show toàn bộ nội dung bài viết', 
CHANGE `inHome` `inhome` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Hiển thị bài viết trên trang chủ', 
CHANGE `catids` `catids` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Chuyên mục', 
CHANGE `tagids` `tagids` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Tags', 
CHANGE `numWords` `numwords` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số từ', 
CHANGE `numViews` `numviews` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Lượt xem', 
CHANGE `numComments` `numcomments` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số bình luận', 
CHANGE `numVotes` `numvotes` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số lượt đánh giá', 
CHANGE `voteTotal` `votetotal` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Tổng số điểm', 
CHANGE `voteDetail` `votedetail` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Chi tiết đánh giá', 
CHANGE `postTime` `posttime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian viết', 
CHANGE `updateTime` `updatetime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian sửa gần nhất', 
CHANGE `pubTime` `pubtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian xuất bản', 
CHANGE `expTime` `exptime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian hết hạn', 
CHANGE `expMode` `expmode` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Kiểu xử lý tự động khi hết hạn: 0: Ngưng hoạt động, 1: Cho thành hết hạn, 2: Xóa';

ALTER TABLE `nv3_vi_blog_send` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_send` 
CHANGE `startTime` `starttime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian bắt đầu gửi', 
CHANGE `endTime` `endtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian kết thúc gửi', 
CHANGE `lastID` `lastid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'ID email đăng ký nhận tin lần cuối cùng gửi', 
CHANGE `resendData` `resenddata` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Danh sách ID đăng ký nhận tin chờ gửi lại', 
CHANGE `errorData` `errordata` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Danh sách ID đăng ký nhận tin gửi bị lỗi cho đến thời điểm hiện tại';

ALTER TABLE `nv3_vi_blog_tags` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_tags` 
CHANGE `title` `title` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', 
CHANGE `alias` `alias` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', 
CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm', 
CHANGE `description` `description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Mô tả cho máy chủ tìm kiếm', 
CHANGE `numPosts` `numposts` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số bài viết';

RENAME TABLE nv3_vi_blog_categories TO nv4_vi_blog_categories;
RENAME TABLE nv3_vi_blog_config TO nv4_vi_blog_config;
RENAME TABLE nv3_vi_blog_data_1 TO nv4_vi_blog_data_1;
RENAME TABLE nv3_vi_blog_newsletters TO nv4_vi_blog_newsletters;
RENAME TABLE nv3_vi_blog_rows TO nv4_vi_blog_rows;
RENAME TABLE nv3_vi_blog_send TO nv4_vi_blog_send;
RENAME TABLE nv3_vi_blog_tags TO nv4_vi_blog_tags;
```

> Chú ý: Các giá trị `nv3`, `vi`, `blog` thay lại thành giá trị đúng với site của bạn. `nv4` là tiếp đầu tố sẽ sử dụng ở site mới.

Sau khi chạy lệnh trên tiến hành xuất các bảng CSDL đã nâng cấp. Lúc xuất ra, chú ý chọn option `Add DROP TABLE / VIEW / PROCEDURE / FUNCTION / EVENT / TRIGGER statement`.

Tiếp theo tạo một file php như sau

```php
<?php

ini_set('memory_limit', '2048M');

function unfixdb($value)
{
    $value = preg_replace(array(
        "/(se)\-(lect)/i",
        "/(uni)\-(on)/i",
        "/(con)\-(cat)/i",
        "/(c)\-(har)/i",
        "/(out)\-(file)/i",
        "/(al)\-(ter)/i",
        "/(in)\-(sert)/i",
        "/(d)\-(rop)/i",
        "/(f)\-(rom)/i",
        "/(whe)\-(re)/i",
        "/(up)\-(date)/i",
        "/(de)\-(lete)/i",
        "/(cre)\-(ate)/i"), "$1$2", $value);
    return $value;
}

$contents = file_get_contents('mysql.sql');
$contents = unfixdb($contents);

file_put_contents('mysql_fixed.sql', $contents, LOCK_EX);

echo('OK');
```

Đổi tên CSDL đã xuất ra thành `mysql.sql`, đặt ngang hàng với file php sau đó chạy file php, khi được thông báo thành công thì sẽ có file `mysql_fixed.sql` được tạo ra.

## Bước 3: Cài mới module 4.1.02.

Tiến hành cài site mới, cài đặt mới module blog. 

> Chú ý: Tên module, ngôn ngữ tiếp đầu tố phải tương ứng với bước 2.

## Bước 4: Khôi phục CSDL và file upload cũ.

- Tiến hành import CSDL đã xuất ở bước 2 vào CSDL mới (File `mysql_fixed.sql`). 
- Xóa thư mục `uploads/blog` ở site mới copy thư mục đã backup ở bước 1 vào thay thế.

## Bước 5: Hoàn tất cập nhật

- Xóa cache hệ thống
- Thiết lập lại các blocks trên site mới.
