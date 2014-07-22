<!-- BEGIN: main -->
<div class="post-list">
	<!-- BEGIN: loop -->
	<div class="post-item">
		<!-- BEGIN: media -->
		<!-- BEGIN: image -->
		<img class="media-image" src="{ROW.mediaValue}" alt="{ROW.title}"/>
		<!-- END: image -->
		<!-- BEGIN: audio -->
		<div id="media-audio-{ROW.id}"></div>
		<script type="text/javascript">
		jwplayer("media-audio-{ROW.id}").setup({
			file: "{ROW.mediaValue}",
			height: {ROW.mediaHeight},
			image: "{ROW.images}",
			width: '100%',
			stretching: 'fill'
		});		
		</script>
		<!-- END: audio -->
		<!-- BEGIN: video -->
		<div id="media-video-{ROW.id}"></div>
		<script type="text/javascript">
		jwplayer("media-video-{ROW.id}").setup({
			file: "{ROW.mediaValue}",
			height: {ROW.mediaHeight},
			image: "{ROW.images}",
			width: '100%',
			stretching: 'fill'
		});		
		</script>
		<!-- END: video -->
		<!-- BEGIN: iframe -->
		<iframe class="media-iframe" src="{ROW.mediaValue}" height="{ROW.mediaHeight}"></iframe>
		<!-- END: iframe -->
		<!-- END: media -->
		<h3 class="post-title"><i class="{ROW.icon}">&nbsp;</i><a href="{ROW.link}">{ROW.title}</a> </h3>
		<p>
			{ROW.hometext}
		</p>
		<div class="meta">
		    <span><i class="icon-user mi">&nbsp;</i> {ROW.postName} </span>
		    <span><i class="icon-time mi">&nbsp;</i>{ROW.pubTime} </span>
		    <span><a href="{ROW.linkComment}"><i class="icon-comments-alt"></i> {ROW.numComments} {LANG.blNumComments}</a> </span>
		</div>
		<a class="tbutton small readmore" href="{ROW.link}"><span> {LANG.blDetail} </span></a>
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