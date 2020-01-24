<!-- BEGIN: main -->
<div class="post-list">
    <!-- BEGIN: loop -->
    <div class="post-item clearfix" itemscope itemtype="http://schema.org/Article">
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
        <h3 class="post-title"><i class="{ROW.icon}">&nbsp;</i><a href="{ROW.link}"><span itemprop="name">{ROW.title}</span></a> </h3>
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
            <span><i class="fa fa-user" aria-hidden="true"></i> <span itemprop="author">{ROW.postName}</span> </span>
            <span><i class="fa fa-clock-o" aria-hidden="true"></i> {ROW.pubtime} </span>
            <span><a href="{ROW.linkComment}"><i class="fa fa-comments-o" aria-hidden="true"></i> {ROW.numcomments} {LANG.blNumComments}</a> </span>
        </div>
        <a class="btn btn-primary btn-xs pull-right" href="{ROW.link}"><span> {LANG.blDetail} </span></a>
    </div>
    <!-- END: loop -->
</div>
<ul class="pagination bl-pagination clearfix">
    <!-- BEGIN: generate_page -->
    {GENERATE_PAGE}
    <!-- END: generate_page -->
    <li class="pages"><span>{GLANG.page} {PAGE_CURRENT} {LANG.paginationInfo} {PAGE_TOTAL}</span></li>
</ul>
<!-- END: main -->
