<!-- BEGIN: main -->
<!-- BEGIN: highlight_js -->
<link type="text/css" href="{NV_BASE_SITEURL}themes/default/images/{MODULE_FILE}/frameworks/highlight/styles/{HIGHLIGHT_THEME}.css" rel="stylesheet">
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/images/{MODULE_FILE}/frameworks/highlight/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<!-- END: highlight_js -->
<div class="news-list">
    <!-- BEGIN: loop -->
    <div class="news-item" itemscope itemtype="http://schema.org/Article">
        <div class="news-image">
            <a href="{ROW.link}"><img src="{ROW.images}" alt="{ROW.title}"/></a>
        </div>
        <div class="news-content">
            <h3 class="news-title"><i class="{ROW.icon}">&nbsp;</i><a href="{ROW.link}"><span itemprop="name">{ROW.title}</span></a> </h3>
            <p>
                {ROW.hometext}
            </p>
            <div class="meta">
                <span><i class="icon-time mi">&nbsp;</i>{ROW.pubtime} </span>
                <span><a href="{ROW.linkComment}"><i class="icon-comments-alt"></i> {ROW.numcomments} {LANG.blNumComments}</a> </span>
            </div>
            <a class="tbutton small readmore" href="{ROW.link}"><span> {LANG.blDetail} </span></a>
        </div>
        <div class="ms4-clear">&nbsp;</div>
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
