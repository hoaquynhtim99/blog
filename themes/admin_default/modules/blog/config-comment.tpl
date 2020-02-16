<!-- BEGIN: main -->
<div class="alert alert-warning">{LANG.cfgCommentNote}</div>
<form method="post" action="{FORM_ACTION}">
    <div class="panel panel-default">
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="commentPerPage">
                    {LANG.cfgCommentPerPage} <span class="text-danger">(*)</span>:
                </label>
                <div class="col-sm-16 col-md-10 col-lg-9">
                    <input type="text" class="form-control" id="commentPerPage" name="commentPerPage" value="{DATA.commentPerPage}" />
                </div>
            </div>
            <div class="row">
                <label class="col-sm-8 col-md-6 control-label" for="commentType">
                    {LANG.cfgCommentType} <span class="text-danger">(*)</span>:
                </label>
                <div class="col-sm-16 col-md-10 col-lg-9">
                    <div class="form-inline">
                        <select name="commentType" id="commentType" class="form-control">
                            <!-- BEGIN: commentType -->
                            <option value="{COMMENTTYPE.key}"{COMMENTTYPE.selected}>{COMMENTTYPE.title}</option>
                            <!-- END: commentType -->
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default comment-table" id="comment-facebook">
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="commentFacebookColorscheme">
                    {LANG.cfgcommentFacebookColorscheme} <span class="text-danger">(*)</span>:
                </label>
                <div class="col-sm-16 col-md-10 col-lg-9">
                    <div class="form-inline">
                        <select name="commentFacebookColorscheme" id="commentFacebookColorscheme" class="form-control">
                            <!-- BEGIN: commentFacebookColorscheme -->
                            <option value="{COMMENTFACEBOOKCOLORSCHEME.key}"{COMMENTFACEBOOKCOLORSCHEME.selected}>{COMMENTFACEBOOKCOLORSCHEME.title}</option>
                            <!-- END: commentFacebookColorscheme -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-16 col-md-10 col-lg-9 col-sm-offset-8 col-md-offset-6">
                    <div class="checkbox">
                        <label><input type="checkbox" name="emailWhenComment" id="emailWhenComment" value="1"{EMAILWHENCOMMENT}> {LANG.cfgemailWhenComment}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-8 col-md-6 control-label" for="emailWhenCommentList">
                    {LANG.cfgemailWhenCommentList}:
                </label>
                <div class="col-sm-16 col-md-10 col-lg-9">
                    <textarea rows="4" class="form-control" name="emailWhenCommentList" id="emailWhenCommentList">{DATA.emailWhenCommentList}</textarea>
                    <div class="form-text text-muted">{LANG.cfgemailWhenCommentListNote}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default comment-table" id="comment-disqus">
        <div class="panel-body form-horizontal">
            <div class="row">
                <label class="col-sm-8 col-md-6 control-label" for="commentFacebookColorscheme">
                    {LANG.cfgcommentDisqusShortname} <span class="text-danger">(*)</span>:
                </label>
                <div class="col-sm-16 col-md-10 col-lg-9">
                    <input type="text" class="form-control" name="commentDisqusShortname" id="commentDisqusShortname" value="{DATA.commentDisqusShortname}">
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-danger comment-table" id="comment-sys">{LANG.isDevelop}</div>
    <div class="text-center">
        <input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}" />
    </div>
</form>
<script type="text/javascript">
function nv_control_comment_table() {
    var commentType = $('[name="commentType"]').val();
    var tableId = 'comment-' + commentType;
    $('.comment-table').hide();
    if (document.getElementById(tableId)) {
        $('#' + tableId).show();
    }
}

$(function() {
    $('[name="commentType"]').change(function() {
        nv_control_comment_table();
    });
    nv_control_comment_table();
});
</script>
<!-- END: main -->
