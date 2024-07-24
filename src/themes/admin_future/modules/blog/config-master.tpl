{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$ERROR}
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off">
    <div class="row">
        <div class="col-xxl-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">{$LANG->get('cfgView')}</h5>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="indexViewType">{$LANG->get('cfgindexViewType')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-select" id="indexViewType" name="indexViewType">
                                {foreach from=$INDEXVIEWTYPE key=key item=value}
                                <option value="{$value}"{if $value eq $DATA.indexViewType} selected="selected"{/if}>{$LANG->get("cfgindexViewType_`$value`")}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="catViewType">{$LANG->get('cfgcatViewType')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-select" id="catViewType" name="catViewType">
                                {foreach from=$CATVIEWTYPE key=key item=value}
                                <option value="{$value}"{if $value eq $DATA.catViewType} selected="selected"{/if}>{$LANG->get("cfgcatViewType_`$value`")}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="tagsViewType">{$LANG->get('cfgtagsViewType')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-select" id="tagsViewType" name="tagsViewType">
                                {foreach from=$TAGSVIEWTYPE key=key item=value}
                                <option value="{$value}"{if $value eq $DATA.tagsViewType} selected="selected"{/if}>{$LANG->get("cfgtagsViewType_`$value`")}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="numPostPerPage">{$LANG->get('cfgnumPostPerPage')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <input type="number" class="form-control" id="numPostPerPage" name="numPostPerPage" value="{$DATA.numPostPerPage}" min="0" max="100">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="strCutHomeText">{$LANG->get('cfgstrCutHomeText')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <input type="number" class="form-control" id="strCutHomeText" name="strCutHomeText" value="{$DATA.strCutHomeText}" min="0" max="9999">
                            </div>
                            <div class="form-text text-muted">{$LANG->get('cfgstrCutHomeText_help')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="numSearchResult">{$LANG->get('cfgnumSearchResult')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <input type="number" class="form-control" id="numSearchResult" name="numSearchResult" value="{$DATA.numSearchResult}" min="0" max="100">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="showAdsInDetailPage">{$LANG->get('cfgshowAdsInDetailPage')}</label>
                        <div class="col-12 col-sm-8 col-lg-6 mt-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="showAdsInDetailPage" name="showAdsInDetailPage" value="1"{if $DATA.showAdsInDetailPage} checked="checked"{/if}>
                                <label class="form-check-label" for="showAdsInDetailPage">{$LANG->get('cfgshowAdsInDetailPage1')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                            <input type="hidden" name="tokend" value="{$TOKEND}">
                            <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">{$LANG->get('cfgTheme')}</h5>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="sysHighlightTheme">{$LANG->get('cfgsysHighlightTheme')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <select class="form-select select2" id="sysHighlightTheme" name="sysHighlightTheme">
                                {foreach from=$HIGHLIGHT_THEMES key=key item=value}
                                {assign var="highlightTheme" value=$value|substr:0:-4 nocache}
                                <option value="{$highlightTheme}"{if $highlightTheme eq $DATA.sysHighlightTheme} selected="selected"{/if}>{['-', '.', '_']|str_replace:" ":"`$highlightTheme|ucfirst`"}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {foreach from=$BLOGPOSTTYPE item=posttype}
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="iconClass{$posttype}">{$LANG->get('cfgIconPost', $LANG->get("blogposttype`$posttype`"))}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <input type="text" class="form-control" id="iconClass{$posttype}" name="iconClass{$posttype}" value="{$DATA["iconClass`$posttype`"]}">
                            </div>
                        </div>
                    </div>
                    {/foreach}
                    <div class="row">
                        <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                            <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card mb-4 mb-xxl-0">
                <div class="card-body">
                    <h5 class="card-title text-primary">{$LANG->get('cfgPost')}</h5>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="initPostExp">{$LANG->get('cfginitPostExp')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <select class="form-select" id="initPostExp" name="initPostExp">
                                    {foreach from=$BLOGEXPMODE key=key item=value}
                                    <option value="{$value}"{if $value eq $DATA.initPostExp} selected="selected"{/if}>{$LANG->get("blogExpMode_`$value`")}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-text text-muted">{$LANG->get('cfginitPostExpHelp')}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="initPostType">{$LANG->get('cfginitPostType')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <select class="form-select" id="initPostType" name="initPostType">
                                    {foreach from=$BLOGPOSTTYPE key=key item=value}
                                    <option value="{$value}"{if $value eq $DATA.initPostType} selected="selected"{/if}>{$LANG->get("blogposttype`$value`")}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="initMediaType">{$LANG->get('cfginitMediaType')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <select class="form-select" id="initMediaType" name="initMediaType">
                                    {foreach from=$BLOGMEDIATYPE key=key item=value}
                                    <option value="{$value}"{if $value eq $DATA.initMediaType} selected="selected"{/if}>{$LANG->get("blogmediatype`$value`")}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="initMediaHeight">{$LANG->get('cfginitMediaHeight')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <input type="number" class="form-control" id="initMediaHeight" name="initMediaHeight" value="{$DATA.initMediaHeight}" min="0" max="9999">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end d-none d-sm-block"></label>
                        <div class="col-12 col-sm-8 col-lg-6 mt-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="initMediaResponsive" name="initMediaResponsive" value="1"{if $DATA.initMediaResponsive} checked="checked"{/if}>
                                <label for="initMediaResponsive" class="form-check-label">{$LANG->get('cfginitMediaResponsive')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="initMediaWidth">{$LANG->get('cfginitMediaWidth')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <input type="number" class="form-control" id="initMediaWidth" name="initMediaWidth" value="{$DATA.initMediaWidth}" min="0" max="9999">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="folderStructure">{$LANG->get('cfgfolderStructure')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <select class="form-select" id="folderStructure" name="folderStructure">
                                {foreach from=$STRUCTURE_IMAGE key=key item=value}
                                <option value="{$key}"{if $key eq $DATA.folderStructure} selected="selected"{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end d-none d-sm-block"></label>
                        <div class="col-12 col-sm-8 col-lg-6 mt-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="initNewsletters" name="initNewsletters" value="1"{if $DATA.initNewsletters} checked="checked"{/if}>
                                <label for="initNewsletters" class="form-check-label">{$LANG->get('cfginitNewsletters')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end d-none d-sm-block"></label>
                        <div class="col-12 col-sm-8 col-lg-6 mt-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="initAutoKeywords" name="initAutoKeywords" value="1"{if $DATA.initAutoKeywords} checked="checked"{/if}>
                                <label for="initAutoKeywords" class="form-check-label">{$LANG->get('cfginitAutoKeywords')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="postingMode">{$LANG->get('cfgpostingMode')}</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="form-inline">
                                <div class="d-inline-flex align-items-center">
                                    <div class="flex-grow-1 flex-shrink-1">
                                        <select class="form-select" id="postingMode" name="postingMode">
                                            {foreach from=$POSTINGMODE key=key item=value}
                                            <option value="{$value}"{if $value eq $DATA.postingMode} selected="selected"{/if}>{$LANG->get("cfgpostingMode`$value`")}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                    <div class="flex-grow-0 flex-shrink-0 ms-1">
                                        <i class="fas fa-info-circle text-primary cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="click" title="{$LANG->get('cfgpostingModeHelp')}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                            <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">{$LANG->get('cfgNewsletter')}</h5>
                    <div class="row mb-3">
                        <label class="col-12 col-sm-4 col-form-label text-sm-end" for="numberResendNewsletter">{$LANG->get('cfgnumberResendNewsletter')}</label>
                        <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                            <select class="form-select" id="numberResendNewsletter" name="numberResendNewsletter">
                                {for $value=0 to $RESENDNEWSLETTERMAX}
                                <option value="{$value}"{if $value eq $DATA.numberResendNewsletter} selected="selected"{/if}>{$value}</option>
                                {/for}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-4">
                            <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
