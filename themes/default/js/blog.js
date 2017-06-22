/* *
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
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
    addCommentOnly: function(blog_id) {
        BL.callApi('addCommentOnly=1&id=' + blog_id)
    },
    delCommentOnly: function(blog_id) {
        BL.callApi('delCommentOnly=1&id=' + blog_id);
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
    // Xử lý responsive iframe
    var postiframescaleTimer = null;
    $(window).on('resize', function() {
        if (postiframescaleTimer) {
            clearTimeout(postiframescaleTimer);
        }
        postiframescaleTimer = setTimeout(function() {
            blog_scaleIframe();
        }, 100);
    });
    blog_scaleIframe();
});

function blog_scaleIframe() {
    $('[data-toggle="postiframescale"]').each(function() {
        $(this).height($(this).width() * $(this).data('h') / $(this).data('w'));
    });
}
