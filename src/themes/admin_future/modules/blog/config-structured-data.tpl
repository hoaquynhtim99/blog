{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$ERROR}
</div>
{/if}
<div class="card">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysGoogleAuthor">{$LANG->get('cfgsysGoogleAuthor')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="sysGoogleAuthor" name="sysGoogleAuthor" value="{$DATA.sysGoogleAuthor}">
                    <div class="form-text text-muted">{$LANG->get('cfgsysGoogleAuthorNote')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysFbAppID">{$LANG->get('cfgsysFbAppID')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="sysFbAppID" name="sysFbAppID" value="{$DATA.sysFbAppID}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysFbAdminID">{$LANG->get('cfgsysFbAdminID')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="sysFbAdminID" name="sysFbAdminID" value="{$DATA.sysFbAdminID}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysLocale">{$LANG->get('cfgsysLocale')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-select select2" id="sysLocale" name="sysLocale">
                        {foreach from=$ARRAY_LOCALES key=key item=value}
                        <option value="{$key}"{if $key eq $DATA.sysLocale} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysDefaultImage">{$LANG->get('cfgsysDefaultImage')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control" id="sysDefaultImage" name="sysDefaultImage" value="{$DATA.sysDefaultImage}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 ps-2">
                            <button class="btn btn-secondary" type="button" id="sysDefaultImageBtn" data-toggle="selectfile" data-target="sysDefaultImage" data-path="{$UPLOADS_PATH}" data-currentpath="{$UPLOADS_PATH}" data-type="image"><i class="icon icon-left far fa-folder-open"></i> {$LANG->get('browse_image')}</button>
                        </div>
                    </div>
                    <div class="form-text text-muted">{$LANG->get('cfgsysDefaultImageNote')}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 offset-sm-3">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
