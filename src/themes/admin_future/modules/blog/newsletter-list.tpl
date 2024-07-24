<div class="card">
    <div class="card-body">
        <form method="get" action="{$NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="card-body-search-form pt-4 pb-2 px-4 form-inline">
                <div class="input-group bl-min-w-25">
                    <label class="sr-only" for="formElementQ">{$LANG->get('searchEmail')}</label>
                    <input type="text" class="form-control mb-2 me-sm-2" id="formElementQ" name="q" value="{$DATA_SEARCH.q}" placeholder="{$LANG->get('searchEmail')}">
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
                        <th style="width:20%;" class="text-nowrap"><a href="{$DATA_ORDER.email.data.url}" title="{$DATA_ORDER.email.data.title}">{if $DATA_ORDER.email.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.email.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('nltEmail')}</a></th>
                        <th style="width:20%;" class="text-nowrap"><a href="{$DATA_ORDER.regtime.data.url}" title="{$DATA_ORDER.regtime.data.title}">{if $DATA_ORDER.regtime.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.regtime.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('nltregtime')}</a></th>
                        <th style="width:15%;" class="text-nowrap"><a href="{$DATA_ORDER.lastsendtime.data.url}" title="{$DATA_ORDER.lastsendtime.data.title}">{if $DATA_ORDER.lastsendtime.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.lastsendtime.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('nltlastsendtime')}</a></th>
                        <th style="width:15%;" class="text-nowrap"><a href="{$DATA_ORDER.numemail.data.url}" title="{$DATA_ORDER.numemail.data.title}">{if $DATA_ORDER.numemail.data.key eq 'asc'}<i class="fas fa-sort-amount-down-alt"></i> {elseif $DATA_ORDER.numemail.data.key eq 'desc'}<i class="fas fa-sort-amount-up"></i> {/if}{$LANG->get('nltnumemail')}</a></th>
                        <th style="width:15%;" class="text-nowrap">{$LANG->get('status1')}</th>
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
                        <td class="cell-detail">
                            <div>{$row.email}</div>
                            <span class="cell-detail-description">{$LANG->get('nltregip')}: {$row.regip}</span>
                        </td>
                        <td class="cell-detail">
                            <div>{"H:i d/m/Y"|date:$row.regtime}</div>
                            <span class="cell-detail-description">{if empty($row.confirmtime)}{$LANG->get('nltnotcomfirm')}{else}{"H:i d/m/Y"|date:$row.confirmtime}{/if}</span>
                        </td>
                        <td>{if empty($row.lastsendtime)}{$LANG->get('nltlastsendtimeno')}{else}{"H:i d/m/Y"|date:$row.lastsendtime}{/if}</td>
                        <td><strong class="text-danger">{$row.numemail|format:0:",":"."}</strong></td>
                        <td>{$LANG->get("nltstatus`$row.status`")}</td>
                        <td class="text-end text-nowrap">
                            <a href="javascript:void(0);" class="btn btn-danger" onclick="nv_delete_newsletters({$row.id});"><i class="icon icon-left fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
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
                    <a class="dropdown-item" href="javascript:void(0);" onclick="nv_newsletters_action('[name=\'BLIdItem[]\']', '{$LANG->get('alert_check')}', 1);"><i class="icon far fa-times-circle"></i> {$LANG->get('delete')}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="nv_newsletters_action('[name=\'BLIdItem[]\']', '{$LANG->get('alert_check')}', 2);"><i class="icon fas fa-toggle-on"></i> {$LANG->get('action_status_ok')}</a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="nv_newsletters_action('[name=\'BLIdItem[]\']', '{$LANG->get('alert_check')}', 3);"><i class="icon fas fa-toggle-off"></i> {$LANG->get('action_status_no')}</a>
                </div>
            </div>
        </div>
        {if not empty($GENERATE_PAGE)}
        <nav class="page-nav">{$GENERATE_PAGE}</nav>
        {/if}
    </div>
</div>
