{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$ERROR}
</div>
{/if}
<div class="card mb-4">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="instantArticlesActive" name="instantArticlesActive" value="1"{if $DATA.instantArticlesActive} checked="checked"{/if}>
                        <label for="instantArticlesActive" class="form-check-label">{$LANG->get('cfgInsArtActive')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="instantArticlesTemplate">{$LANG->get('cfgInsArtTemplate')}</label>
                <div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                    <input type="text" class="form-control" id="instantArticlesTemplate" name="instantArticlesTemplate" value="{$DATA.instantArticlesTemplate}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="instantArticlesHttpauth" name="instantArticlesHttpauth" value="1"{if $DATA.instantArticlesHttpauth} checked="checked"{/if}>
                        <label for="instantArticlesHttpauth" class="form-check-label">{$LANG->get('cfgInsArtHttpauth')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="instantArticlesUsername">{$LANG->get('cfgInsArtUsername')}</label>
                <div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                    <input type="text" class="form-control" id="instantArticlesUsername" name="instantArticlesUsername" value="{$DATA.instantArticlesUsername}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="instantArticlesPassword">{$LANG->get('cfgInsArtPassword')}</label>
                <div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                    <div class="input-group">
                        <input type="password" class="form-control" name="instantArticlesPassword" id="instantArticlesPassword" value="{$DATA.instantArticlesPassword}" autocomplete="new-password">
                        <button id="instantArticlesPasswordShowHide" tabindex="-1" class="btn btn-primary" type="button" title="{$LANG->get('show_hide_pass')}"><i class="text-center fa-fw far fa-eye"></i></button>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="instantArticlesLivetime">{$LANG->get('cfgInsArtLivetime')}</label>
                <div class="col-12 col-sm-8 col-lg-3">
                    <div class="form-inline">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 flex-shrink-1">
                                <input type="number" class="form-control" id="instantArticlesLivetime" name="instantArticlesLivetime" value="{$DATA.instantArticlesLivetime}" min="0" max="999">
                            </div>
                            <div class="ps-1"><i>({$LANG->get('min')})</i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="instantArticlesGettime">{$LANG->get('cfgInsArtGettime')}</label>
                <div class="col-12 col-sm-8 col-lg-3">
                    <div class="form-inline">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 flex-shrink-1">
                                <input type="number" class="form-control" id="instantArticlesGettime" name="instantArticlesGettime" value="{$DATA.instantArticlesGettime}" min="0" max="999">
                            </div>
                            <div class="ps-1"><i>({$LANG->get('min')})</i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$(function() {
    $('#instantArticlesPasswordShowHide').on("click", function() {
        var icon = $(this).find('i');
        if (icon.is(".fa-eye")) {
            icon.removeClass("fa-eye");
            icon.addClass("fa-eye-slash");
            $("#instantArticlesPassword").prop("type", "text");
        } else {
            icon.addClass("fa-eye");
            icon.removeClass("fa-eye-slash");
            $("#instantArticlesPassword").prop("type", "password");
        }
    });
    $("#instantArticlesArea").on("change", function() {
        $("#instantArticlesLink").html($(this).val());
    });
});
</script>
<div class="card">
    <div class="card-header fs-5 fw-medium">{$LANG->get('cfgInsArtToolLink')}</div>
    <div class="card-body">
        <div class="row mb-3">
            <label class="col-12 col-sm-3 col-form-label text-sm-end" for="instantArticlesArea">{$LANG->get('cfgInsArtToolSelArea')}</label>
            <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                <select class="form-select select2" id="instantArticlesArea" name="instantArticlesArea">
                    <option value="">{$LANG->get('cfgInsArtToolSelArea')}</option>
                    <option value="{$NV_MY_DOMAIN}{"`$NV_BASE_SITEURL`index.php?`$NV_LANG_VARIABLE`=`$NV_LANG_DATA`&amp;`$NV_NAME_VARIABLE`=`$MODULE_NAME`&amp;`$NV_OP_VARIABLE`=instant-rss"|rewrite:1}">{$LANG->get('cfgInsArtToolSelAreaAll')}</option>
                    {foreach from=$ARRAY_CATS key=key item=value}
                    <option value="{$NV_MY_DOMAIN}{"`$NV_BASE_SITEURL`index.php?`$NV_LANG_VARIABLE`=`$NV_LANG_DATA`&amp;`$NV_NAME_VARIABLE`=`$MODULE_NAME`&amp;`$NV_OP_VARIABLE`=instant-rss/`$value.alias`"|rewrite:1}">{$value.name}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-12 col-sm-3 text-sm-end pt-2">{$LANG->get('cfgInsArtToolResLink')}</label>
            <div class="col-12 col-sm-8 col-lg-6">
                <div id="instantArticlesLink" class="pt-2"></div>
            </div>
        </div>
    </div>
</div>
