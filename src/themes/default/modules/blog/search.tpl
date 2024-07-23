<!-- BEGIN: main -->
<!-- BEGIN: result -->
<p class="text-right">
	{RESULT_INFO}.
</p>
<!-- BEGIN: loop -->
<h3 class="upper"><i class="{ROW.icon}">&nbsp;</i><a href="{ROW.link}">{ROW.title}</a></h3>
<p>{ROW.hometext}</p>
<div class="post-list clearfix">
	<div class="meta">
	    <span><i class="icon-user mi">&nbsp;</i> {ROW.postName} </span>
	    <span><i class="icon-time mi">&nbsp;</i>{ROW.pubtime} </span>
	    <span><a href="{ROW.linkComment}"><i class="icon-comments-alt"></i> {ROW.numcomments} {LANG.blNumComments}</a> </span>
	</div>
</div>
<hr />
<!-- END: loop -->
<div class="pagination-tt clearfix">
	<!-- BEGIN: generate_page -->
	{GENERATE_PAGE}
    <!-- END: generate_page -->
    <span class="pages">{GLANG.page} {PAGE_CURRENT} {LANG.paginationInfo} {PAGE_TOTAL}</span>
</div>
<!-- END: result -->
<!-- BEGIN: noResult -->
{NORESULT_MESSAGE}
<!-- END: noResult -->
<!-- END: main -->