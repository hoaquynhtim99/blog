<div class="card">
    <div class="card-body">
        <form method="get" action="{$NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="row g-3 flex-xl-nowrap">
                <div class="col-md-6 flex-lg-fill">
                    <label class="form-label" for="formEleQ">{$LANG->get('keywordsSoft')}:</label>
                    <input type="text" class="form-control" id="formEleQ" name="q" value="{$DATA_SEARCH.q}">
                </div>
                <div class="col-md-6 flex-lg-fill">
                    <label class="form-label" for="formEleCat">{$LANG->get('blogInCats')}:</label>
                    <select class="form-select select2" name="catid" id="formEleCat">
                        <option value="0">{$LANG->get('filter_all_cat')}</option>
                        {foreach from=$LIST_CATS key=key item=value}
                        <option value="{$value.id}"{if $value.id eq {$DATA_SEARCH.catid}} selected="selected"{/if}>{if $value.cat_level gt 1}{for $foo=2 to $value.cat_level} &nbsp; &nbsp;{/for}{/if}{$value.title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-6 flex-lg-fill">
                    <label class="form-label" for="formEleFrom">{$LANG->get('filter_from')}:</label class="form-label">
                    <input class="form-control datepicker-get" value="{$DATA_SEARCH.from}" type="text" id="formEleFrom" name="from" autocomplete="off">
                </div>
                <div class="col-md-6 flex-lg-fill">
                    <label class="form-label" for="formEleTo">{$LANG->get('filter_to')}:</label>
                    <input class="form-control datepicker-get" value="{$DATA_SEARCH.to}" type="text" id="formEleTo" name="to" autocomplete="off">
                </div>
                <div class="col-md-6 flex-lg-fill">
                    <label class="form-label" for="formEleStatus">{$LANG->get('status1')}:</label>
                    <select class="form-select" name="status" id="formEleStatus">
                        <option value="10">{$LANG->get('filter_all_status')}</option>
                        {foreach from=$BLOGSTATUS key=key item=value}
                        <option value="{$value}"{if $value eq $DATA_SEARCH.status} selected="selected"{/if}>{$LANG->get("blogStatus`$value`")}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="flex-grow-0 flex-shrink-1 w-auto">
                    <label class="form-label d-none d-sm-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary text-nowrap"><i class="fas fa-search"></i> {$LANG->get('filter_action')}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th style="width:1%;" class="text-nowrap">
                            <input class="form-check-input" type="checkbox" data-toggle="checkAll" name="BLIdItems" data-target="[name='BLIdItem[]']">
                        </th>
                        <th style="width:35%;" class="text-nowrap"><a href="{$DATA_ORDER.title.data.url}" title="{$DATA_ORDER.title.data.title}">{if $DATA_ORDER.title.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.title.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('title')}</a></th>
                        <th style="width:16%;" class="text-nowrap"><a href="{$DATA_ORDER.posttime.data.url}" title="{$DATA_ORDER.posttime.data.title}">{if $DATA_ORDER.posttime.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.posttime.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('blogposttime')}</a></th>
                        <th style="width:16%;" class="text-nowrap"><a href="{$DATA_ORDER.updatetime.data.url}" title="{$DATA_ORDER.updatetime.data.title}">{if $DATA_ORDER.updatetime.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.updatetime.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('blogupdatetime')}</a></th>
                        <th style="width:16%;" class="text-nowrap">{$LANG->get('status')}</th>
                        <th class="text-right text-nowrap" style="width:16%;">{$LANG->get('feature')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY key=key item=row}
                    <tr>
                        <td>
                            <input class="form-check-input" type="checkbox" data-toggle="checkSingle" name="BLIdItem[]" data-target="[name='BLIdItems']" value="{$row.id}">
                        </td>
                        <td>
                            <span class="badge text-bg-secondary">#{$row.id}</span>
                            {if $row.status eq 1}
                            <a href="{$row.link}" target="_blank">{$row.title}</a>
                            {else}
                            {if empty($row.title)}
                            <i class="text-muted">{$LANG->get('blogDraftNoTitle')}</i>
                            {else}
                            {$row.title}
                            {/if}
                            {/if}
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
                            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=blog-content&amp;id={$row.id}" class="btn btn-sm btn-secondary"><i class="fas fa-pencil-alt"></i> {$LANG->get('edit')}</a>
                            <a href="#" class="btn btn-sm btn-danger" onclick="nv_delete_post({$row.id});"><i class="fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                <div class="me-2">
                    <input type="checkbox" data-toggle="checkAll" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                </div>
                <div class="input-group me-1 my-1">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {$LANG->get('withSelectedRow')}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-toggle="blAction" data-action="2" data-mgs="{$LANG->get('alert_check')}"><i class="fas fa-toggle-on"></i> {$LANG->get('action_status_public')}</a></li>
                            <li><a class="dropdown-item" href="#" data-toggle="blAction" data-action="3" data-mgs="{$LANG->get('alert_check')}"><i class="fas fa-toggle-off"></i> {$LANG->get('action_status_no')}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-toggle="blAction" data-action="1" data-mgs="{$LANG->get('alert_check')}"><i class="far fa-times-circle text-danger"></i> {$LANG->get('delete')}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pagination-wrap">
                {$GENERATE_PAGE}
            </div>
        </div>
    </div>
</div>
<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
