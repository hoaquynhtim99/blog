<div class="card card-table card-footer-nav">
    <div class="card-body">
        <form method="get" action="{$NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="row card-body-search-form">
                <div class="col-12 col-lg-12 col-xl-6 mb-2">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <label for="formEleQ">{$LANG->get('keywordsSoft')}:</label>
                            <input type="text" class="form-control form-control-sm" id="formEleQ" name="q" value="{$DATA_SEARCH.q}">
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="formEleCat">{$LANG->get('blogInCats')}:</label>
                            <select class="select2" name="catid" id="formEleCat">
                                <option value="0">{$LANG->get('filter_all_cat')}</option>
                                {foreach from=$LIST_CATS key=key item=value}
                                <option value="{$value.id}"{if $value.selected} selected="selected"{/if}>{$value.name}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 col-xl-6 mb-2">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-6">
                                    <label for="formEleFrom">{$LANG->get('filter_from')}:</label>
                                    <input class="form-control form-control-sm bsdatepicker" value="{$DATA_SEARCH.from}" type="text" id="formEleFrom" name="from" autocomplete="off">
                                </div>
                                <div class="col-6">
                                    <label for="formEleTo">{$LANG->get('filter_to')}:</label>
                                    <input class="form-control form-control-sm bsdatepicker" value="{$DATA_SEARCH.to}" type="text" id="formEleTo" name="to" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="formEleStatus">{$LANG->get('status1')}:</label>
                            <select class="form-control form-control-sm" name="status" id="formEleStatus">
                                <option value="10">{$LANG->get('filter_all_status')}</option>
                                {foreach from=$BLOGSTATUS key=key item=value}
                                <option value="{$value}"{if $value eq $DATA_SEARCH.status} selected="selected"{/if}>{$LANG->get("blogStatus`$value`")}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 col-xl-6">
                    <button type="submit" class="btn btn-primary"><i class="icon icon-left fas fa-search"></i> {$LANG->get('filter_action')}</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width:5%;" class="text-nowrap">
                            <label class="custom-control custom-control-sm custom-checkbox">
                                <input class="custom-control-input" type="checkbox" data-toggle="BLCheckAll" name="BLIdItems" data-target="[name='BLIdItem[]']"><span class="custom-control-label"></span>
                            </label>
                        </th>
                        <th style="width:35%;" class="text-nowrap"><a href="{$DATA_ORDER.title.data.url}" title="{$DATA_ORDER.title.data.title}">{if $DATA_ORDER.title.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.title.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('title')}</a></th>
                        <th style="width:15%;" class="text-nowrap"><a href="{$DATA_ORDER.posttime.data.url}" title="{$DATA_ORDER.posttime.data.title}">{if $DATA_ORDER.posttime.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.posttime.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('blogposttime')}</a></th>
                        <th style="width:15%;" class="text-nowrap"><a href="{$DATA_ORDER.updatetime.data.url}" title="{$DATA_ORDER.updatetime.data.title}">{if $DATA_ORDER.updatetime.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.updatetime.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('blogupdatetime')}</a></th>
                        <th style="width:15%;" class="text-nowrap">{$LANG->get('status')}</th>
                        <th class="text-right text-nowrap" style="width:15%;">{$LANG->get('feature')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY key=key item=row}
                    <tr>
                        <td>
                            <label class="custom-control custom-control-sm custom-checkbox">
                                <input class="custom-control-input" type="checkbox" data-toggle="BLUncheckAll" name="BLIdItem[]" data-target="[name='BLIdItems']" value="{$row.id}"><span class="custom-control-label"></span>
                            </label>
                        </td>
                        <td>
                            #{$row.id}
                            <div>
                                {if $row.status eq 1}
                                <a href="{$row.link}" target="_blank">{$row.title}</a>
                                {else}
                                {if empty($row.title)}
                                <i class="text-muted">{$LANG->get('blogDraftNoTitle')}</i>
                                {else}
                                {$row.title}
                                {/if}
                                {/if}
                            </div>
                        </td>
                        <td>
                            {if not empty($row.posttime)}{"H:i d/m/Y"|date:$row.posttime}{else}{/if}
                        </td>
                        <td>
                            {if not empty($row.updatetime)}{"H:i d/m/Y"|date:$row.updatetime}{else}{/if}
                        </td>
                        <td>
                            {$LANG->get("blogStatus`$row.status`")}
                        </td>
                        <td class="text-right text-nowrap">
                            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=blog-content&amp;id={$row.id}" class="btn btn-hspace btn-secondary"><i class="icon icon-left fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-danger" onclick="nv_delete_post({$row.id});"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
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
                    <a class="dropdown-item" href="javascript:void(0);" onclick="nv_post_action('[name=\'BLIdItem[]\']', '{$LANG->get('alert_check')}', 2);"><i class="icon fas fa-toggle-on"></i> {$LANG->get('action_status_public')}</a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="nv_post_action('[name=\'BLIdItem[]\']', '{$LANG->get('alert_check')}', 3);"><i class="icon fas fa-toggle-off"></i> {$LANG->get('action_status_no')}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="nv_post_action('[name=\'BLIdItem[]\']', '{$LANG->get('alert_check')}', 1);"><i class="icon far fa-times-circle"></i> {$LANG->get('delete')}</a>
                </div>
            </div>
        </div>
        {if not empty($GENERATE_PAGE)}
        <nav class="page-nav">{$GENERATE_PAGE}</nav>
        {/if}
    </div>
</div>
<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.css">
<link data-offset="0" rel="stylesheet" href="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/select2/i18n/{$NV_LANG_INTERFACE}.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/bootstrap-datepicker/locales/bootstrap-datepicker.{$NV_LANG_INTERFACE}.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#formEleCat").select2({
        width: "100%",
        containerCssClass: "select2-sm"
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
        format: 'dd.mm.yyyy'
    });
});
</script>
