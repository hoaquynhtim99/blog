<!-- BEGIN: main -->
<!-- BEGIN: highlight_js -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/frameworks/highlight/styles/{HIGHLIGHT_THEME}.css" rel="stylesheet">
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/frameworks/highlight/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<!-- END: highlight_js -->
<div class="post-list">
    <!-- BEGIN: loop -->
    <article class="post-item clearfix" itemscope itemtype="http://schema.org/Article">
        <!-- BEGIN: media -->
        <!-- BEGIN: image -->
        <img class="media-image" src="{ROW.mediavalue}" alt="{ROW.title}"/>
        <!-- END: image -->
        <!-- BEGIN: audio -->
        <div id="media-audio-{ROW.id}"></div>
        <script type="text/javascript">
        jwplayer("media-audio-{ROW.id}").setup({
            file: "{ROW.mediavalue}",
            image: "{ROW.images}",
            width: '100%',
            <!-- BEGIN: height -->height: {ROW.mediaheight},<!-- END: height -->
            <!-- BEGIN: aspectratio -->aspectratio: "{ASPECTRATIO}",<!-- END: aspectratio -->
            stretching: 'fill'
        });
        </script>
        <!-- END: audio -->
        <!-- BEGIN: video -->
        <div id="media-video-{ROW.id}"></div>
        <script type="text/javascript">
        jwplayer("media-video-{ROW.id}").setup({
            file: "{ROW.mediavalue}",
            image: "{ROW.images}",
            width: '100%',
            <!-- BEGIN: height -->height: {ROW.mediaheight},<!-- END: height -->
            <!-- BEGIN: aspectratio -->aspectratio: "{ASPECTRATIO}",<!-- END: aspectratio -->
            stretching: 'fill'
        });
        </script>
        <!-- END: video -->
        <!-- BEGIN: iframe -->
        <iframe class="media-iframe" src="{ROW.mediavalue}"<!-- BEGIN: height --> height="{ROW.mediaheight}"<!-- END: height --><!-- BEGIN: aspectratio --> data-toggle="postiframescale" data-w="{ROW.mediawidth}" data-h="{ROW.mediaheight}"<!-- END: aspectratio -->></iframe>
        <!-- END: iframe -->
        <!-- END: media -->
        <h3 class="post-title"><i class="{ROW.icon}"></i> <a href="{ROW.link}"><span itemprop="name">{ROW.title}</span></a></h3>
        <!-- BEGIN: hometext -->
        <p class="item-hometext">
            {ROW.hometext}
        </p>
        <!-- END: hometext -->
        <!-- BEGIN: bodyhtml -->
        <div class="post-item-html clearfix">
            {ROW.bodyhtml}
        </div>
        <!-- END: bodyhtml -->
        <div class="meta">
            <span><i class="fa fa-user" aria-hidden="true"></i> <span itemprop="author">{ROW.postName}</span></span>
            <span><i class="fa fa-clock-o" aria-hidden="true"></i> {ROW.pubtime}</span>
            <span><a href="{ROW.linkComment}"><i class="fa fa-comments-o" aria-hidden="true"></i> {ROW.numcomments} {LANG.blNumComments}</a></span>
            <span><a class="btn btn-primary btn-xs pull-right" href="{ROW.link}">{LANG.blDetail}</a></span>
        </div>
    </article>
    <!-- END: loop -->
</div>
<ul class="pagination bl-pagination clearfix">
    <!-- BEGIN: generate_page -->
    {GENERATE_PAGE}
    <!-- END: generate_page -->
    <li class="pages"><span>{GLANG.page} {PAGE_CURRENT} {LANG.paginationInfo} {PAGE_TOTAL}</span></li>
</ul>
<!-- END: main -->
