/* *
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

var BL = {
    siteroot: nv_base_siteurl,
    sitelang: nv_lang_data,
    name_variable: nv_name_variable,
    module_name: nv_module_name,
    op_variable: nv_fc_variable,
    callApi: function(q) {
        $.ajax({
            type: 'POST',
            url: BL.siteroot + 'index.php',
            data: BL.lang_variable + '=' + BL.sitelang + '&' + BL.name_variable + '=' + BL.module_name + '&' + BL.op_variable + '=api&' + q,
            success: function(e) {}
        });
    },
    addCommentOnly: function(blog_id, tokend, fbCommentID) {
        BL.callApi('addCommentOnly=1&id=' + blog_id + '&tokend=' + tokend + '&fbcmtid=' + fbCommentID);
    },
    delCommentOnly: function(blog_id, tokend, fbCommentID) {
        BL.callApi('delCommentOnly=1&id=' + blog_id + '&tokend=' + tokend + '&fbcmtid=' + fbCommentID);
    },
};

$(document).ready(function() {
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
    });
    // Xử lý block newsletter
    $('.bl-newsletters').each(function() {
        $('form', $(this)).submit(function(e) {
            e.preventDefault();

            var $this = $(this);
            var emailElement = $('[type="email"]', $this);
            var moduleName = $this.data('module');
            var checksess = $this.data('checksess');
            var submitBtn = $('[type="submit"] i', $this);

            if ($this.data('busy')) {
                return false;
            }

            $this.data('busy', true);

            submitBtn.removeClass('fa-check-circle');
            submitBtn.addClass('fa-spinner');
            submitBtn.addClass('fa-pulse');

            $.ajax({
                type: 'POST',
                url: nv_base_siteurl + 'index.php',
                data: nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + moduleName + '&' + nv_fc_variable + '=newsletters&newsletters=' + encodeURIComponent(emailElement.val()) + '&checksess=' + checksess,
                success: function(data) {
                    emailElement.val('');
                    modalShow('', data);
                    $this.data('busy', false);
                    submitBtn.removeClass('fa-spinner');
                    submitBtn.removeClass('fa-pulse');
                    submitBtn.addClass('fa-check-circle');
                }
            });
        });
    });
    // Xử lý list tags
    $('.bl-tags-list').each(function() {
        $(this).html($(this).html() + '.................................................................................................................................................................................................................................');
    });
    // Xử lý responsive iframe, ảnh
    var postiframescaleTimer = null;
    var postimagescaleTimer = null;
    var postdataiframescaleTimer = null;
    $(window).on('resize', function() {
        // Chỉnh Iframe đầu bài viết
        if (postiframescaleTimer) {
            clearTimeout(postiframescaleTimer);
        }
        postiframescaleTimer = setTimeout(function() {
            blog_scaleIframe();
        }, 50);
        // Chỉnh ảnh nội dung bài viết
        if (postimagescaleTimer) {
            clearTimeout(postimagescaleTimer);
        }
        postimagescaleTimer = setTimeout(function() {
            blog_scalePostImage(true);
        }, 50);
        // Chỉnh ảnh nội dung bài viết
        if (postdataiframescaleTimer) {
            clearTimeout(postdataiframescaleTimer);
        }
        postdataiframescaleTimer = setTimeout(function() {
            blog_scalePostIframe();
        }, 50);
    });
    // Iframe fix cố định chiều cao do đó xử lý khi ready
    blog_scaleIframe();
    blog_scalePostImage(true);
    blog_scalePostIframe();
});

$(window).on('load', function() {
    // Ảnh xử lý khi load trang xong
    blog_scalePostImage(false);
});

function blog_scaleIframe() {
    $('[data-toggle="postiframescale"]').each(function() {
        $(this).height($(this).width() * $(this).data('h') / $(this).data('w'));
    });
}

function blog_scalePostImage(preLoad) {
    if ($('.post-detail-content').length == 1) {
        $('img', $('.post-detail-content')).each(function() {
            var $this = $(this);
            var defW = $this.data('w');
            var defH = $this.data('h');
            var ctnW = $('.post-detail-content').width();

            // Xử lý data ảnh khi chưa có thiết lập rộng và cao
            if (!defW || !defH) {
                // Khi preLoad mà ảnh không có width và height thì kết thúc
                var setW = $this.attr('width');
                var setH = $this.attr('height');
                if (!setW || !setH) {
                    if (preLoad) {
                        return false;
                    }
                    // Lấy kích thước thật
                    defW = $this.width();
                    defH = $this.height();
                } else {
                    defW = setW;
                    defH = setH;
                }
                $this.data('w', defW);
                $this.data('h', defH);
            }

            // Sau khi có rộng cao thì co ảnh
            if (defW && defH) {
                var w = defW;
                var h = defH;
                if (w > ctnW) {
                    w = ctnW;
                    h = (ctnW * defH / defW);
                }
                $this.css({
                    'width' : w,
                    'height' : h
                });
                $this.removeAttr('width');
                $this.removeAttr('height');
            }
        });
    }
}

function blog_scalePostIframe() {
    if ($('.post-detail-content').length == 1) {
        $('iframe', $('.post-detail-content')).each(function() {
            var $this = $(this);
            var defW = $this.data('w');
            var defH = $this.data('h');
            var ctnW = $('.post-detail-content').width();

            // Xử lý data iframe khi chưa có thiết lập rộng và cao
            if (!defW || !defH) {
                defW = $this.width();
                defH = $this.height();
                $this.data('w', defW);
                $this.data('h', defH);
            }

            // Sau khi có rộng cao thì co iframe
            if (defW && defH) {
                var w = defW;
                var h = defH;
                if (w > ctnW) {
                    w = ctnW;
                    h = (ctnW * defH / defW);
                }
                $this.css({
                    'width' : w,
                    'height' : h
                });
                $this.removeAttr('width');
                $this.removeAttr('height');
            }
        });
    }
}
