<!-- BEGIN: main -->
<div class="post-detail" itemscope itemtype="http://schema.org/Article">
	<!-- BEGIN: media -->
	<div class="post-detail-media-data">
		<!-- BEGIN: image -->
		<img itemprop="image" class="media-image" src="{DATA.mediaValue}" alt="{DATA.title}"/>
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
	<h1 class="upper"><i class="{DATA.icon}">&nbsp;</i> <span itemprop="name">{DATA.title}</span></h1>
	<div class="post-detail-share clearfix">
		<div class="fr">
			<div class="g-plusone" data-size="tall" data-annotation="inline" data-width="300" data-href="{DATA.href}"></div>
			<script type="text/javascript">
			  window.___gcfg = {lang: '{NV_LANG_DATA}'};
			  (function() {
			    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			    po.src = 'https://apis.google.com/js/platform.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</div>
		<!-- BEGIN: fbShare -->
		<div class="fl">
			<div id="fb-root"></div>
			<script type="text/javascript">
			$(document).ready(function() {
			    $.ajaxSetup({
			        cache: true
			    });
			    $.getScript('//connect.facebook.net/{LOCALE}/all.js', function() {
			        FB.init({
			            appId: '{FB_APP_ID}',
			            version: 'v2.0',
			            xfbml: 1
			        });
					FB.Event.subscribe('comment.create', function(e) {
						BL.addCommentOnly({DATA.id});
					});
					FB.Event.subscribe('comment.remove', function(e) {
						BL.delCommentOnly({DATA.id});
					});
			    });
			});
			</script>
			<div class="fb-like" data-href="{DATA.href}" data-width="200" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
		</div>
		<!-- END: fbShare -->
	</div>
	<div class="post-detail-content" itemprop="articleBody">
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
		    <span><i class="icon-user mi">&nbsp;</i> <!-- BEGIN: postName --><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">{DATA.postName}</span></span><!-- END: postName --><!-- BEGIN: postGoogleID --><span itemprop="author" itemscope itemtype="http://schema.org/Person"><a href="https://plus.google.com/{DATA.postGoogleID}?rel=author" itemprop="author"><span itemprop="name">{DATA.postName}</span></a></span><!-- END: postGoogleID --> </span>
		    <span><i class="icon-time mi">&nbsp;</i><span itemprop="datePublished" content="{DATA.pubTimeGoogle}">{DATA.pubTime} </span></span>
		    <span><a href="#comment"><i class="icon-comments-alt mi">&nbsp;</i> {DATA.numComments} {LANG.blNumComments}</a> </span>
		</div>
		<div class="fr">
			<div class="addthis_sharing_toolbox"></div>
			<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=hoaquynhtim99"></script>
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
	<!-- BEGIN: comment -->
	<a id="comment" name="comment"></a>
	<!-- BEGIN: facebook -->
	<div class="fb-comments" data-href="{DATA.href}" data-numposts="{COMMENT_PER_PAGE}" data-colorscheme="{COLORSCHEME}" data-width="100%"></div>
	<!-- END: facebook -->
	<!-- BEGIN: disqus -->
	<div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = '{DISQUS_SHORTNAME}';
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
	<!-- END: disqus -->
	<!-- END: comment -->
</div>
<!-- END: main -->