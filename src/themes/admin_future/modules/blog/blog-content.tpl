<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$NV_BASE_SITEURL}themes/default/images/{$MODULE_FILE}/frameworks/autosize/jquery.autosize.js"></script>
{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$ERROR}
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off" id="post-form">
    <div class="card">
        <div class="card-header fs-5 fw-medium">
            {if $ID}{$LANG->get('blogEdit')}{else}{$LANG->get('blogAdd')}{/if}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row mb-3">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementTitle">{$LANG->get('blogTitle')} <i class="text-danger">(*)</i></label>
                        <div class="col-12 col-lg-9">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control" id="formElementTitle" name="title" value="{$DATA.title}" maxlength="250">
                                </div>
                                <div class="flex-grow-0 flex-shrink-0 ps-2">
                                    <button class="btn btn-secondary" type="button" id="formElementTitleClick" tabindex="-1">{$LANG->get('aliasAutoGet')}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementAlias">{$LANG->get('alias')}</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" class="form-control" id="formElementAlias" name="alias" value="{$DATA.alias}" maxlength="250">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementSitetitle">{$LANG->get('blogSiteTitle')}</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" class="form-control" id="formElementSitetitle" name="sitetitle" value="{$DATA.sitetitle}" maxlength="250">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementKeywords">{$LANG->get('keywordsSoft')} <i class="text-danger">(*)</i></label>
                        <div class="col-12 col-lg-9">
                            <input type="text" class="form-control" id="formElementKeywords" name="keywords" value="{$DATA.keywords}" maxlength="250">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementHometext">{$LANG->get('blogHometext')} <i class="text-danger">(*)</i></label>
                        <div class="col-12 col-lg-9">
                            <textarea type="text" class="autoresize textarea-animated form-control" id="formElementHometext" name="hometext" rows="5">{$DATA.hometext}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group row text-center">
                        <div class="col">
                            <input type="hidden" name="tokend" value="{$TOKEND}">
                            <input type="hidden" name="id" id="post-id" value="{$ID}">
                            <input type="submit" name="submit" value="{$LANG->get('blogPublic')}" class="btn btn-primary">
                            <input type="submit" name="draft" value="{$LANG->get('blogSaveDraft')}" class="btn btn-secondary" data-toggle="savedraft">
                        </div>
                    </div>
                    <div class="card border mt-3 ">
                        <div class="card-header fs-5 fw-medium">{$LANG->get('blogInCats')} <i class="text-danger">(*)</i></div>
                        <div class="card-body">
                            <div class="bl-scroller bl-post-cats" data-wheel="true">
                                <div class="content">
                                    {foreach from=$LIST_CATS key=key item=value}
                                    <div class="form-check" style="margin-left: {($value.cat_level - 1) * 20}px;">
                                        <input class="form-check-input" type="checkbox" id="catids_{$value.id}" name="catids[]" value="{$value.id}"{if in_array($value.id, $DATA.catids)} checked="checked"{/if}>
                                        <label class="form-check-label" for="catids_{$value.id}">{$value.title}</label>
                                    </div>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-divider"></div>
            <h4>{$LANG->get('blogBodyhtml')} <i class="text-danger">(*)</i></h4>
            <div class="mb-4">
                {if $POSTINGMODE eq 'editor'}
                {$DATA.bodyhtml}
                {else}
                <div class="blmd-header">
                    <div class="blmd-header-tabs">
                        <ul class="nav nav-underline" role="tablist">
                            <li class="nav-item"><a href="#tab-post-writemarkdown" data-control="postTabs" data-value="write" data-bs-toggle="tab" role="tab" class="nav-link active">{$LANG->getModule('content_editing')}</a></li>
                            <li class="nav-item"><a href="#tab-post-previewmarkdown" data-control="postTabs" data-value="preview" data-bs-toggle="tab" role="tab" class="nav-link">{$LANG->getModule('content_preview')}</a></li>
                        </ul>
                    </div>
                    <div class="blmd-header-btns">
                        <div class="btns-inner">
                            <div class="d-flex align-items-center">
                                <a class="ms-2" href="#" data-toggle="mdicon" data-command="heading"><i class="fas fa-heading" data-toggle="tooltip" title="{$LANG->get('mdHeading')}"></i></a>
                                <a class="ms-2" href="#" data-toggle="mdicon" data-command="bold"><i class="fas fa-bold" data-toggle="tooltip" title="{$LANG->get('mdBold')}"></i></a>
                                <a class="ms-2" href="#" data-toggle="mdicon" data-command="italic"><i class="fas fa-italic" data-toggle="tooltip" title="{$LANG->get('mdItalic')}"></i></a>
                                <a class="ms-4" href="#" data-toggle="mdicon" data-command="quote"><i class="fas fa-quote-right" data-toggle="tooltip" title="{$LANG->get('mdQuote')}"></i></a>
                                <a class="ms-2" href="#" data-toggle="mdicon" data-command="code"><i class="fas fa-code" data-toggle="tooltip" title="{$LANG->get('mdCode')}"></i></a>
                                <a class="ms-2" href="#" data-toggle="mdicon" data-command="link"><i class="fas fa-link" data-toggle="tooltip" title="{$LANG->get('mdLink')}"></i></a>
                                <a class="ms-4" href="#" data-toggle="mdicon" data-command="blist"><i class="fas fa-list-ul" data-toggle="tooltip" title="{$LANG->get('mdBulletList')}"></i></a>
                                <a class="ms-2" href="#" data-toggle="mdicon" data-command="nlist"><i class="fas fa-list-ol" data-toggle="tooltip" title="{$LANG->get('mdNumberList')}"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content p-0 m-0">
                    <div class="tab-pane show active" id="tab-post-writemarkdown" role="tabpanel" aria-labelledby="tab-post-writemarkdown">
                        <textarea rows="10" name="markdown_text" class="form-control blmd-editor">{$DATA.markdown_text}</textarea>
                    </div>
                    <div class="tab-pane fade" id="tab-post-previewmarkdown" role="tabpanel" aria-labelledby="tab-post-previewmarkdowntab-post-previewmarkdown">
                        <div id="post-markdown-preview"></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function() {
                    var currentHtml = 1;
                    var currentView = 0;
                    $('[name="markdown_text"]').on('keyup', function() {
                        currentHtml++;
                    });
                    $('[data-control="postTabs"]').on('show.bs.tab', function(e) {
                        var currentTab = $(e.target);
                        if (currentTab.data('value') != 'preview' || currentView >= currentHtml) {
                            return true;
                        }
                        var ctn = $('#post-markdown-preview');
                        ctn.html('<div class="text-center"><i class="fas fa-spinner fa-pulse"></i></div>');
                        $.ajax({
                            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-content&nocache=' + new Date().getTime(),
                            type: 'POST',
                            data: {
                                markdownpreview: '{$TOKEND}',
                                markdowntext: $('[name="markdown_text"]').val(),
                            },
                            success: function(res) {
                                ctn.html(res);
                                currentView++;
                            }
                        });
                    });
                });
                </script>
                {/if}
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="row mb-3">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementImages">{$LANG->get('blogImages')}</label>
                        <div class="col-12 col-lg-9">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control" id="formElementImages" name="images" value="{$DATA.images}">
                                </div>
                                <div class="flex-grow-0 flex-shrink-0 ps-2">
                                    <button class="btn btn-secondary" type="button" id="formElementImagesView"><i class="far fa-eye"></i> {$LANG->get('view')}</button>
                                </div>
                                <div class="flex-grow-0 flex-shrink-0 ps-2">
                                    <button class="btn btn-secondary" type="button" id="formElementImagesBtn" data-toggle="selectfile" data-target="formElementImages" data-path="{$UPLOADS_PATH}" data-currentpath="{$CURRENT_PATH}" data-type="image"><i class="far fa-folder-open"></i> {$LANG->get('browse_image')}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementMediatype">{$LANG->get('blogmediatype')}</label>
                        <div class="col-12 col-lg-9">
                            <div class="form-inline">
                                <select class="form-select" id="formElementMediatype" name="mediatype">
                                    {foreach from=$BLOGMEDIATYPE key=key item=value}
                                    <option value="{$value}"{if $value eq $DATA.mediatype} selected="selected"{/if}>{$LANG->get("blogmediatype`$value`")}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end d-none d-sm-block"></label>
                        <div class="col-12 col-lg-8 mt-1">
                            <div class="form-check form-check-inline mb-1">
                                <input class="form-check-input" type="checkbox" id="blogmediashowlist" name="mediashowlist" value="1"{if $DATA.mediashowlist} checked="checked"{/if}>
                                <label class="form-check-label" for="blogmediashowlist">{$LANG->get('blogmediashowlist')}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-12 col-lg-3 col-form-label text-lg-end d-none d-sm-block"></label>
                        <div class="col-12 col-lg-8 mt-1">
                            <div class="form-check form-check-inline mb-1">
                                <input class="form-check-input" type="checkbox" id="blogmediashowdetail" name="mediashowdetail" value="1"{if $DATA.mediashowdetail} checked="checked"{/if}>
                                <label class="form-check-label" for="blogmediashowdetail">{$LANG->get('blogmediashowdetail')}</label>
                            </div>
                        </div>
                    </div>
                    <div id="mediaheight-wrap">
                        <div class="row mb-3">
                            <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementMediaheight">{$LANG->get('blogMediaHeight')}</label>
                            <div class="col-12 col-lg-9">
                                <div class="form-inline">
                                    <input class="form-control" type="number" id="formElementMediaheight" name="mediaheight" value="{$DATA.mediaheight}" min="0" max="99999">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-12 col-lg-3 col-form-label text-lg-end d-none d-sm-block"></label>
                            <div class="col-12 col-lg-8 mt-1">
                                <div class="form-check form-check-inline mb-1">
                                    <input class="form-check-input" type="checkbox" id="formElementMediaresponsive" name="mediaresponsive" value="1"{if $DATA.mediaresponsive} checked="checked"{/if}>
                                    <label class="form-check-label" for="formElementMediaresponsive">{$LANG->get('blogMediaResponsive')}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementMediawidth">{$LANG->get('blogMediaWidth')}</label>
                            <div class="col-12 col-lg-9">
                                <div class="form-inline">
                                    <input class="form-control" type="number" id="formElementMediawidth" name="mediawidth" value="{$DATA.mediawidth}" min="0" max="99999">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="mediavalue-wrap">
                        <div class="row mb-3">
                            <label class="col-12 col-lg-3 col-form-label text-lg-end" for="formElementMediavalue">{$LANG->get('blogMediaValue')}</label>
                            <div class="col-12 col-lg-9">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 flex-shrink-1">
                                        <input type="text" class="form-control" id="formElementMediavalue" name="mediavalue" value="{$DATA.mediavalue}">
                                    </div>
                                    <div class="flex-grow-0 flex-shrink-0 ps-2" id="formElementMediavalueWrap">
                                        <button class="btn btn-secondary" type="button" id="formElementMediavalueBtn" data-toggle="selectfile" data-target="formElementMediavalue" data-path="{$UPLOADS_PATH}" data-currentpath="{$CURRENT_PATH}" data-type="file"><i class="far fa-folder-open"></i> {$LANG->get('browse_file')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 bl-post-col-tools">
                    <div class="form-group mb-2">
                        <label>{$LANG->get('blogTags')} <span data-toggle="tooltip" title="{$LANG->get('canDragDrop')}" class="text-primary"><i class="fas fa-info-circle"></i></span>:</label>
                        <div>
                            <input type="hidden" name="tagids" value="{$TAGIDS}" id="post-tags">
                            <ul class="bl-list-tags" id="post-tags-list">
                                {foreach from=$TAGS key=key item=value}
                                <li data-id="{$value.id}" class="badge text-bg-secondary lh-base post-tags-most-item"><i class="fas fa-tag"></i> {$value.title} <a href="#"><i class="fas fa-times text-danger"></i></a></li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                    {if not empty($MOSTTAGS)}
                    <div class="form-group mb-2">
                        <label>{$LANG->get('tagsMost')}:</label>
                        <div id="post-tags-most">
                            {foreach from=$MOSTTAGS key=key item=value}
                            <a href="#" class="badge text-bg-secondary lh-base post-tags-most-item" data-toggle="tagitem" data-id="{$value.id}" data-title="{$value.title}"><i class="fas fa-tag"></i> {$value.title}</a>
                            {/foreach}
                        </div>
                    </div>
                    {/if}
                    <div class="form-group mb-2">
                        <label>{$LANG->get('tagsSearch')}:</label>
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <div class="flex-grow-1 flex-shrink-1 pe-1">
                                    <input type="text" class="form-control form-control-xs" id="post-tags-type">
                                </div>
                                <div class="flex-grow-0 flex-shrink-0">
                                    <input type="button" class="btn btn-secondary" value="{$LANG->get('add')}" id="post-tags-button" data-tokend="{$TOKEND}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 col-md-4  col-xl-4">
                    <div class="form-group mb-2">
                        <div class="fw-medium mb-2">{$LANG->get('blogposttype')}:</div>
                        {foreach from=$BLOGPOSTTYPE key=key item=value}
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="elementPosttype_{$value}" name="posttype" value="{$value}"{if $DATA.posttype eq $value} checked="checked"{/if}>
                            <label class="form-check-label" for="elementPosttype_{$value}">{$LANG->get("blogposttype`$value`")}</label>
                        </div>
                        {/foreach}
                    </div>
                </div>
                <div class="col-12 col-md-4  col-xl-4">
                    <div class="form-group mb-2">
                        <div class="fw-medium mb-2">{$LANG->get('blogOtherOption')}:</div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="elementNewsletters" name="newsletters" value="1"{if $NEWSLETTERS} checked="checked"{/if}>
                            <label class="form-check-label" for="elementNewsletters">{$LANG->get('blogSendNewsletter')}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="elementFullpage" name="fullpage" value="1"{if $DATA.fullpage} checked="checked"{/if}>
                            <label class="form-check-label" for="elementFullpage">{$LANG->get('blogFullPage')}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="elementIsAutoKeywords" name="isAutoKeywords" value="1"{if $ISAUTOKEYWORDS} checked="checked"{/if}>
                            <label class="form-check-label" for="elementIsAutoKeywords">{$LANG->get('blogIsAutoKeywords')}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="elementInhome" name="inhome" value="1"{if $DATA.inhome} checked="checked"{/if}>
                            <label class="form-check-label" for="elementInhome">{$LANG->get('bloginhome')}</label>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="formElementGoogleAuthor">{$LANG->get('blogGoogleAuthor')}:</label>
                        <input type="text" class="form-control" id="formElementGoogleAuthor" name="postgoogleid" value="{$DATA.postgoogleid}">
                    </div>
                </div>
                <div class="col-12 col-md-4  col-xl-4">
                    <div class="form-group mb-2">
                        <label for="formElementPubtime">{$LANG->get('blogPubtime1')}:</label>
                        <div class="row g-2">
                            <div class="col-xs-6 col-sm-3">
                                <select name="pubtime_h" class="form-select mb-2">
                                    {for $val=0 to 23}
                                    <option value="{$val}"{if $val eq $DATA.pubtime_h} selected="selected"{/if}>{if $val lt 10}0{/if}{$val}</option>
                                    {/for}
                                </select>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <select name="pubtime_m" class="form-select mb-2">
                                    {for $val=0 to 59}
                                    <option value="{$val}"{if $val eq $DATA.pubtime_m} selected="selected"{/if}>{if $val lt 10}0{/if}{$val}</option>
                                    {/for}
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control mb-2 datepicker-post" value="{if not empty($DATA.pubtime)}{"d/m/Y"|date:$DATA.pubtime}{/if}" name="pubtime" id="formElementPubtime" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="formElementExptime">{$LANG->get('blogExptime1')}:</label>
                        <div class="row g-2">
                            <div class="col-xs-6 col-sm-3">
                                <select name="exptime_h" class="form-select mb-2">
                                    {for $val=0 to 23}
                                    <option value="{$val}"{if $val eq $DATA.exptime_h} selected="selected"{/if}>{if $val lt 10}0{/if}{$val}</option>
                                    {/for}
                                </select>
                            </div>
                            <div class="col-xs-6 col-sm-3">
                                <select name="exptime_m" class="form-select mb-2">
                                    {for $val=0 to 59}
                                    <option value="{$val}"{if $val eq $DATA.exptime_m} selected="selected"{/if}>{if $val lt 10}0{/if}{$val}</option>
                                    {/for}
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <input type="text" class="form-control mb-2 datepicker-post" value="{if not empty($DATA.exptime)}{"d/m/Y"|date:$DATA.exptime}{/if}" name="exptime" id="formElementExptime" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formElementExpmode">{$LANG->get('blogExpMode1')}:</label>
                        <select id="formElementExpmode" name="expmode" class="form-select">
                            {foreach from=$BLOGEXPMODE key=key item=value}
                            <option value="{$value}"{if $value eq $DATA.expmode} selected="selected"{/if}>{$LANG->get("blogExpMode_`$value`")}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <input type="hidden" name="postingMode" value="{$POSTINGMODE}">
                <input type="submit" name="submit" value="{$LANG->get('blogPublic')}" class="btn btn-primary">
                <input type="submit" name="draft" value="{$LANG->get('blogSaveDraft')}" class="btn btn-secondary" data-toggle="savedraft">
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
$(function() {
    function mediaHandle() {
        var mediatype = $('#formElementMediatype').val();
        if (mediatype != '2' && mediatype != '3' && mediatype != 4) {
            $('#formElementMediaheight').attr('disabled', 'disabled');
            $('#formElementMediawidth').attr('disabled', 'disabled');
            $('#formElementMediaresponsive').attr('disabled', 'disabled');
            $('#mediaheight-wrap').hide();
        } else {
            $('#formElementMediaheight').removeAttr('disabled');
            $('#formElementMediawidth').removeAttr('disabled');
            $('#formElementMediaresponsive').removeAttr('disabled');
            $('#mediaheight-wrap').show();
        }
        if (mediatype == '0') {
            $('#formElementMediavalue').attr('disabled', 'disabled');
            $('#mediavalue-wrap').hide();
        } else {
            $('#formElementMediavalue').removeAttr('disabled');
            $('#mediavalue-wrap').show();
        }
        if (mediatype == '0' || mediatype == '4') {
            $('#formElementMediavalueWrap').hide();
        } else {
            $('#formElementMediavalueWrap').show();
        }
    }
    mediaHandle();

    // Lấy liên kết tĩnh khi điền xong tiêu đề
    $('#formElementTitle').on('change', function() {
        if (trim($('#formElementAlias').val()) == '') {
            get_alias('formElementTitle', 'formElementAlias', 'post');
        }
    });
    // Lấy liên kết tĩnh khi ấn nút
    $('#formElementTitleClick').on('click', function() {
        get_alias('formElementTitle', 'formElementAlias', 'post');
    });
    // Thao tác khi thay đổi loại media
    $('#formElementMediatype').change(function() {
        mediaHandle();
    });
    // Xem ảnh
    $('#formElementImagesView').on('click', function(e) {
        e.preventDefault();
        var src = trim($('#formElementImages').val());
        if (src == '') {
            return false;
        }
        var date = new Date();
        var timestamp = date.getTime();
        var seprate = src.indexOf("?") == -1 ? '?' : '&amp;';
        var html = '<img alt="View Image" src="' + src + seprate + 't=' + timestamp + '" class="img-fluid">';
        $('#md-view-post-image').find('[data-toggle="mdcontent"]').html(html);
        $('#md-view-post-image').modal('show');
    });

    BL.tags.init();
    BL.post.init({$EDITOR});
});
</script>
<div id="md-view-post-image" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog full-width">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="text-center" data-toggle="mdcontent"></div>
            </div>
        </div>
    </div>
</div>
