<link type="text/css" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="{$NV_BASE_SITEURL}themes/default/images/{$MODULE_FILE}/frameworks/autosize/jquery.autosize.js"></script>
<script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/locales/bootstrap-datepicker.{$NV_LANG_INTERFACE}.min.js"></script>
{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<form method="post" action="{$FORM_ACTION}" autocomplete="off" id="post-form">
    <div class="card card-border-color card-border-color-primary">
        <div class="card-header card-header-divider">
            {if $ID}{$LANG->get('blogEdit')}{else}{$LANG->get('blogAdd')}{/if}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group row">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementTitle">{$LANG->get('blogTitle')} <i class="text-danger">(*)</i></label>
                        <div class="col-12 col-lg-9">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control" id="formElementTitle" name="title" value="{$DATA.title}" maxlength="250">
                                </div>
                                <div class="flex-grow-0 flex-shrink-0 pl-2">
                                    <button class="btn btn-secondary btn-input-sm" type="button" id="formElementTitleClick" tabindex="-1">{$LANG->get('aliasAutoGet')}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementAlias">{$LANG->get('alias')}</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" class="form-control" id="formElementAlias" name="alias" value="{$DATA.alias}" maxlength="250">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementSitetitle">{$LANG->get('blogSiteTitle')}</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" class="form-control" id="formElementSitetitle" name="sitetitle" value="{$DATA.sitetitle}" maxlength="250">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementKeywords">{$LANG->get('keywordsSoft')}</label>
                        <div class="col-12 col-lg-9">
                            <input type="text" class="form-control" id="formElementKeywords" name="keywords" value="{$DATA.keywords}" maxlength="250">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementHometext">{$LANG->get('blogHometext')} <i class="text-danger">(*)</i></label>
                        <div class="col-12 col-lg-9">
                            <textarea type="text" class="autoresize textarea-animated form-control" id="formElementHometext" name="hometext" rows="3">{$DATA.hometext}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group row text-center">
                        <div class="col">
                            <input type="hidden" name="tokend" value="{$TOKEND}">
                            <input type="hidden" name="id" id="post-id" value="{$ID}">
                            <input type="submit" name="submit" value="{$LANG->get('blogPublic')}" class="btn btn-primary btn-input-sm">
                            <input type="submit" name="draft" value="{$LANG->get('blogSaveDraft')}" class="btn btn-secondary btn-input-sm" data-toggle="savedraft">
                        </div>
                    </div>
                    <div class="card card-border mt-2">
                        <div class="card-header card-header-divider">{$LANG->get('blogInCats')} <i class="text-danger">(*)</i></div>
                        <div class="card-body">
                            <div class="nv-scroller bl-post-cats" data-wheel="true">
                                <div class="content">
                                    {foreach from=$LIST_CATS key=key item=value}
                                    <label class="custom-control custom-checkbox" style="margin-left: {$value.catlev * 20}px;">
                                        <input class="custom-control-input" type="checkbox" name="catids[]" value="{$value.id}"{if in_array($value.id, $DATA.catids)} checked="checked"{/if}><span class="custom-control-label">{$value.title}</span>
                                    </label>
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
                        <ul role="tablist" class="nav nav-tabs">
                            <li class="nav-item"><a href="#tab-post-writemarkdown" data-control="postTabs" data-value="write" data-toggle="tab" role="tab" class="nav-link active">Soạn thảo</a></li>
                            <li class="nav-item"><a href="#tab-post-previewmarkdown" data-control="postTabs" data-value="preview" data-toggle="tab" role="tab" class="nav-link">Xem trước</a></li>
                        </ul>
                    </div>
                    <div class="blmd-header-btns">
                        <div class="btns-inner">
                            <div class="d-flex align-items-center">
                                <a class="ml-2" href="#" data-toggle="mdicon" data-command="heading"><i class="fas fa-heading" data-toggle="tooltip" title="{$LANG->get('mdHeading')}"></i></a>
                                <a class="ml-2" href="#" data-toggle="mdicon" data-command="bold"><i class="fas fa-bold" data-toggle="tooltip" title="{$LANG->get('mdBold')}"></i></a>
                                <a class="ml-2" href="#" data-toggle="mdicon" data-command="italic"><i class="fas fa-italic" data-toggle="tooltip" title="{$LANG->get('mdItalic')}"></i></a>
                                <a class="ml-4" href="#" data-toggle="mdicon" data-command="quote"><i class="fas fa-quote-right" data-toggle="tooltip" title="{$LANG->get('mdQuote')}"></i></a>
                                <a class="ml-2" href="#" data-toggle="mdicon" data-command="code"><i class="fas fa-code" data-toggle="tooltip" title="{$LANG->get('mdCode')}"></i></a>
                                <a class="ml-2" href="#" data-toggle="mdicon" data-command="link"><i class="fas fa-link" data-toggle="tooltip" title="{$LANG->get('mdLink')}"></i></a>
                                <a class="ml-4" href="#" data-toggle="mdicon" data-command="blist"><i class="fas fa-list-ul" data-toggle="tooltip" title="{$LANG->get('mdBulletList')}"></i></a>
                                <a class="ml-2" href="#" data-toggle="mdicon" data-command="nlist"><i class="fas fa-list-ol" data-toggle="tooltip" title="{$LANG->get('mdNumberList')}"></i></a>
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
                $(document).ready(function() {
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
                        $.post(
                            script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blog-content&nocache=' + new Date().getTime(),
                            'markdownrender=1&id=' + id,
                            function(res) {
                                ctn.html(res);
                            }
                        );
                    });
                });
                </script>
                {/if}
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group row">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementImages">{$LANG->get('blogImages')}</label>
                        <div class="col-12 col-lg-9">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 flex-shrink-1">
                                    <input type="text" class="form-control" id="formElementImages" name="images" value="{$DATA.images}">
                                </div>
                                <div class="flex-grow-0 flex-shrink-0 pl-2">
                                    <button class="btn btn-secondary btn-input-sm" type="button" id="formElementImagesView"><i class="icon icon-left far fa-eye"></i> {$LANG->get('view')}</button>
                                </div>
                                <div class="flex-grow-0 flex-shrink-0 pl-2">
                                    <button class="btn btn-secondary btn-input-sm" type="button" id="formElementImagesBtn"><i class="icon icon-left far fa-folder-open"></i> {$LANG->get('browse_image')}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementMediatype">{$LANG->get('blogmediatype')}</label>
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
                    <div class="form-group row py-0">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right d-none d-sm-block"></label>
                        <div class="col-12 col-lg-8 form-check mt-1">
                            <label class="custom-control custom-checkbox custom-control-inline mb-1">
                                <input class="custom-control-input" type="checkbox" id="blogmediashowlist" name="mediashowlist" value="1"{if $DATA.mediashowlist} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('blogmediashowlist')}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row py-0">
                        <label class="col-12 col-lg-3 col-form-label text-lg-right d-none d-sm-block"></label>
                        <div class="col-12 col-lg-8 form-check mt-1">
                            <label class="custom-control custom-checkbox custom-control-inline mb-1">
                                <input class="custom-control-input" type="checkbox" id="blogmediashowdetail" name="mediashowdetail" value="1"{if $DATA.mediashowdetail} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('blogmediashowdetail')}</span>
                            </label>
                        </div>
                    </div>
                    <div id="mediaheight-wrap">
                        <div class="form-group row">
                            <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementMediaheight">{$LANG->get('blogMediaHeight')}</label>
                            <div class="col-12 col-lg-9">
                                <div class="form-inline">
                                    <input class="form-control" type="number" id="formElementMediaheight" name="mediaheight" value="{$DATA.mediaheight}" min="0" max="99999">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row py-0">
                            <label class="col-12 col-lg-3 col-form-label text-lg-right d-none d-sm-block"></label>
                            <div class="col-12 col-lg-8 form-check mt-1">
                                <label class="custom-control custom-checkbox custom-control-inline mb-1">
                                    <input class="custom-control-input" type="checkbox" id="formElementMediaresponsive" name="mediaresponsive" value="1"{if $DATA.mediaresponsive} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('blogMediaResponsive')}</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementMediawidth">{$LANG->get('blogMediaWidth')}</label>
                            <div class="col-12 col-lg-9">
                                <div class="form-inline">
                                    <input class="form-control" type="number" id="formElementMediawidth" name="mediawidth" value="{$DATA.mediawidth}" min="0" max="99999">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="mediavalue-wrap">
                        <div class="form-group row">
                            <label class="col-12 col-lg-3 col-form-label text-lg-right" for="formElementMediavalue">{$LANG->get('blogMediaValue')}</label>
                            <div class="col-12 col-lg-9">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 flex-shrink-1">
                                        <input type="text" class="form-control" id="formElementMediavalue" name="mediavalue" value="{$DATA.mediavalue}">
                                    </div>
                                    <div class="flex-grow-0 flex-shrink-0 pl-2" id="formElementMediavalueWrap">
                                        <button class="btn btn-secondary btn-input-sm" type="button" id="formElementMediavalueBtn"><i class="icon icon-left far fa-folder-open"></i> {$LANG->get('browse_image')}</button>
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
                                <li data-id="{$value.id}" class="badge badge-secondary post-tags-most-item"><i class="fas fa-tag"></i> {$value.title} <a href="#"><i class="fas fa-times"></i></a></li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                    {if not empty($MOSTTAGS)}
                    <div class="form-group mb-2">
                        <label>{$LANG->get('tagsMost')}:</label>
                        <div id="post-tags-most">
                            {foreach from=$MOSTTAGS key=key item=value}
                            <a href="#" class="badge badge-secondary post-tags-most-item" data-toggle="tagitem" data-id="{$value.id}" data-title="{$value.title}"><i class="fas fa-tag"></i> {$value.title}</a>
                            {/foreach}
                        </div>
                    </div>
                    {/if}
                    <div class="form-group mb-2">
                        <label>{$LANG->get('tagsSearch')}:</label>
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <div class="flex-grow-1 flex-shrink-1 pr-1">
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
                        <label>{$LANG->get('blogposttype')}:</label>
                        {foreach from=$BLOGPOSTTYPE key=key item=value}
                        <label class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="posttype" value="{$value}"{if $DATA.posttype eq $value} checked="checked"{/if}><span class="custom-control-label">{$LANG->get("blogposttype`$value`")}</span>
                        </label>
                        {/foreach}
                    </div>
                </div>
                <div class="col-12 col-md-4  col-xl-4">
                    <div class="form-group mb-2">
                        <label>{$LANG->get('blogOtherOption')}:</label>
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="newsletters" value="1"{if $NEWSLETTERS} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('blogSendNewsletter')}</span>
                        </label>
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="fullpage" value="1"{if $DATA.fullpage} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('blogFullPage')}</span>
                        </label>
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="isAutoKeywords" value="1"{if $ISAUTOKEYWORDS} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('blogIsAutoKeywords')}</span>
                        </label>
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="inhome" value="1"{if $DATA.inhome} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('bloginhome')}</span>
                        </label>
                    </div>
                    <div class="form-group mb-2">
                        <label for="formElementGoogleAuthor">{$LANG->get('blogGoogleAuthor')}:</label>
                        <input type="text" class="form-control" id="formElementGoogleAuthor" name="postgoogleid" value="{$DATA.postgoogleid}">
                    </div>
                </div>
                <div class="col-12 col-md-4  col-xl-4">
                    <div class="form-group mb-2">
                        <label for="formElementPubtime">{$LANG->get('blogPubtime1')}:</label>
                        <div class="form-inline">
                            <select name="pubtime_h" class="form-control mb-2 mr-lg-2">
                                {for $val=0 to 23}
                                <option value="{$val}"{if $val eq $DATA.pubtime_h} selected="selected"{/if}>{if $val lt 10}0{/if}{$val}</option>
                                {/for}
                            </select>
                            <select name="pubtime_m" class="form-control mb-2 mr-lg-2">
                                {for $val=0 to 59}
                                <option value="{$val}"{if $val eq $DATA.pubtime_m} selected="selected"{/if}>{if $val lt 10}0{/if}{$val}</option>
                                {/for}
                            </select>
                            <input type="text" class="form-control mb-2 bsdatepicker" value="{if not empty($DATA.pubtime)}{"d/m/Y"|date:$DATA.pubtime}{/if}" name="pubtime" id="formElementPubtime" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="formElementExptime">{$LANG->get('blogExptime1')}:</label>
                        <div class="form-inline">
                            <select name="exptime_h" class="form-control mb-2 mr-lg-2">
                                {for $val=0 to 23}
                                <option value="{$val}"{if $val eq $DATA.exptime_h} selected="selected"{/if}>{if $val lt 10}0{/if}{$val}</option>
                                {/for}
                            </select>
                            <select name="exptime_m" class="form-control mb-2 mr-lg-2">
                                {for $val=0 to 59}
                                <option value="{$val}"{if $val eq $DATA.exptime_m} selected="selected"{/if}>{if $val lt 10}0{/if}{$val}</option>
                                {/for}
                            </select>
                            <input type="text" class="form-control mb-2 bsdatepicker" value="{if not empty($DATA.exptime)}{"d/m/Y"|date:$DATA.exptime}{/if}" name="exptime" id="formElementExptime" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formElementExpmode">{$LANG->get('blogExpMode1')}:</label>
                        <select id="formElementExpmode" name="expmode" class="form-control">
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
                <input type="submit" name="submit" value="{$LANG->get('blogPublic')}" class="btn btn-primary btn-input-sm">
                <input type="submit" name="draft" value="{$LANG->get('blogSaveDraft')}" class="btn btn-secondary btn-input-sm" data-toggle="savedraft">
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function() {
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
    // Chọn ngày tháng năm
    $(".bsdatepicker").datepicker({
        autoclose: 1,
        templates: {
            rightArrow: '<i class="fas fa-chevron-right"></i>',
            leftArrow: '<i class="fas fa-chevron-left"></i>'
        },
        language: '{$NV_LANG_INTERFACE}',
        orientation: 'auto bottom',
        todayHighlight: true,
        format: 'dd/mm/yyyy'
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
$(document).on("nv.upload.ready", function() {
    $("#formElementImagesBtn").nvBrowseFile({
        adminBaseUrl: '{$NV_BASE_ADMINURL}',
        path: '{$UPLOADS_PATH}',
        currentpath: '{$CURRENT_PATH}',
        type: 'image',
        area: '#formElementImages'
    });
    $("#formElementMediavalueBtn").nvBrowseFile({
        adminBaseUrl: '{$NV_BASE_ADMINURL}',
        path: '{$UPLOADS_PATH}',
        currentpath: '{$CURRENT_PATH}',
        type: 'file',
        area: '#formElementMediavalue'
    });
});
</script>
<div id="md-view-post-image" tabindex="-1" role="dialog" class="modal fade">
    <div class="modal-dialog full-width">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-bs-dismiss="modal" aria-hidden="true" class="close"><span class="fas fa-times"></span></button>
            </div>
            <div class="modal-body">
                <div class="text-center" data-toggle="mdcontent"></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
