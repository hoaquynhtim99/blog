/* *
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

// Global
var tmp, nv_timer;

// Alias
function get_alias(id, returnId, mode) {
    tmp = returnId;
    var title = strip_tags(document.getElementById(id).value);
    if (title != '') {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'mode=' + mode + '&get_alias=' + encodeURIComponent(title), function(res) {
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
        window.location.href = window.location.href;
    } else {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}

function nv_change_status_result(res) {
    if (res != 'OK') {
        alert(nv_is_change_act_confirm[2]);
        window.location.href = window.location.href;
    }
    return;
}

function nv_change_status_list_result(res) {
    if (res != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    window.location.href = window.location.href;
    return;
}

function nv_chang_weight_result(res) {
    if (res != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    clearTimeout(nv_timer);
    window.location.href = window.location.href;
    return;
}

// Thao tac voi email dang ky nhan tin
function nv_newsletters_action(oForm, nv_message_no_check, key) {
    var fa = oForm['idcheck[]'];
    var listid = [];

    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid.push(fa[i].value);
            }
        }
    } else {
        if (fa.checked) {
            listid.push(fa.value);
        }
    }

    if (listid != '') {
        if (key == 1) {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
                    nv_delete_result(res);
                });
            }
        } else if (key == 2) {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'changestatus=1&status=1&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        } else if (key == 3) {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'changestatus=1&status=2&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        }
    } else {
        alert(nv_message_no_check);
    }
}

function nv_delete_newsletters(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=newsletter-manager&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

// Thao tac voi danh muc tin
function nv_change_cat_status(id) {
    var nv_timer = nv_settimeout_disable('change_status' + id, 4000);
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(), 'changestatus=1&id=' + id, function(res) {
        nv_change_status_result(res);
    });
    return;
}

function nv_change_cat_weight(id) {
    var nv_timer = nv_settimeout_disable('weight' + id, 5000);
    var newpos = document.getElementById('weight' + id).options[document.getElementById('weight' + id).selectedIndex].value;
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(), 'changeweight=1&id=' + id + '&new=' + newpos, function(res) {
        nv_change_status_result(res);
    });
    return;
}

function nv_delete_cat(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

// Thao tac voi tags
function nv_tags_action(oForm, nv_message_no_check, key) {
    var fa = oForm['idcheck[]'];
    var listid = [];

    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid.push(fa[i].value);
            }
        }
    } else {
        if (fa.checked) {
            listid.push(fa.value);
        }
    }

    if (listid != '') {
        if (key == 1) {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
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
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

// Thao tac voi bai viet
function nv_post_action(oForm, nv_message_no_check, key) {
    var fa = oForm['idcheck[]'];
    var listid = [];

    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid.push(fa[i].value);
            }
        }
    } else {
        if (fa.checked) {
            listid.push(fa.value);
        }
    }

    if (listid != '') {
        if (key == 1) {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'del=1&listid=' + listid, function(res) {
                    nv_delete_result(res);
                });
            }
        } else if (key == 2) {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'changestatus=1&status=1&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        } else if (key == 3) {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'changestatus=1&status=2&listid=' + listid, function(res) {
                nv_change_status_list_result(res);
            });
        }
    } else {
        alert(nv_message_no_check);
    }
}

function nv_delete_post(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-list&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            nv_delete_result(res);
        });
    }
    return false;
}

// Cac chuc nang trong admin
var BL = {};

BL.data = {
    url: script_name,
    nv: nv_name_variable,
    op: nv_fc_variable,
    name: nv_module_name,
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
        BL.tags.add($(e).attr('rel'), $(e).html());
    },
    add: function(id, value) {
        if ($('#' + BL.tags.listTagsID + ' li[rel="' + id + '"]').length == 0) {
            $('#' + BL.tags.listTagsID).append('<li rel="' + id + '">' + value + '<span>&nbsp;</span></li>');
            BL.tags.reset();
        }
    },
    reset: function() {
        var list = new Array();
        $("#" + BL.tags.listTagsID + " li").each(function() {
            list.push($(this).attr("rel"));
        });
        list = list.toString();
        $('#' + BL.tags.inputTagsID).val(list);
    },
    creat: function() {
        if (!BL.busy) {
            var tags = $('#' + BL.tags.typeTagsID).val();

            if (tags != '') {
                BL.busy = true;
                $.ajax({
                    url: BL.data.url,
                    type: "POST",
                    dataType: "json",
                    data: BL.data.nv + '=' + BL.data.name + '&' + BL.data.op + '=tags&searchTags=' + encodeURIComponent(tags),
                    success: function(data) {
                        BL.busy = false;
                        if (data.id) {
                            BL.tags.add(data.id, data.title);
                            $('#' + BL.tags.typeTagsID).val('');
                        } else {
                            BL.busy = true;
                            $.ajax({
                                url: BL.data.url,
                                type: "POST",
                                dataType: "json",
                                data: BL.data.nv + '=' + BL.data.name + '&' + BL.data.op + '=tags&submit=1&quick=1&title=' + encodeURIComponent(tags),
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
        // Xoa tags
        $('#' + BL.tags.listTagsID).delegate("span", "click", function() {
            BL.tags.remove(this);
        });

        // Sap xep
        $('#' + BL.tags.listTagsID).sortable({
            cursor: "crosshair",
            update: function(event, ui) {
                BL.tags.reset();
            }
        });

        // Them tags pho bien
        $('#' + BL.tags.mostTagsID).delegate("li", "click", function() {
            BL.tags.mostAdd(this);
        });

        // Autocomplete
        $('#' + BL.tags.typeTagsID).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: BL.data.url,
                    type: "POST",
                    dataType: "json",
                    data: BL.data.nv + '=' + BL.data.name + '&' + BL.data.op + '=tags&ajaxTags=' + encodeURIComponent(request.term),
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

        // Tao tags
        $('#' + BL.tags.buttonTagsID).click(function() {
            BL.tags.creat();
        });

        // Khong submit form khi an enter tren o tim kiem tag
        $('#' + BL.tags.typeTagsID).keypress(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    },
};

BL.post = {
    IDform: 'post-form',
    IDdraftButton: 'button-draft',
    IDpublicButton: 'button-public',
    IDpost: 'post-id',
    IDmessage: 'post-message',

    draft: function(editor) {
        if (!BL.busy) {
            if (editor) {
                $("textarea[name=bodyhtml]").val(CKEDITOR.instances.bodyhtml.getData());
            }

            var data = BL.data.nv + '=' + BL.data.name + '&' + BL.data.op + '=blog-content&draft=1&' + $('#' + BL.post.IDform).serialize();
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
                    BL.post.showLog(data.message, data.error);
                }
            });
        }
    },
    showLog: function(msg, stus) {
        $('#' + BL.post.IDmessage).css({
            opacity: 1
        }).html(msg);
        setTimeout("BL.post.hideLog()", 3000);
    },
    hideLog: function() {
        $('#' + BL.post.IDmessage).animate({
            opacity: 0,
        }, 500);
    },
    init: function(editor) {
        $('#' + BL.post.IDdraftButton).click(function() {
            BL.post.draft(editor);
            return false;
        });
    }
}
