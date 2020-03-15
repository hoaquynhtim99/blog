<div class="card card-border-color card-border-color-{if empty($ARRAY_NOTICE)}success{else}warning{/if}">
    {if empty($ARRAY_NOTICE)}
    <div class="card-header card-header-divider">{$LANG->get('mainNotice')}</div>
    <div class="card-body">
        <div role="alert" class="alert alert-success alert-dismissible my-0">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('title')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="fas fa-check"></i></div>
            <div class="message">{$LANG->get('mainNoticeEmpty')}</div>
        </div>
    </div>
    {else}
    <div class="card-header ml-4">{$LANG->get('mainNotice')}</div>
        <div class="list-group list-group-flush">
            {foreach from=$ARRAY_NOTICE item=item}
            <div class="list-group-item pl-4"><i class="fas fa-exclamation-circle text-warning"></i> <a href="{$item.link}">{$item.title}</a></div>
            {/foreach}
        </div>
    {/if}
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="bl-main-card-height">
            <div class="card card-border-color card-border-color-dark">
                <div class="card-header ml-4">{$LANG->get('mainQuickLink')}</div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item pl-4"><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=config-block-tags"><i class="fas fa-chevron-right"></i> {$LANG->get('cfgBlockTags')}</a></div>
                    <div class="list-group-item pl-4"><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=config-sys"><i class="fas fa-chevron-right"></i> {$LANG->get('cfgSys')}</a></div>
                    <div class="list-group-item pl-4"><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=config-structured-data"><i class="fas fa-chevron-right"></i> {$LANG->get('cfgStructureData')}</a></div>
                    <div class="list-group-item pl-4"><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=config-comment"><i class="fas fa-chevron-right"></i> {$LANG->get('cfgComment')}</a></div>
                    <div class="list-group-item pl-4"><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=config-instant-articles"><i class="fas fa-chevron-right"></i> {$LANG->get('cfgInsArt')}</a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="bl-main-card-height">
            <div class="card card-border-color card-border-color-primary">
                <div class="card-header ml-4">{$LANG->get('mainStat')}</div>
                <div class="list-group list-group-flush">
                    {foreach from=$ARRAY_STATISTICS item=item}
                    <div class="list-group-item pl-4"><a href="{$item.link}"><i class="fas fa-project-diagram text-dark"></i> {$item.title}</a></div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="bl-main-card-height">
            <div class="card card-border-color card-border-color-danger">
                <div class="card-header ml-4">{$LANG->get('mainInfo')}</div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item pl-4"><i class="fas fa-info-circle"></i> {$LANG->get('mainInfoVersion')}: <strong class="text-danger">{$MODULE_INFO.version}</strong></div>
                    <div class="list-group-item pl-4"><i class="fas fa-info-circle"></i> {$LANG->get('mainInfoRelease')}: <strong class="text-danger">{"d/m/Y"|date:$MODULE_INFO.date}</strong></div>
                    <div class="list-group-item pl-4"><i class="fas fa-info-circle"></i> {$LANG->get('mainInfoAuthor')}: <strong class="text-danger">{$MODULE_INFO.author}</strong></div>
                    <div class="list-group-item pl-4"><i class="fas fa-info-circle"></i> {$LANG->get('mainInfoContact')}: <strong class="text-danger">{$AUTHOR_CONTACT}</strong></div>
                    <div class="list-group-item pl-4"><i class="fas fa-info-circle"></i> {$LANG->get('mainInfoSupport')}: <strong class="text-danger"><a class="badge badge-primary" href="https://github.com/hoaquynhtim99/blog/" target="_blank" title="https://github.com/hoaquynhtim99/blog/"><i class="fab fa-github"></i> {$LANG->get('mainInfoSupport')}</a></strong></div>
                    <div class="list-group-item pl-4"><i class="fas fa-info-circle"></i> {$LANG->get('mainInfoIssue')}: <strong class="text-danger"><a class="badge badge-primary" href="https://github.com/hoaquynhtim99/blog/issues" target="_blank" title="https://github.com/hoaquynhtim99/blog/issues"><i class="fas fa-bug"></i> {$LANG->get('mainInfoIssue')}</a></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card card-border-color card-border-color-success">
    <div class="card-header card-header-divider">
        {$LANG->get('mainDonation')}
        <span class="card-subtitle">{$LANG->get('donateTitle')}</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="text-center">
                    <a target="_blank" href="https://www.nganluong.vn/button_payment.php?receiver={$DONATE_EMAIL}&amp;product_name={$DONATE_ORDERID}&amp;price={$DONATE_AMOUNT}&amp;return_url={$DONATE_RETURN}&amp;comments={$LANG->get('donateComment')}"><img class="img-fluid" alt="Ngân lượng" src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/images/{$MODULE_FILE}/nganluong.jpg"></a>
                    <h4>{$LANG->get('donateNganLuong')}</h4>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <form class="text-center" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="5C3MM5P45Z72L">
                    <input type="image" src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/images/{$MODULE_FILE}/paypal.jpg" class="img-fluid" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                </form>
                <div class="text-center">
                    <h4>{$LANG->get('donatePaypal')}</h4>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="text-center">
                    <a href="#modalDonateMomo" data-toggle="modal"><img class="img-fluid" alt="Momo" src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/images/{$MODULE_FILE}/donate-momo.jpg"></a>
                    <h4>{$LANG->get('donateMomo')}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalDonateMomo" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fas fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="text-center">
                        <img width="200" class="img-fluid" alt="Donate Momo" src="{$NV_BASE_SITEURL}themes/{$TEMPLATE}/images/{$MODULE_FILE}/donate-momo-qr.jpg">
                    </div>
                    <h3>{$LANG->get('donateMomo')}.</h3>
                    <p>{$LANG->get('donateMomoHelp')}.</p>
                    <div class="mt-6">
                        <button type="button" data-dismiss="modal" class="btn btn-space btn-secondary">{$LANG->get('close')}</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
