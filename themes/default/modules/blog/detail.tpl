<!-- BEGIN: main -->
<div class="post-detail" itemscope itemtype="http://schema.org/Article">
    <!-- Google structured data required -->
    <span class="hidden" itemprop="dateModified" content="{DATA.updatetimeGoogle}"></span>
    <span class="hidden" itemprop="headline" content="{DATA.headlineGoogle}"></span>
    <span class="hidden" itemprop="mainEntityOfPage" content="{DATA.href}"></span>
    <span class="hidden" itemprop="image" content="{DATA.mediaImage}"></span>
    <span class="hidden" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
        <span class="hidden" itemprop="name" content="{DATA.publisherName}"></span>
        <span class="hidden" itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
            <span class="hidden" itemprop="url" content="{DATA.publisherLogo}"></span>
        </span>
    </span>
    <!-- BEGIN: media -->
    <div class="post-detail-media-data">
        <!-- BEGIN: image -->
        <img itemprop="image" class="media-image" src="{DATA.mediavalue}" alt="{DATA.title}"/>
        <!-- END: image -->
        <!-- BEGIN: audio -->
        <div id="media-audio-{DATA.id}"></div>
        <script type="text/javascript">
        jwplayer("media-audio-{DATA.id}").setup({
            file: "{DATA.mediavalue}",
            width: '100%',
            <!-- BEGIN: height -->height: {DATA.mediaheight},<!-- END: height -->
            <!-- BEGIN: aspectratio -->aspectratio: "{ASPECTRATIO}",<!-- END: aspectratio -->
            image: "{DATA.images}",
            stretching: 'fill'
        });
        </script>
        <!-- END: audio -->
        <!-- BEGIN: video -->
        <div id="media-video-{DATA.id}"></div>
        <script type="text/javascript">
        jwplayer("media-video-{DATA.id}").setup({
            file: "{DATA.mediavalue}",
            width: '100%',
            <!-- BEGIN: height -->height: {DATA.mediaheight},<!-- END: height -->
            <!-- BEGIN: aspectratio -->aspectratio: "{ASPECTRATIO}",<!-- END: aspectratio -->
            image: "{DATA.images}",
            stretching: 'fill'
        });
        </script>
        <!-- END: video -->
        <!-- BEGIN: iframe -->
        <iframe class="media-iframe" src="{DATA.mediavalue}"<!-- BEGIN: height --> height="{DATA.mediaheight}"<!-- END: height --><!-- BEGIN: aspectratio --> data-toggle="postiframescale" data-w="{DATA.mediawidth}" data-h="{DATA.mediaheight}"<!-- END: aspectratio -->></iframe>
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
        <i class="fa fa-tags" aria-hidden="true"></i> {LANG.tags}:
        <!-- BEGIN: loop -->
        <a href="{TAG.link}">{TAG.title}</a>
        <!-- END: loop -->
    </div>
    <!-- END: tags -->
    <div class="clearfix">
        <div class="post-detail-meta">
            <span><i class="fa fa-user-circle-o" aria-hidden="true"></i> <!-- BEGIN: postName --><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">{DATA.postName}</span></span><!-- END: postName --><!-- BEGIN: postgoogleid --><a href="https://plus.google.com/{DATA.postgoogleid}?rel=author" itemprop="author"><span itemprop="name">{DATA.postName}</span></a><!-- END: postgoogleid --> </span>
            <span><i class="fa fa-clock-o" aria-hidden="true"></i> <span itemprop="datePublished" content="{DATA.pubtimeGoogle}">{DATA.pubtime} </span></span>
            <span><a href="#comment"><i class="icon-comments-alt mi">&nbsp;</i> {DATA.numcomments} {LANG.blNumComments}</a> </span>
        </div>
        <div class="fr">
            <div class="addthis_sharing_toolbox"></div>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=hoaquynhtim99"></script>
        </div>
        <div class="fr">{LANG.share}: &nbsp;</div>
    </div>
    <hr />
    <!-- BEGIN: navPost -->
    <!-- BEGIN: nextPost -->
    <div class="post-detail-next-post">
        <a href="{DATA.nextPost.link}"><i class="fa fa-long-arrow-right" aria-hidden="true"></i> {DATA.nextPost.title}</a>
    </div>
    <!-- END: nextPost -->
    <!-- BEGIN: prevPost -->
    <div class="post-detail-prev-post">
        <a href="{DATA.prevPost.link}"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> {DATA.prevPost.title}</a>
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
