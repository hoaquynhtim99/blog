<div class="card">
    <div class="card-body">
        <form method="get" action="{$NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$NV_LANG_VARIABLE}" value="{$NV_LANG_DATA}">
            <input type="hidden" name="{$NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$NV_OP_VARIABLE}" value="{$OP}">
            <div class="row g-3 flex-nowrap">
                <div class="col-auto flex-fill flex-md-grow-0 flex-md-shrink-0">
                    <label class="form-label" for="formElementQ">{$LANG->get('searchEmail')}</label>
                    <input type="text" class="form-control mb-2 me-sm-2" id="formElementQ" name="q" value="{$DATA_SEARCH.q}" placeholder="{$LANG->get('searchEmail')}">
                </div>
                <div class="flex-grow-0 flex-shrink-1 w-auto">
                    <label class="form-label d-block">&nbsp;</label>
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
                        <th style="width:5%;" class="text-nowrap">
                            <input class="form-check-input" type="checkbox" data-toggle="checkAll" name="BLIdItems" data-target="[name='BLIdItem[]']">
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
                            <input class="form-check-input" type="checkbox" data-toggle="checkSingle" name="BLIdItem[]" data-target="[name='BLIdItems']" value="{$row.id}">
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
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="nv_delete_newsletters({$row.id});"><i class="fas fa-trash-alt"></i> {$LANG->get('delete')}</a>
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
                            <li><a class="dropdown-item" href="#" data-toggle="newslettersAction" data-action="2" data-mgs="{$LANG->get('alert_check')}"><i class="fas fa-toggle-on"></i> {$LANG->get('action_status_ok')}</a></li>
                            <li><a class="dropdown-item" href="#" data-toggle="newslettersAction" data-action="3" data-mgs="{$LANG->get('alert_check')}"><i class="fas fa-toggle-off"></i> {$LANG->get('action_status_no')}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-toggle="newslettersAction" data-action="1" data-mgs="{$LANG->get('alert_check')}"><i class="far fa-times-circle text-danger"></i> {$LANG->get('delete')}</a></li>
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
