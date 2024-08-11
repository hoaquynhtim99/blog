<!-- BEGIN: main -->
<!-- BEGIN: highlight_js -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/frameworks/highlight/styles/{HIGHLIGHT_THEME}.css" rel="stylesheet">
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/frameworks/highlight/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<!-- END: highlight_js -->
<div class="news-list">
    <!-- BEGIN: loop -->
    <article class="news-item" itemscope itemtype="http://schema.org/Article">
        <div class="news-image">
            <a href="{ROW.link}"><img src="{ROW.images}" alt="{ROW.title}"/></a>
        </div>
        <div class="news-content">
            <h3 class="news-title"><i class="{ROW.icon}"></i> <a href="{ROW.link}"><span itemprop="name">{ROW.title}</span></a> </h3>
            <div class="summarytext">{ROW.hometext}</div>
            <div class="meta">
                <span><i class="fa fa-calendar" aria-hidden="true"></i> {ROW.pubtime} </span>
                <span><a href="{ROW.linkComment}"><i class="fa fa-comments-o" aria-hidden="true"></i> {ROW.numcomments} {LANG.blNumComments}</a> </span>
                <span><a class="readmore" href="{ROW.link}"><i class="fa fa-sign-in" aria-hidden="true"></i> {LANG.blDetail}</a></span>
            </div>
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
