{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">{$LANG->get('cfgView')}</div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="indexViewType">{$LANG->get('cfgindexViewType')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="indexViewType" name="indexViewType">
                        {foreach from=$INDEXVIEWTYPE key=key item=value}
                        <option value="{$value}"{if $value eq $DATA.indexViewType} selected="selected"{/if}>{$LANG->get("cfgindexViewType_`$value`")}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="catViewType">{$LANG->get('cfgcatViewType')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="catViewType" name="catViewType">
                        {foreach from=$CATVIEWTYPE key=key item=value}
                        <option value="{$value}"{if $value eq $DATA.catViewType} selected="selected"{/if}>{$LANG->get("cfgcatViewType_`$value`")}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="tagsViewType">{$LANG->get('cfgtagsViewType')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <select class="form-control form-control-sm" id="tagsViewType" name="tagsViewType">
                        {foreach from=$TAGSVIEWTYPE key=key item=value}
                        <option value="{$value}"{if $value eq $DATA.tagsViewType} selected="selected"{/if}>{$LANG->get("cfgtagsViewType_`$value`")}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="numPostPerPage">{$LANG->get('cfgnumPostPerPage')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <input type="number" class="form-control form-control-sm" id="numPostPerPage" name="numPostPerPage" value="{$DATA.numPostPerPage}" min="0" max="100">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="strCutHomeText">{$LANG->get('cfgstrCutHomeText')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <input type="number" class="form-control form-control-sm" id="strCutHomeText" name="strCutHomeText" value="{$DATA.strCutHomeText}" min="0" max="9999">
                    </div>
                    <div class="form-text text-muted">{$LANG->get('cfgstrCutHomeText_help')}</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="numSearchResult">{$LANG->get('cfgnumSearchResult')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <input type="number" class="form-control form-control-sm" id="numSearchResult" name="numSearchResult" value="{$DATA.numSearchResult}" min="0" max="100">
                    </div>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="showAdsInDetailPage">{$LANG->get('cfgshowAdsInDetailPage')}</label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="showAdsInDetailPage" name="showAdsInDetailPage" value="1"{if $DATA.showAdsInDetailPage} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfgshowAdsInDetailPage1')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">{$LANG->get('cfgTheme')}</div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="sysHighlightTheme">{$LANG->get('cfgsysHighlightTheme')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="select2" id="sysHighlightTheme" name="sysHighlightTheme">
                        {foreach from=$HIGHLIGHT_THEMES key=key item=value}
                        {assign var="highlightTheme" value=$value|substr:0:-4 nocache}
                        <option value="{$highlightTheme}"{if $highlightTheme eq $DATA.sysHighlightTheme} selected="selected"{/if}>{['-', '.', '_']|replace:" ":"`$highlightTheme|ucfirst`"}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {foreach from=$BLOGPOSTTYPE item=posttype}
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="iconClass{$posttype}">{$LANG->get('cfgIconPost', $LANG->get("blogposttype`$posttype`"))}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <input type="text" class="form-control form-control-sm" id="iconClass{$posttype}" name="iconClass{$posttype}" value="{$DATA["iconClass`$posttype`"]}">
                    </div>
                </div>
            </div>
            {/foreach}
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">{$LANG->get('cfgPost')}</div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="initPostExp">{$LANG->get('cfginitPostExp')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <select class="form-control form-control-sm" id="initPostExp" name="initPostExp">
                            {foreach from=$BLOGEXPMODE key=key item=value}
                            <option value="{$value}"{if $value eq $DATA.initPostExp} selected="selected"{/if}>{$LANG->get("blogExpMode_`$value`")}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="form-text text-muted">{$LANG->get('cfginitPostExpHelp')}</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="initPostType">{$LANG->get('cfginitPostType')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <select class="form-control form-control-sm" id="initPostType" name="initPostType">
                            {foreach from=$BLOGPOSTTYPE key=key item=value}
                            <option value="{$value}"{if $value eq $DATA.initPostType} selected="selected"{/if}>{$LANG->get("blogposttype`$value`")}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="initMediaType">{$LANG->get('cfginitMediaType')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <select class="form-control form-control-sm" id="initMediaType" name="initMediaType">
                            {foreach from=$BLOGMEDIATYPE key=key item=value}
                            <option value="{$value}"{if $value eq $DATA.initMediaType} selected="selected"{/if}>{$LANG->get("blogmediatype`$value`")}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="initMediaHeight">{$LANG->get('cfginitMediaHeight')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <input type="number" class="form-control form-control-sm" id="initMediaHeight" name="initMediaHeight" value="{$DATA.initMediaHeight}" min="0" max="9999">
                    </div>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="initMediaResponsive" name="initMediaResponsive" value="1"{if $DATA.initMediaResponsive} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfginitMediaResponsive')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="initMediaWidth">{$LANG->get('cfginitMediaWidth')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <input type="number" class="form-control form-control-sm" id="initMediaWidth" name="initMediaWidth" value="{$DATA.initMediaWidth}" min="0" max="9999">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="folderStructure">{$LANG->get('cfgfolderStructure')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="folderStructure" name="folderStructure">
                        {foreach from=$STRUCTURE_IMAGE key=key item=value}
                        <option value="{$key}"{if $key eq $DATA.folderStructure} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="initNewsletters" name="initNewsletters" value="1"{if $DATA.initNewsletters} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfginitNewsletters')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="initAutoKeywords" name="initAutoKeywords" value="1"{if $DATA.initAutoKeywords} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfginitAutoKeywords')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="postingMode">{$LANG->get('cfgpostingMode')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <div class="d-inline-flex align-items-center">
                            <div class="flex-grow-1 flex-shrink-1">
                                <select class="form-control form-control-sm" id="postingMode" name="postingMode">
                                    {foreach from=$POSTINGMODE key=key item=value}
                                    <option value="{$value}"{if $value eq $DATA.postingMode} selected="selected"{/if}>{$LANG->get("cfgpostingMode`$value`")}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="flex-grow-0 flex-shrink-0 ml-1">
                                <i class="fas fa-info-circle text-primary cursor-pointer" data-toggle="tooltip" data-placement="top" data-trigger="click" title="{$LANG->get('cfgpostingModeHelp')}"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-text text-muted">{$LANG->get('cfgMarkdownClass')}</div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">{$LANG->get('cfgNewsletter')}</div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="numberResendNewsletter">{$LANG->get('cfgnumberResendNewsletter')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="numberResendNewsletter" name="numberResendNewsletter">
                        {for $value=0 to $RESENDNEWSLETTERMAX}
                        <option value="{$value}"{if $value eq $DATA.numberResendNewsletter} selected="selected"{/if}>{$value}</option>
                        {/for}
                    </select>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#sysHighlightTheme").select2({
        width: "100%",
        containerCssClass: "select2-sm"
    });
});
</script>
