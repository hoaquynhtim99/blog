{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$ERROR}
</div>
{/if}
<div role="alert" class="alert alert-primary">
    <i class="fas fa-info-circle"></i> {$LANG->get('cfgCommentNote')}
</div>
<div class="card">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="commentPerPage">{$LANG->get('cfgCommentPerPage')}</label>
                <div class="col-12 col-sm-8 col-lg-3">
                    <input type="number" class="form-control" id="commentPerPage" name="commentPerPage" value="{$DATA.commentPerPage}" min="0" max="999">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="commentType">{$LANG->get('cfgCommentType')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-select" id="commentType" name="commentType">
                        {foreach from=$COMMENTTYPE key=key item=value}
                        <option value="{$value}"{if $value eq $DATA.commentType} selected="selected"{/if}>{$LANG->get("cfgCommentType_`$value`")}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div id="comment-facebook" class="comment-table">
                <div class="row mb-3">
                    <label class="col-12 col-sm-3 col-form-label text-sm-end" for="commentFacebookColorscheme">{$LANG->get('cfgcommentFacebookColorscheme')}</label>
                    <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                        <select class="form-select" id="commentFacebookColorscheme" name="commentFacebookColorscheme">
                            {foreach from=$COLORSCHEME key=key item=value}
                            <option value="{$key}"{if $key eq $DATA.commentFacebookColorscheme} selected="selected"{/if}>{$value}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-12 col-sm-3 col-form-label text-sm-end d-none d-sm-block"></label>
                    <div class="col-12 col-sm-8 col-lg-6 mt-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailWhenComment" name="emailWhenComment" value="1"{if $DATA.emailWhenComment} checked="checked"{/if}>
                            <label for="emailWhenComment" class="form-check-label">{$LANG->get('cfgemailWhenComment')}</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-12 col-sm-3 col-form-label text-sm-end" for="emailWhenCommentList">{$LANG->get('cfgemailWhenCommentList')}</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <textarea rows="4" class="form-control" name="emailWhenCommentList" id="emailWhenCommentList">{$DATA.emailWhenCommentList}</textarea>
                        <div class="form-text text-muted">{$LANG->get('cfgemailWhenCommentListNote')}</div>
                    </div>
                </div>
            </div>
            <div id="comment-disqus" class="comment-table">
                <div class="row mb-3">
                    <label class="col-12 col-sm-3 col-form-label text-sm-end" for="commentDisqusShortname">{$LANG->get('cfgcommentDisqusShortname')} <i class="text-danger">(*)</i></label>
                    <div class="col-12 col-sm-8 col-lg-6">
                        <input type="text" class="form-control" name="commentDisqusShortname" id="commentDisqusShortname" value="{$DATA.commentDisqusShortname}">
                    </div>
                </div>
            </div>
            <div id="comment-sys" class="comment-table">
                <div role="alert" class="alert alert-danger my-3">
                    <i class="far fa-times-circle"></i> {$LANG->get('isDevelop')}
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
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
