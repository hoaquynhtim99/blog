{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="instantArticlesActive" name="instantArticlesActive" value="1"{if $DATA.instantArticlesActive} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfgInsArtActive')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="instantArticlesTemplate">{$LANG->get('cfgInsArtTemplate')}</label>
                <div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                    <input type="text" class="form-control form-control-sm" id="instantArticlesTemplate" name="instantArticlesTemplate" value="{$DATA.instantArticlesTemplate}">
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="instantArticlesHttpauth" name="instantArticlesHttpauth" value="1"{if $DATA.instantArticlesHttpauth} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfgInsArtHttpauth')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="instantArticlesUsername">{$LANG->get('cfgInsArtUsername')}</label>
                <div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                    <input type="text" class="form-control form-control-sm" id="instantArticlesUsername" name="instantArticlesUsername" value="{$DATA.instantArticlesUsername}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="instantArticlesPassword">{$LANG->get('cfgInsArtPassword')}</label>
                <div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                    <div class="input-group input-group-sm">
                        <input type="password" class="form-control" name="instantArticlesPassword" id="instantArticlesPassword" value="{$DATA.instantArticlesPassword}" autocomplete="new-password">
                        <div class="input-group-append">
                            <button id="instantArticlesPasswordShowHide" tabindex="-1" class="btn btn-primary" type="button" title="{$LANG->get('show_hide_pass')}"><i class="far fa-eye"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="instantArticlesLivetime">{$LANG->get('cfgInsArtLivetime')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 flex-shrink-1">
                                <input type="number" class="form-control form-control-sm" id="instantArticlesLivetime" name="instantArticlesLivetime" value="{$DATA.instantArticlesLivetime}" min="0" max="999">
                            </div>
                            <div class="pl-1"><i>({$LANG->get('min')})</i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="instantArticlesGettime">{$LANG->get('cfgInsArtGettime')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 flex-shrink-1">
                                <input type="number" class="form-control form-control-sm" id="instantArticlesGettime" name="instantArticlesGettime" value="{$DATA.instantArticlesGettime}" min="0" max="999">
                            </div>
                            <div class="pl-1"><i>({$LANG->get('min')})</i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$(document).ready(function() {
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
    $("#instantArticlesArea").select2({
        width: "100%",
        containerCssClass: "select2-sm"
    });
});
</script>
<div class="card card-border-color card-border-color-primary">
    <div class="card-header card-header-divider">{$LANG->get('cfgInsArtToolLink')}</div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-12 col-sm-3 col-form-label text-sm-right" for="instantArticlesArea">{$LANG->get('cfgInsArtToolSelArea')}</label>
            <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                <select class="select2" id="instantArticlesArea" name="instantArticlesArea">
                    <option value="">{$LANG->get('cfgInsArtToolSelArea')}</option>
                    <option value="{$NV_MY_DOMAIN}{"`$NV_BASE_SITEURL`index.php?`$NV_LANG_VARIABLE`=`$NV_LANG_DATA`&amp;`$NV_NAME_VARIABLE`=`$MODULE_NAME`&amp;`$NV_OP_VARIABLE`=instant-rss"|rewrite:1}">{$LANG->get('cfgInsArtToolSelAreaAll')}</option>
                    {foreach from=$ARRAY_CATS key=key item=value}
                    <option value="{$NV_MY_DOMAIN}{"`$NV_BASE_SITEURL`index.php?`$NV_LANG_VARIABLE`=`$NV_LANG_DATA`&amp;`$NV_NAME_VARIABLE`=`$MODULE_NAME`&amp;`$NV_OP_VARIABLE`=instant-rss/`$value.alias`"|rewrite:1}">{$value.name}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-12 col-sm-3 text-sm-right pt-2" for="blockTagsNums">{$LANG->get('cfgInsArtToolResLink')}</label>
            <div class="col-12 col-sm-8 col-lg-6">
                <div id="instantArticlesLink" class="pt-2"></div>
            </div>
        </div>
    </div>
</div>
