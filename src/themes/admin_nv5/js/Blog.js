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

// Thao tac voi email dang ky nhan tin
function nv_newsletters_action(checkitem, nv_message_no_check, key) {
    var items = $(checkitem);
    if (!items.length) {
        return false;
    }
    var listid = [];
    items.each(function() {
        if ($(this).is(':checked')) {
            listid.push($(this).val());
        }
    });
    if (listid.length) {
        if (key == 1) {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
                    nv_delete_result(res);
                });
            }
        } else if (key == 2) {
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'changestatus=1&status=1&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        } else if (key == 3) {
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'changestatus=1&status=2&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        }
    } else {
        alert(nv_message_no_check);
    }
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
    var nv_timer = nv_settimeout_disable('change_status' + id, 4000);
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(), 'changestatus=1&id=' + id, function(res) {
        nv_change_status_result(res);
    });
    return;
}

function nv_change_cat_weight(id) {
    var nv_timer = nv_settimeout_disable('weight' + id, 5000);
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

// Thao tac voi tags
function nv_tags_action(checkitem, nv_message_no_check, key) {
    var items = $(checkitem);
    if (!items.length) {
        return false;
    }
    var listid = [];
    items.each(function() {
        if ($(this).is(':checked')) {
            listid.push($(this).val());
        }
    });
    if (listid.length) {
        if (key == 1) {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
                    nv_delete_result(res);
                });
            }
        }
    } else {
        alert(nv_message_no_check);
    }
}

function nv_delete_tags(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

// Thao tac voi bai viet
function nv_post_action(checkitem, nv_message_no_check, key) {
    var items = $(checkitem);
    if (!items.length) {
        return false;
    }
    var listid = [];
    items.each(function() {
        if ($(this).is(':checked')) {
            listid.push($(this).val());
        }
    });
    if (listid.length) {
        if (key == 1) {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
                    nv_delete_result(res);
                });
            }
        } else if (key == 2) {
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'changestatus=1&status=1&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        } else if (key == 3) {
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'changestatus=1&status=2&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        }
    } else {
        alert(nv_message_no_check);
    }
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
            $('#' + BL.tags.listTagsID).append('<li data-id="' + id + '" class="badge badge-secondary post-tags-most-item"><i class="fas fa-tag"></i> ' + value + ' <a href="#"><i class="fas fa-times"></i></a></li>');
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
            update: function(event, ui) {
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
                $("textarea[name=bodyhtml]").val(CKEDITOR.instances[BL.data.dbName + '_bodyhtml'].getData());
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
                        $.gritter.add({
                            title: data.title,
                            text: data.message,
                            class_name: "color danger"
                        });
                    } else {
                        $.gritter.add({
                            title: data.title,
                            text: data.message,
                            class_name: "color success"
                        });
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

$(document).ready(function() {
    // Check/bỏ check
    $('[data-toggle="BLCheckAll"]').on('change', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $items = $($this.data('target'));
        if ($this.is(':checked')) {
            $items.prop('checked', true);
        } else {
            $items.prop('checked', false);
        }
    });
    $('[data-toggle="BLUncheckAll"]').on('change', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $btnall = $($this.data('target'));
        var $items = $($btnall.data('target'));
        var $itemsChecked = $($btnall.data('target') + ':checked');
        if ($itemsChecked.length >= $items.length) {
            $btnall.prop('checked', true);
        } else {
            $btnall.prop('checked', false);
        }
    });
});
