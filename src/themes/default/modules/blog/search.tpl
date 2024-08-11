<!-- BEGIN: main -->
<!-- BEGIN: result -->
<p class="text-right">
    {RESULT_INFO}.
</p>
<!-- BEGIN: loop -->
<div class="post-list clearfix">
    <h3 class="post-title"><i class="{ROW.icon}"></i> <a href="{ROW.link}">{ROW.title}</a></h3>
    <p class="item-hometext">{ROW.hometext}</p>
    <div class="meta">
        <span><i class="fa fa-user"></i> {ROW.postName}</span>
        <span><i class="fa fa-clock-o"></i> {ROW.pubtime}</span>
        <span><a href="{ROW.linkComment}"><i class="fa fa-comments-o"></i> {ROW.numcomments} {LANG.blNumComments}</a></span>
    </div>
</div>
<hr />
<!-- END: loop -->
<div class="text-center">
    <div>{GLANG.page} {PAGE_CURRENT} {LANG.paginationInfo} {PAGE_TOTAL}</div>
    <!-- BEGIN: generate_page -->
    {GENERATE_PAGE}
    <!-- END: generate_page -->
</div>
<!-- END: result -->
<!-- BEGIN: noResult -->
{NORESULT_MESSAGE}
<!-- END: noResult -->
<!-- END: main -->
