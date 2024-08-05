{if empty($NUMCAT)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$LANG->get('categoriesEmpty')}
</div>
{else}
<div class="card mb-4">
    <div class="card-body">
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb mb-0">
                {foreach from=$BREADCRUMBS key=key item=value}
                {if $value.link lt 0}
                <li class="breadcrumb-item active">{$value.title}</li>
                {elseif $value.link eq 0}
                <li class="breadcrumb-item"><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}">{$value.title}</a></li>
                {else}
                <li class="breadcrumb-item"><a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}&amp;parentid={$value.link}">{$value.title}</a></li>
                {/if}
                {/foreach}
            </ol>
        </nav>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card" id="list-items">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th style="width:5%;" class="text-nowrap">ID</th>
                        <th style="width:5%;" class="text-nowrap">{$LANG->get('order')}</th>
                        <th style="width:27%;" class="text-nowrap">{$LANG->get('title')}</th>
                        <th style="width:28%;" class="text-nowrap">{$LANG->get('description')}</th>
                        <th style="width:10%;" class="text-nowrap">{$LANG->get('categoriesnumPost')}</th>
                        <th style="width:15%;" class="text-nowrap">{$LANG->get('status')}</th>
                        <th class="text-center text-nowrap" style="width:10%;">{$LANG->get('feature')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY key=key item=row}
                    <tr>
                        <td>{$row.id}</td>
                        <td>
                            <select class="form-select form-select-sm bl-min-w60" name="weight" id="weight{$row.id}" onchange="nv_change_cat_weight({$row.id});">
                                {for $weight=1 to $NUMCAT}
                                <option value="{$weight}"{if $weight eq $row.weight} selected="selected"{/if}>{$weight}</option>
                                {/for}
                            </select>
                        </td>
                        <td>
                            {$row.title}
                            {if not empty($row.numsubs)}
                            (<a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}&amp;parentid={$row.id}">{$LANG->get('categoriesHasSub', $row.numsubs)}</a>)
                            {/if}
                        </td>
                        <td>{$row.description}</td>
                        <td><strong class="text-danger">{$row.numposts}</strong></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="change_status{$row.id}" value="1"{if $row.status eq 1} checked="checked"{/if} id="change_status{$row.id}" onclick="nv_change_cat_status({$row.id})">
                            </div>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}={$OP}&amp;id={$row.id}" class="btn btn-sm btn-hspace btn-secondary"><i class="fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-sm btn-danger" onclick="nv_delete_cat({$row.id});"><i class="fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$ERROR}
</div>
{/if}
<div class="card" id="formElement">
    <div class="card-header fs-5 fw-medium">
        {if $ID}{$LANG->get('categoriesEdit')}{else}{$LANG->get('categoriesAdd')}{/if}
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
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="formElementParentid">{$LANG->get('categoriesInCat')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-select select2" id="formElementParentid" name="parentid">
                        {foreach from=$LISTCATS key=key item=value}
                        <option value="{$value.id}"{if $value.selected} selected="selected"{/if}>{$value.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    <input type="hidden" name="id" value="{$ID}">
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
$(document).ready(function() {
    $('#formElementTitle').on('change', function() {
        if (trim($('#formElementAlias').val()) == '') {
            get_alias('formElementTitle', 'formElementAlias', 'cat');
        }
    });
    $('#formElementTitleClick').on('click', function() {
        get_alias('formElementTitle', 'formElementAlias', 'cat');
    });
});
{if $IS_SUBMIT or $ID}
$(window).on('load', function() {
    $('html, body').animate({
        scrollTop: $('#formElement').offset().top
    }, 200);
});
{/if}
</script>
