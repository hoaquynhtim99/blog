/* *
 * @Project NUKEVIET BLOG 5.x
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2020 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Saturday, February 22, 2020 15:39:20 GMT+07:00
 */

// Global
var tmp, nv_timer;

// Alias
function get_alias(id, returnId, mode) {
    tmp = returnId;
    var title = strip_tags(document.getElementById(id).value);
    if (title != '') {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'mode=' + mode + '&get_alias=' + encodeURIComponent(title), function(res) {
            if (res != "") {
                document.getElementById(tmp).value = res;
            } else {
                document.getElementById(tmp).value = '';
            }
        });
    }
    return false;
}

// Thao tac tra ve
function nv_delete_result(res) {
    if (res == 'OK') {
        location.reload();
    } else {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}

function nv_change_status_result(res) {
    if (res != 'OK') {
        alert(nv_is_change_act_confirm[2]);
        location.reload();
    }
    return;
}

function nv_change_status_list_result(res) {
    if (res != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    location.reload();
    return;
}

function nv_chang_weight_result(res) {
    if (res != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    clearTimeout(nv_timer);
    location.reload();
    return;
}

function nv_delete_newsletters(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

// Thao tac voi danh muc tin
function nv_change_cat_status(id) {
    nv_settimeout_disable('change_status' + id, 4000);
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(), 'changestatus=1&id=' + id, function(res) {
        nv_change_status_result(res);
    });
    return;
}

function nv_change_cat_weight(id) {
    nv_settimeout_disable('weight' + id, 5000);
    var newpos = document.getElementById('weight' + id).options[document.getElementById('weight' + id).selectedIndex].value;
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(), 'changeweight=1&id=' + id + '&new=' + newpos, function(res) {
        nv_chang_weight_result(res);
    });
    return;
}

function nv_delete_cat(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

function nv_delete_tags(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

function nv_delete_post(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

// Cac chuc nang trong admin
var BL = {};

BL.data = {
    url: script_name,
    langval: nv_lang_variable,
    lang: nv_lang_data,
    nv: nv_name_variable,
    op: nv_fc_variable,
    name: nv_module_name,
    dbName: nv_module_name.toLowerCase().replace(/\-/g, '_')
};

BL.busy = false;

BL.tags = {
    listTagsID: "post-tags-list",
    mostTagsID: "post-tags-most",
    inputTagsID: "post-tags",
    typeTagsID: "post-tags-type",
    buttonTagsID: "post-tags-button",

    remove: function(e) {
        $(e).parent().remove();
        BL.tags.reset();
    },
    mostAdd: function(e) {
        BL.tags.add($(e).data('id'), $(e).data('title'));
    },
    add: function(id, value) {
        if ($('#' + BL.tags.listTagsID + ' li[data-id="' + id + '"]').length == 0) {
            $('#' + BL.tags.listTagsID).append('<li data-id="' + id + '" class="badge text-bg-secondary lh-base post-tags-most-item"><i class="fas fa-tag"></i> ' + value + ' <a href="#"><i class="fas fa-times text-danger"></i></a></li>');
            BL.tags.reset();
        }
    },
    reset: function() {
        var list = new Array();
        $("#" + BL.tags.listTagsID + " li").each(function() {
            list.push($(this).data("id"));
        });
        list = list.toString();
        $('#' + BL.tags.inputTagsID).val(list);
    },
    creat: function(tokend) {
        if (!BL.busy) {
            var tags = $('#' + BL.tags.typeTagsID).val();
            if (tags != '') {
                BL.busy = true;
                // Submit để tìm theo tag
                $.ajax({
                    url: BL.data.url,
                    type: "POST",
                    dataType: "json",
                    data: BL.data.langval + '=' + BL.data.lang + '&' + BL.data.nv + '=' + BL.data.name + '&' + BL.data.op + '=tags&searchTags=' + encodeURIComponent(tags),
                    success: function(data) {
                        BL.busy = false;
                        if (data.id) {
                            // Tìm thấy thì thêm vào
                            BL.tags.add(data.id, data.title);
                            $('#' + BL.tags.typeTagsID).val('');
                        } else {
                            // Tạo mới tag
                            BL.busy = true;
                            $.ajax({
                                url: BL.data.url,
                                type: "POST",
                                dataType: "json",
                                data: BL.data.langval + '=' + BL.data.lang + '&' + BL.data.nv + '=' + BL.data.name + '&' + BL.data.op + '=tags&tokend=' + tokend + '&quick=1&title=' + encodeURIComponent(tags),
                                success: function(data) {
                                    BL.busy = false;
                                    if (data.error) {
                                        alert(data.message);
                                    } else {
                                        BL.tags.add(data.id, data.title);
                                        $('#' + BL.tags.typeTagsID).val('');
                                    }
                                }
                            });
                        }
                    }
                });
            }
        }
    },
    init: function() {
        // Xóa tag
        $('#' + BL.tags.listTagsID).delegate("a", "click", function(e) {
            e.preventDefault();
            BL.tags.remove(this);
        });

        // Sắp xếp
        $('#' + BL.tags.listTagsID).sortable({
            cursor: "crosshair",
            placeholder: "badge badge-secondary post-tags-most-item post-tags-most-item-sort",
            forcePlaceholderSize: true,
            update: function() {
                BL.tags.reset();
            }
        });
        $('#' + BL.tags.listTagsID).disableSelection();

        // Ấn tag phổ biến để thêm vào danh sách tag
        $('#' + BL.tags.mostTagsID).delegate('[data-toggle="tagitem"]', 'click', function(e) {
            e.preventDefault();
            BL.tags.mostAdd(this);
        });

        // Autocomplete
        $('#' + BL.tags.typeTagsID).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: BL.data.url,
                    type: "POST",
                    dataType: "json",
                    data: BL.data.langval + '=' + BL.data.lang + '&' + BL.data.nv + '=' + BL.data.name + '&' + BL.data.op + '=tags&ajaxTags=' + encodeURIComponent(request.term),
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.label,
                                value: item.value
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                if (ui.item) {
                    BL.tags.add(ui.item.value, ui.item.label);
                }

                $('#' + BL.tags.typeTagsID).val('');
                return false;
            },
            focus: function() {
                return false;
            }
        });

        // Tạo tag
        $('#' + BL.tags.buttonTagsID).click(function() {
            BL.tags.creat($(this).data('tokend'));
        });

        // Khong submit form khi an enter tren o tim kiem tag
        $('#' + BL.tags.typeTagsID).keypress(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });
    },
};

BL.post = {
    IDform: 'post-form',
    IDdraftButton: '[data-toggle="savedraft"]',
    IDpost: 'post-id',

    draft: function(editor) {
        if (!BL.busy) {
            if (editor) {
                if (typeof CKEDITOR != "undefined") {
                    // CKEditor 4
                    $("textarea[name=bodyhtml]").val(CKEDITOR.instances[BL.data.dbName + '_bodyhtml'].getData());
                } else if (window.nveditor && window.nveditor[BL.data.dbName + '_bodyhtml']) {
                    // CKEditor 5
                    $("textarea[name=bodyhtml]").val(window.nveditor[BL.data.dbName + '_bodyhtml'].getData());
                }
            }

            var data = BL.data.langval + '=' + BL.data.lang + '&' + BL.data.nv + '=' + BL.data.name + '&' + BL.data.op + '=blog-content&draft=1&' + $('#' + BL.post.IDform).serialize();
            BL.busy = true;

            $.ajax({
                url: BL.data.url,
                type: "POST",
                dataType: "json",
                data: data,
                success: function(data) {
                    BL.busy = false;
                    if (!data.error) {
                        $('#' + BL.post.IDpost).val(data.id);
                    }
                    if (data.error) {
                        nvToast(data.message, 'error');
                    } else {
                        nvToast(data.message, 'success');
                    }
                }
            });
        }
    },
    init: function(editor) {
        $(BL.post.IDdraftButton).click(function(e) {
            e.preventDefault();
            BL.post.draft(editor);
        });
    }
}

$(function() {
    // Select2 chung
    if ($('.select2').length) {
        $('.select2').select2({
            width: '100%'
        });
    }

    // Thanh cuộn
    if ($('.bl-scroller').length) {
        new PerfectScrollbar($('.bl-scroller')[0], {
            wheelPropagation: true
        });
    }

    // Chọn ngày tháng
    if ($('.datepicker-get').length) {
        $('.datepicker-get').datepicker({
            dateFormat: nv_jsdate_get.replace('yyyy', 'yy'),
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showButtonPanel: true,
            showOn: 'focus',
            isRTL: $('html').attr('dir') == 'rtl'
        });
    }
    if ($('.datepicker-post').length) {
        $('.datepicker-post').datepicker({
            dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showButtonPanel: true,
            showOn: 'focus',
            isRTL: $('html').attr('dir') == 'rtl'
        });
    }

    // Action tại trang danh sách bài viết
    $('[data-toggle="blAction"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);

        var items = $('[name="BLIdItem[]"]');
        if (!items.length) {
            return false;
        }
        var listid = [];
        items.each(function() {
            if ($(this).is(':checked')) {
                listid.push($(this).val());
            }
        });
        if (listid.length < 1) {
            nvToast($this.data('mgs'), 'warning');
            return;
        }

        if ($this.data('action') == 1) {
            // Xóa
            nvConfirm(nv_is_del_confirm[0], () => {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
                    nv_delete_result(res);
                });
            });
        } else if ($this.data('action') == 2) {
            // Đăng
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'changestatus=1&status=1&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        } else if ($this.data('action') == 3) {
            // Đình chỉ
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'changestatus=1&status=2&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        }
    });

    // Action tại trang tag
    $('[data-toggle="tagAction"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);

        var items = $('[name="BLIdItem[]"]');
        if (!items.length) {
            return false;
        }
        var listid = [];
        items.each(function() {
            if ($(this).is(':checked')) {
                listid.push($(this).val());
            }
        });
        if (listid.length < 1) {
            nvToast($this.data('mgs'), 'warning');
            return;
        }

        if ($this.data('action') == 1) {
            // Xóa
            nvConfirm(nv_is_del_confirm[0], () => {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
                    nv_delete_result(res);
                });
            });
        }
    });

    // Action tại trang danh sách email nhận tin
    $('[data-toggle="newslettersAction"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);

        var items = $('[name="BLIdItem[]"]');
        if (!items.length) {
            return false;
        }
        var listid = [];
        items.each(function() {
            if ($(this).is(':checked')) {
                listid.push($(this).val());
            }
        });
        if (listid.length < 1) {
            nvToast($this.data('mgs'), 'warning');
            return;
        }

        if ($this.data('action') == 1) {
            // Xóa
            nvConfirm(nv_is_del_confirm[0], () => {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
                    nv_delete_result(res);
                });
            });
        } else if ($this.data('action') == 2) {
            // Đăng
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'changestatus=1&status=1&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        } else if ($this.data('action') == 3) {
            // Đình chỉ
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'changestatus=1&status=2&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        }
    });

    // Trình soạn thảo
    /*
     * Kiểm tra chèn, gỡ ký tự chèn vào
     */
    function mozWrap(txtarea, open, close, command) {
        // Chỉnh lại line-end
        var textNew = txtarea.value.replace(/(?:\r\n|\r)/g, "\n");
        $(txtarea).val(textNew);

        var textLength = (typeof(txtarea.textLength) == 'undefined') ? txtarea.value.length : txtarea.textLength; // Số ký tự trong ô soạn thảo
        var selStart = realSelStart = txtarea.selectionStart; // Vị trí bắt đầu chọn
        var selEnd = realSelEnd = txtarea.selectionEnd; // Vị trí kết thúc chọn
        var scrollTop = txtarea.scrollTop; // Vị trí cuộn ô soạn thảo

        var isAutoRange = false;

        // Cho xuống hàng khi bôi đen tiêu đề, ghi chú
        if ((command == 'heading' || command == 'quote' || command == 'blist' || command == 'nlist') && selStart < selEnd) {
            open = "\n\n" + open;
            close = "\n\n";
        }

        if ((command == 'heading' || command == 'quote' || command == 'blist' || command == 'nlist') && selStart >= selEnd) {
            /*
             * Dạng chèn/bỏ chèn ở đầu đoạn văn
             * - Khi bôi đen thì tạo đoạn mới
             * - Khi không bôi đen thì tính tới đầu đoạn văn
             */

            /*
             * Đếm từ vị trí chọn đến đầu đoạn văn
             */
            if (selStart > 0) {
                for (var i = selStart - 1; i >= 0; i--) {
                    var character = (txtarea.value).substring(i, i + 1);
                    if (character == "\n") {
                        break;
                    }
                    realSelStart--;
                }
            }

            isAutoRange = true;
        } else {
            /*
             * Dạng chèn/bỏ chèn inline
             */

            /*
             * Khi bôi đen text thì tính toán lại vùng chọn trên tinh thần bỏ
             * các khoảng trắng đầu và cuối vùng chọn
             */
            if (selStart < selEnd) {
                for (var i = selStart; i < selEnd; i++) {
                    var character = (txtarea.value).substring(i, i + 1);
                    if (character == ' ' || character == "\n") {
                        realSelStart++;
                    } else {
                        break;
                    }
                }
                for (var i = selEnd; i > selStart; i--) {
                    var character = (txtarea.value).substring(i, i - 1);
                    if (character == ' ' || character == "\n") {
                        realSelEnd--;
                    } else {
                        break;
                    }
                }
                selStart = realSelStart;
                selEnd = realSelEnd;
                if (selStart > selEnd) {
                    selStart = selEnd;
                }
            }

            /*
             * Trong khoảng editor, tính ra vị trí bắt đầu, kết thúc thật
             * trên tinh thần từ vị trí trỏ chuột duyệt về trước, gặp khoảng trống thì kết thúc => Đánh dấu start
             * từ vị trí trỏ chuột duyệt về sau gặp khoảng trống thì kết thúc => Đánh dấu end
             *
             * Ngoài khoảng editor đơn giản thêm vào là được
             */
            if (selStart == selEnd && (selEnd + 1) < textLength) {
                for (var i = selStart - 1; i >= 0; i--) {
                    var character = (txtarea.value).substring(i, i + 1);
                    if (character == ' ' || character == "\n") {
                        break;
                    }
                    realSelStart = i;
                }
                for (var i = selEnd + 1; i < textLength; i++) {
                    var character = (txtarea.value).substring(i - 1, i);
                    if (character == ' ' || character == "\n") {
                        break;
                    }
                    realSelEnd = i;
                }
                isAutoRange = true;
            }
        }

        /*
         * Kiểm tra thêm vào hay loại ra
         * - Từ điểm start thực lấy lùi về sau xxx ký tự nếu bằng open thì đánh dấu +1
         * - Từ điểm end thực lấy về trước xxx ký tự nếu bằng close thì đánh dấu +1
         * Nếu =2 thì là xóa còn lại là cộng
         */
        var countRemoveCheck = 0;
        if (isAutoRange) {
            if ((realSelStart + open.length) <= textLength && ((txtarea.value).substring(realSelStart, realSelStart + open.length)) == open) {
                countRemoveCheck++;
            }
            if (close == '') {
                countRemoveCheck++;
            } else if ((realSelEnd - close.length) >= 0 && ((txtarea.value).substring(realSelEnd - close.length, realSelEnd)) == close) {
                countRemoveCheck++;
            }
        } else {
            if ((realSelStart - open.length) >= 0 && ((txtarea.value).substring(realSelStart - open.length, realSelStart)) == open) {
                countRemoveCheck++;
            }
            if ((realSelEnd + close.length) <= textLength && ((txtarea.value).substring(realSelEnd, realSelEnd + close.length)) == close) {
                countRemoveCheck++;
            }
        }

        /*
         * Tính toán lại vị trí con trỏ hoặc bôi đen text sau khi thêm vào, loại ra
         */
        if (countRemoveCheck >= 2) {
            // Loại ra
            if (isAutoRange) {
                // Loại khi auto tìm từ
                var s1 = (txtarea.value).substring(0, realSelStart);
                var s2 = (txtarea.value).substring(realSelStart + open.length, realSelEnd - close.length);
                var s3 = (txtarea.value).substring(realSelEnd, textLength);

                txtarea.value = s1 + s2 + s3;
                /*
                 * Sau khi loại ra nếu là link thì kiểm tra
                 * Trước đó trỏ chuột ở trong dòng url thì
                 */
                if (command == 'link' && selStart > (realSelEnd - (close.length + 1))) {
                    txtarea.selectionStart = realSelEnd - (close.length + 1);
                    txtarea.selectionEnd = realSelEnd - (close.length + 1);
                } else {
                    txtarea.selectionStart = selStart - open.length;
                    txtarea.selectionEnd = selEnd - open.length;
                }
            } else {
                /*
                 * Loại khi chủ động bôi đen từ
                 * - Chủ động bôi đen từ không loại cái thẻ link
                 */
                var s1 = (txtarea.value).substring(0, realSelStart - open.length);
                var s2 = (txtarea.value).substring(realSelStart, realSelEnd);
                var s3 = (txtarea.value).substring(realSelEnd + close.length, textLength);

                txtarea.value = s1 + s2 + s3;
                txtarea.selectionStart = realSelStart - open.length;
                txtarea.selectionEnd = realSelEnd - open.length;
            }
        } else {
            // Thêm vào
            var s1 = (txtarea.value).substring(0, realSelStart);
            var s2 = (txtarea.value).substring(realSelStart, realSelEnd);
            var s3 = (txtarea.value).substring(realSelEnd, textLength);

            txtarea.value = s1 + open + s2 + close + s3;

            if (command == 'link') {
                // Thêm link thì, bôi đen cái link
                txtarea.selectionStart = realSelEnd + 3;
                txtarea.selectionEnd = realSelEnd + 6;
            } else {
                // Giữ nguyên trỏ chuột, bôi đen text trước đó
                txtarea.selectionStart = selStart + open.length;
                txtarea.selectionEnd = selEnd + open.length;
            }
        }

        txtarea.focus();
        txtarea.scrollTop = scrollTop;
        $(txtarea).trigger('input');
    }

    var markdownEditor = $('[name="markdown_text"]');
    if (markdownEditor.length) {
        $('[data-toggle="mdicon"]').on('click', function(e) {
            e.preventDefault();

            var $this = $(this);
            var command = $this.data('command');

            // Xác định ký tự mở, đóng
            var openChar, closeChar = '';
            if (command == 'bold') {
                openChar = closeChar = '**'; // Đậm
            } else if (command == 'italic') {
                openChar = closeChar = '_'; // Nghiêng
            } else if (command == 'code') {
                openChar = closeChar = '`'; // Inline code
            } else if (command == 'heading') {
                openChar = '### '; // Tiêu đề cấp 3
                closeChar = '';
            } else if (command == 'quote') {
                openChar = '> '; // Ghi chú
                closeChar = '';
            } else if (command == 'link') {
                openChar = '['; // Ghi chú
                closeChar = '](url)';
            } else if (command == 'blist') {
                openChar = '- '; // Danh sách gạch đầu dòng
                closeChar = '';
            } else if (command == 'nlist') {
                openChar = '1. '; // Danh sách số thứ tự
                closeChar = '';
            }

            var textarea = markdownEditor[0];
            var clientPC = navigator.userAgent.toLowerCase();
            var is_ie = ((clientPC.indexOf('msie') != -1) && (clientPC.indexOf('opera') == -1));

            if (!isNaN(textarea.selectionStart) && !is_ie) {
                // Trên Chrome, Firefox, Opera
                mozWrap(textarea, openChar, closeChar, command);
            } else if (textarea.createTextRange && textarea.caretPos) {
                // FIXME tạm chưa hỗ trợ
            } else {
                // FIXME Trình duyệt khác
            }
        });
    }
});
