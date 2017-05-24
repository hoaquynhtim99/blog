# Hướng dẫn nâng cấp module blog từ bản 3.4.01, 4.0.01 lên 4.1.00

> Chú ý: Hệ thống phiên bản 3x lên 4x có rất nhiều thay đổi nên tại đây hướng dẫn các bạn nâng cấp module với hình thức thủ công. Hướng dẫn chỉ dành cho những bạn đã có kỹ năng cơ bản về MySQL, NukeViet. Các bạn mới sử dụng hoặc chưa am hiểu có thể nhờ bạn bè, tổ chức, các nhân khác giúp đỡ.

## Bước 1: Sao lưu dữ liệu cũ.

```sql
ALTER TABLE  `nv4_vi_blog_categories` CHANGE  `numSubs`  `numsubs` SMALLINT( 4 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  'Số danh mục con';
ALTER TABLE  `nv4_vi_blog_categories` CHANGE  `numPosts`  `numposts` SMALLINT( 4 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  'Số bài viết';
```

```sql
ALTER TABLE  `nv4_vi_blog_newsletters` CHANGE  `regIP`  `regip` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '' COMMENT  'IP đã đăng ký';
ALTER TABLE  `nv4_vi_blog_newsletters` CHANGE  `regTime`  `regtime` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  'Thời gian đăng ký';
ALTER TABLE  `nv4_vi_blog_newsletters` CHANGE  `confirmTime`  `confirmtime` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  'Thời gian xác nhận';
ALTER TABLE  `nv4_vi_blog_newsletters` CHANGE  `lastSendTime`  `lastsendtime` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  'Lần gửi cuối';
ALTER TABLE  `nv4_vi_blog_newsletters` CHANGE  `tokenKey`  `tokenkey` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '' COMMENT  'Khóa xác nhận';
ALTER TABLE  `nv4_vi_blog_newsletters` CHANGE  `numEmail`  `numemail` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  'Số email đã gửi';
```
