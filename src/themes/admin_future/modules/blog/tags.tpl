<div class="card">
    <div class="card-body">
        <form method="get" action="{$NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="card-body-search-form pt-4 pb-2 px-4 form-inline">
                <div class="input-group bl-min-w-25">
                    <label class="sr-only" for="formElementQ">{$LANG->get('searchTags')}</label>
                    <input type="text" class="form-control mb-2 me-sm-2" id="formElementQ" name="q" value="{$DATA_SEARCH.q}" placeholder="{$LANG->get('searchTags')}">
                </div>
                <button type="submit" class="btn btn-primary mb-2 me-2">{$LANG->get('filter_action')}</button>
                {if not empty($DATA_SEARCH.q)}
                <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}" class="btn btn-secondary mb-2">{$LANG->get('filter_cancel')}</a>
                {/if}
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width:5%;" class="text-nowrap">
                            <label class="custom-control custom-control-sm custom-checkbox">
                                <input class="form-check-input" type="checkbox" data-toggle="BLCheckAll" name="BLIdItems" data-target="[name='BLIdItem[]']"><span class="custom-control-label"></span>
                            </label>
                        </th>
                        <th style="width:5%;" class="text-nowrap">ID</th>
                        <th style="width:30%;" class="text-nowrap"><a href="{$DATA_ORDER.title.data.url}" title="{$DATA_ORDER.title.data.title}">{if $DATA_ORDER.title.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.title.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('title')}</a></th>
                        <th style="width:45%;" class="text-nowrap">{$LANG->get('colDescriptionKeyword')}</th>
                        <th style="width:10%;" class="text-nowrap"><a href="{$DATA_ORDER.numposts.data.url}" title="{$DATA_ORDER.numposts.data.title}">{if $DATA_ORDER.numposts.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.numposts.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('categoriesnumPost')}</a></th>
                        <th class="text-end text-nowrap" style="width:10%;">{$LANG->get('feature')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY key=key item=row}
                    <tr>
                        <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                                <input class="form-check-input" type="checkbox" data-toggle="BLUncheckAll" name="BLIdItem[]" data-target="[name='BLIdItems']" value="{$row.id}"><span class="custom-control-label"></span>
                            </label>
                        </td>
                        <td>{$row.id}</td>
                        <td class="cell-detail">
                            {$row.title}
                            <span class="cell-detail-description">{$row.alias}</span>
                        </td>
                        <td class="cell-detail">
                            <div>{$row.description}</div>
                            <span class="cell-detail-description">{$LANG->get('keywordsSoft')}: {$row.keywords}</span>
                        </td>
                        <td><strong class="text-danger">{$row.numposts|format:0:",":"."}</strong></td>
                        <td class="text-end text-nowrap">
                            <a href="{$PREFIX_EDIT}&amp;id={$row.id}" class="btn btn-sm btn-hspace btn-secondary"><i class="fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="nv_delete_tags({$row.id});"><i class="fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="page-tools">
            <div class="btn-group">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" data-boundary="window">{$LANG->get('withSelectedRow')} <span class="icon-dropdown fas fa-chevron-down"></span></button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="javascript:void(0);" onclick="nv_tags_action('[name=\'BLIdItem[]\']', '{$LANG->get('alert_check')}', 1);"><i class="far fa-times-circle"></i> {$LANG->get('delete')}</a>
                </div>
            </div>
        </div>
        {if not empty($GENERATE_PAGE)}
        <nav class="page-nav">{$GENERATE_PAGE}</nav>
        {/if}
    </div>
</div>
{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<div class="card" id="formElement">
    <div class="card-header fs-5 fw-medium">
        {if $ID}{$LANG->get('tagsEdit')}{else}{$LANG->get('tagsAdd')}{/if}
    </div>
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="formElementTitle">{$LANG->get('title')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
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
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="formElementAlias">{$LANG->get('alias')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="formElementAlias" name="alias" value="{$DATA.alias}" maxlength="250">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="formElementKeywords">{$LANG->get('keywords')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="formElementKeywords" name="keywords" value="{$DATA.keywords}" maxlength="255">
                    <div class="form-text text-muted">{$LANG->get('keywordsNote')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="formElementDescription">{$LANG->get('description')} <i class="text-danger">(*)</i></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="formElementDescription" name="description" value="{$DATA.description}" maxlength="255">
                    <div class="form-text text-muted">{$LANG->get('descriptionNote')}</div>
                </div>
            </div>
            <div class="row">
                <label class="col-12 col-sm-3 col-form-label text-sm-end"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="id" value="{$ID}">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function() {
    $('#formElementTitle').on('change', function() {
        if (trim($('#formElementAlias').val()) == '') {
            get_alias('formElementTitle', 'formElementAlias', 'tags');
        }
    });
    $('#formElementTitleClick').on('click', function() {
        get_alias('formElementTitle', 'formElementAlias', 'tags');
    });
});
{if $IS_SUBMIT or $ID}
$(window).on('load', function() {
    $('html, body').animate({
        scrollTop: $('#formElement').offset().top
    }, 500);
});
{/if}
</script>
