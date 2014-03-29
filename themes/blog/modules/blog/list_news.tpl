<!-- BEGIN: main -->
<div class="news-list">
	<!-- BEGIN: loop -->
	<div class="news-item">
		<div class="news-image">
			<a href="{ROW.link}"><img src="{ROW.images}" alt="{ROW.title}"/></a>
		</div>
		<div class="news-content">
			<h3 class="news-title"><i class="icon-play">&nbsp;</i><a href="{ROW.link}">{ROW.title}</a> </h3>
			<p>
				{ROW.hometext}
			</p>
			<div class="meta">
			    <span><i class="icon-time mi">&nbsp;</i>{ROW.pubTime} </span>
			    <span><a href="{ROW.linkComment}"><i class="icon-comments-alt"></i> {ROW.numComments} {LANG.blNumComments}</a> </span>
			</div>
			<a class="tbutton small readmore" href="{ROW.link}"><span> {LANG.blDetail} </span></a>
		</div>
		<div class="ms4-clear">&nbsp;</div>
	</div>
	<!-- END: loop -->
</div>
<div class="pagination-tt clearfix">
	<!-- BEGIN: generate_page -->
	{GENERATE_PAGE}
    <!-- END: generate_page -->
    <span class="pages">{GLANG.page} {PAGE_CURRENT} {LANG.paginationInfo} {PAGE_TOTAL}</span>
</div>
<!-- END: main -->