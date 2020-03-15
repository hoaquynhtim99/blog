{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<div role="alert" class="alert alert-primary alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="fas fa-info-circle"></i></div>
    <div class="message">{$LANG->get('cfgCommentNote')}</div>
</div>
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="commentPerPage">{$LANG->get('cfgCommentPerPage')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <input type="number" class="form-control form-control-sm" id="commentPerPage" name="commentPerPage" value="{$DATA.commentPerPage}" min="0" max="999">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="commentType">{$LANG->get('cfgCommentType')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="commentType" name="commentType">
                        {foreach from=$COMMENTTYPE key=key item=value}
                        <option value="{$value}"{if $value eq $DATA.commentType} selected="selected"{/if}>{$LANG->get("cfgCommentType_`$value`")}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div id="comment-facebook" class="comment-table">
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="commentFacebookColorscheme">{$LANG->get('cfgcommentFacebookColorscheme')}</label>
                    <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                        <select class="form-control form-control-sm" id="commentFacebookColorscheme" name="commentFacebookColorscheme">
                            {foreach from=$COLORSCHEME key=key item=value}
                            <option value="{$key}"{if $key eq $DATA.commentFacebookColorscheme} selected="selected"{/if}>{$value}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="form-group row py-0">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                    <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                        <label class="custom-control custom-checkbox custom-control-inline mb-1">
                            <input class="custom-control-input" type="checkbox" id="emailWhenComment" name="emailWhenComment" value="1"{if $DATA.emailWhenComment} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfgemailWhenComment')}</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="emailWhenCommentList">{$LANG->get('cfgemailWhenCommentList')}</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <textarea rows="4" class="form-control" name="emailWhenCommentList" id="emailWhenCommentList">{$DATA.emailWhenCommentList}</textarea>
                        <div class="form-text text-muted">{$LANG->get('cfgemailWhenCommentListNote')}</div>
                    </div>
                </div>
            </div>
            <div id="comment-disqus" class="comment-table">
                <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right" for="commentDisqusShortname">{$LANG->get('cfgcommentDisqusShortname')} <i class="text-danger">(*)</i></label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <input type="text" class="form-control form-control-sm" name="commentDisqusShortname" id="commentDisqusShortname" value="{$DATA.commentDisqusShortname}">
                    </div>
                </div>
            </div>
            <div id="comment-sys" class="comment-table">
                <div role="alert" class="alert alert-danger alert-dismissible my-2">
                    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
                    <div class="icon"><i class="far fa-times-circle"></i></div>
                    <div class="message">{$LANG->get('isDevelop')}</div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function() {
    function nv_control_comment_table() {
        var commentType = $('[name="commentType"]').val();
        var tableId = 'comment-' + commentType;
        $('.comment-table').hide();
        if (document.getElementById(tableId)) {
            $('#' + tableId).show();
        }
    }
    $('[name="commentType"]').change(function() {
        nv_control_comment_table();
    });
    nv_control_comment_table();
});
</script>
