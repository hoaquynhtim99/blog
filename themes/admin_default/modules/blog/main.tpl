<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="blog-panel-title">{LANG.mainNotice}</h2>
    </div>
    <div class="panel-body">
        <!-- BEGIN: NoNotice --><div class="alert alert-success mb-0">{LANG.mainNoticeEmpty}</div><!-- END: NoNotice -->
        <!-- BEGIN: notice -->
        <ul class="list-unstyled mb-0">
            <!-- BEGIN: loop -->
            <li><a href="{NOTICE.link}" class="text-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {NOTICE.title}</a></li>
            <!-- END: loop -->
        </ul>
        <!-- END: notice -->
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="blog-panel-title">{LANG.mainQuickLink}</h2>
            </div>
            <ul class="list-group">
                <li class="list-group-item"><a href="{BASE_ADMIN_LINK}config-block-tags"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> {LANG.cfgBlockTags}</a></li>
                <li class="list-group-item"><a href="{BASE_ADMIN_LINK}config-sys"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> {LANG.cfgSys}</a></li>
                <li class="list-group-item"><a href="{BASE_ADMIN_LINK}config-structured-data"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> {LANG.cfgStructureData}</a></li>
                <li class="list-group-item"><a href="{BASE_ADMIN_LINK}config-comment"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> {LANG.cfgComment}</a></li>
                <li class="list-group-item"><a href="{BASE_ADMIN_LINK}config-instant-articles"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> {LANG.cfgInsArt}</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="blog-panel-title">{LANG.mainStat}</h2>
            </div>
            <ul class="list-group">
                <!-- BEGIN: statistics -->
                <li class="list-group-item"><i class="fa fa-info-circle" aria-hidden="true"></i> <a href="{STATISTICS.link}">{STATISTICS.title}</a></li>
                <!-- END: statistics -->
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="blog-panel-title">{LANG.mainInfo}</h2>
            </div>
            <ul class="list-group">
                <li class="list-group-item"><i class="fa fa-angle-right" aria-hidden="true"></i> {LANG.mainInfoVersion}: <strong>{MODULE_INFO.version}</strong></li>
                <li class="list-group-item"><i class="fa fa-angle-right" aria-hidden="true"></i> {LANG.mainInfoRelease}: <strong>{MODULE_INFO.date}</strong></li>
                <li class="list-group-item"><i class="fa fa-angle-right" aria-hidden="true"></i> {LANG.mainInfoAuthor}: <strong>{MODULE_INFO.author}</strong></li>
                <li class="list-group-item"><i class="fa fa-angle-right" aria-hidden="true"></i> {LANG.mainInfoContact}: <strong>{AUTHOR_CONTACT}</strong></li>
                <li class="list-group-item"><i class="fa fa-angle-right" aria-hidden="true"></i> {LANG.mainInfoSupport}: <a class="label label-info label-inlist-group" href="https://github.com/hoaquynhtim99/blog/" target="_blank" title="https://github.com/hoaquynhtim99/blog/"><i class="fa fa-github" aria-hidden="true"></i> {LANG.mainInfoSupport}</a></li>
                <li class="list-group-item"><i class="fa fa-angle-right" aria-hidden="true"></i> {LANG.mainInfoIssue}: <a class="label label-info label-inlist-group" href="https://github.com/hoaquynhtim99/blog/issues" target="_blank" title="https://github.com/hoaquynhtim99/blog/issues"><i class="fa fa-bug" aria-hidden="true"></i> {LANG.mainInfoIssue}</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="blog-panel-title">{LANG.mainDonation}</h2>
            </div>
            <div class="panel-body">
                <div class="text-center">
                    <div class="bl-donate">
                        <a target="_blank" href="https://www.nganluong.vn/button_payment.php?receiver={DONATE_EMAIL}&product_name={DONATE_ORDERID}&price={DONATE_AMOUNT}&return_url={DONATE_RETURN}&comments={LANG.donateComment}" ><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/nganluong.gif"  border="0" /></a>
                        <form class="form-inline" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                            <input type="hidden" name="cmd" value="_s-xclick"/>
                            <input type="hidden" name="hosted_button_id" value="5C3MM5P45Z72L"/>
                            <input type="image" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/paypal.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
