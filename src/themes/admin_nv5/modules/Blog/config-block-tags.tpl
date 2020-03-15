{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
<div class="card card-border-color card-border-color-primary">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="blockTagsShowType">{$LANG->get('cfgblockTagsShowType')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-control form-control-sm" id="blockTagsShowType" name="blockTagsShowType">
                        {foreach from=$SHOWTYPE key=key item=value}
                        <option value="{$value}"{if $value eq $DATA.blockTagsShowType} selected="selected"{/if}>{$LANG->get("cfgblockTagsShowType_`$value`")}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="blockTagsNums">{$LANG->get('cfgblockTagsNums')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <input type="number" class="form-control form-control-sm" id="blockTagsNums" name="blockTagsNums" value="{$DATA.blockTagsNums}" min="0" max="999">
                    </div>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="blockTagsCacheIfRandom" name="blockTagsCacheIfRandom" value="1"{if $DATA.blockTagsCacheIfRandom} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfgblockTagsCacheIfRandom')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-right" for="blockTagsNums">{$LANG->get('cfgblockTagsCacheLive')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="form-inline">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 flex-shrink-1">
                                <input type="number" class="form-control form-control-sm" id="blockTagsCacheLive" name="blockTagsCacheLive" value="{$DATA.blockTagsCacheLive}" min="0">
                            </div>
                            <div class="pl-1"><i>({$LANG->get('min')})</i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
