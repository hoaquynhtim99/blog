# Hướng dẫn nâng cấp module blog từ bản 3.4.01, 4.0.01 lên 4.1.00

> Chú ý: Hệ thống phiên bản 3x lên 4x có rất nhiều thay đổi nên tại đây hướng dẫn các bạn nâng cấp module với hình thức thủ công. Hướng dẫn chỉ dành cho những bạn đã có kỹ năng cơ bản về MySQL, NukeViet. Các bạn mới sử dụng hoặc chưa am hiểu có thể nhờ bạn bè, tổ chức, các nhân khác giúp đỡ.

## Bước 1: Sao lưu dữ liệu cũ.

## Ghi nhật ký câu lệnh chạy của CSDL

```sql
ALTER TABLE `nv3_vi_blog_categories` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_categories` 
CHANGE `title` `title` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', 
CHANGE `alias` `alias` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', 
CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm', 
CHANGE `description` `description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Mô tả cho máy chủ tìm kiếm',
CHANGE `numSubs` `numsubs` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số danh mục con', 
CHANGE `numPosts` `numposts` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số bài viết';
```

```sql
ALTER TABLE `nv3_vi_blog_config` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_config` 
CHANGE `config_name` `config_name` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, 
CHANGE `config_value` `config_value` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
```

```sql
ALTER TABLE `nv3_vi_blog_data_1` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_data_1` CHANGE `bodyhtml` `bodyhtml` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
```

```sql
ALTER TABLE `nv3_vi_blog_newsletters` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_newsletters` 
CHANGE `email` `email` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Email đăng ký', 
CHANGE `regIP` `regip` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP đã đăng ký', 
CHANGE `regTime` `regtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian đăng ký', 
CHANGE `confirmTime` `confirmtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian xác nhận', 
CHANGE `lastSendTime` `lastsendtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Lần gửi cuối', 
CHANGE `tokenKey` `tokenkey` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Khóa xác nhận', 
CHANGE `numEmail` `numemail` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số email đã gửi';
```

```sql
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
```

```sql
ALTER TABLE `nv3_vi_blog_send` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_send` 
CHANGE `startTime` `starttime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian bắt đầu gửi', 
CHANGE `endTime` `endtime` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian kết thúc gửi', 
CHANGE `lastID` `lastid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'ID email đăng ký nhận tin lần cuối cùng gửi', 
CHANGE `resendData` `resenddata` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Danh sách ID đăng ký nhận tin chờ gửi lại', 
CHANGE `errorData` `errordata` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Danh sách ID đăng ký nhận tin gửi bị lỗi cho đến thời điểm hiện tại';
```

```sql
ALTER TABLE `nv3_vi_blog_tags` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `nv3_vi_blog_tags` 
CHANGE `title` `title` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', 
CHANGE `alias` `alias` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', 
CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm', 
CHANGE `description` `description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Mô tả cho máy chủ tìm kiếm', 
CHANGE `numPosts` `numposts` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Số bài viết';
```
