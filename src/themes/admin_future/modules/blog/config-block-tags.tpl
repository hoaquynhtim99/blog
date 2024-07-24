{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$ERROR}
</div>
{/if}
<div class="card">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="blockTagsShowType">{$LANG->get('cfgblockTagsShowType')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-select" id="blockTagsShowType" name="blockTagsShowType">
                        {foreach from=$SHOWTYPE key=key item=value}
                        <option value="{$value}"{if $value eq $DATA.blockTagsShowType} selected="selected"{/if}>{$LANG->get("cfgblockTagsShowType_`$value`")}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="blockTagsNums">{$LANG->get('cfgblockTagsNums')}</label>
                <div class="col-12 col-sm-8 col-lg-3">
                    <div class="form-inline">
                        <input type="number" class="form-control" id="blockTagsNums" name="blockTagsNums" value="{$DATA.blockTagsNums}" min="0" max="999">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="blockTagsCacheIfRandom" name="blockTagsCacheIfRandom" value="1"{if $DATA.blockTagsCacheIfRandom} checked="checked"{/if}>
                        <label for="blockTagsCacheIfRandom" class="form-check-label">{$LANG->get('cfgblockTagsCacheIfRandom')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="blockTagsCacheLive">{$LANG->get('cfgblockTagsCacheLive')}</label>
                <div class="col-12 col-sm-8 col-lg-3">
                    <div class="form-inline">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 flex-shrink-1">
                                <input type="number" class="form-control" id="blockTagsCacheLive" name="blockTagsCacheLive" value="{$DATA.blockTagsCacheLive}" min="0">
                            </div>
                            <div class="ps-1"><i>({$LANG->get('min')})</i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
