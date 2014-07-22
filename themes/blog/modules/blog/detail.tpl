<!-- BEGIN: main -->
<!-- BEGIN: media -->
<div class="post-detail-media-data">
	<!-- BEGIN: image -->
	<img class="media-image" src="{DATA.mediaValue}" alt="{DATA.title}"/>
	<!-- END: image -->
	<!-- BEGIN: audio -->
	<div id="media-audio-{DATA.id}"></div>
	<script type="text/javascript">
	jwplayer("media-audio-{DATA.id}").setup({
		file: "{DATA.mediaValue}",
		height: {DATA.mediaHeight},
		image: "{DATA.images}",
		width: '100%'
	});		
	</script>
	<!-- END: audio -->
	<!-- BEGIN: video -->
	<div id="media-video-{DATA.id}"></div>
	<script type="text/javascript">
	jwplayer("media-video-{DATA.id}").setup({
		file: "{DATA.mediaValue}",
		height: {DATA.mediaHeight},
		image: "{DATA.images}",
		width: '100%',
		stretching: 'fill'
	});		
	</script>
	<!-- END: video -->
	<!-- BEGIN: iframe -->
	<iframe class="media-iframe" src="{DATA.mediaValue}" height="{DATA.mediaHeight}"></iframe>
	<!-- END: iframe -->
</div>
<!-- END: media -->
<h1 class="upper"><i class="{DATA.icon}">&nbsp;</i> {DATA.title}</h1>
<div class="post-detail-content">
	{DATA.bodyhtml}
</div>
<!-- BEGIN: tags -->
<div class="post-detail-tags">
	{LANG.tags}:
	<!-- BEGIN: loop -->
	<a href="{TAG.link}">{TAG.title}</a>
	<!-- END: loop -->
</div>
<!-- END: tags -->
<div class="clearfix">
	<div class="post-detail-meta">
	    <span><i class="icon-user mi">&nbsp;</i> {DATA.postName} </span>
	    <span><i class="icon-time mi">&nbsp;</i>{DATA.pubTime} </span>
	    <span><a href="#comment"><i class="icon-comments-alt mi">&nbsp;</i> {DATA.numComments} {LANG.blNumComments}</a> </span>
	</div>
	<div class="fr">
		<div class="addthis_sharing_toolbox"></div>
		<!--script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=hoaquynhtim99"></script-->
	</div>
	<div class="fr">{LANG.share}: </div>
</div>
<hr />
<!-- BEGIN: navPost -->
<!-- BEGIN: nextPost -->
<div class="post-detail-next-post">
	<a href="{DATA.nextPost.link}"><i class="icon-long-arrow-right"></i> {DATA.nextPost.title}</a>
</div>
<!-- END: nextPost -->
<!-- BEGIN: prevPost -->
<div class="post-detail-prev-post">
	<a href="{DATA.prevPost.link}"><i class="icon-long-arrow-left"></i> {DATA.prevPost.title}</a>
</div>
<!-- END: prevPost -->
<div class="clearfix"></div>
<hr />
<!-- END: navPost -->
<!-- END: main -->