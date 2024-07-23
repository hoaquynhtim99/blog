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
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysGoogleAuthor">{$LANG->get('cfgsysGoogleAuthor')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="sysGoogleAuthor" name="sysGoogleAuthor" value="{$DATA.sysGoogleAuthor}">
                    <div class="form-text text-muted">{$LANG->get('cfgsysGoogleAuthorNote')}</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysFbAppID">{$LANG->get('cfgsysFbAppID')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="sysFbAppID" name="sysFbAppID" value="{$DATA.sysFbAppID}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysFbAdminID">{$LANG->get('cfgsysFbAdminID')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="text" class="form-control" id="sysFbAdminID" name="sysFbAdminID" value="{$DATA.sysFbAdminID}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysLocale">{$LANG->get('cfgsysLocale')}</label>
                <div class="col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                    <select class="form-select" id="sysLocale" name="sysLocale">
                        {foreach from=$ARRAY_LOCALES key=key item=value}
                        <option value="{$key}"{if $key eq $DATA.sysLocale} selected="selected"{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="sysDefaultImage">{$LANG->get('cfgsysDefaultImage')}</label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 flex-shrink-1">
                            <input type="text" class="form-control" id="sysDefaultImage" name="sysDefaultImage" value="{$DATA.sysDefaultImage}">
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 pl-2">
                            <button class="btn btn-secondary btn-input-sm" type="button" id="sysDefaultImageBtn"><i class="icon icon-left far fa-folder-open"></i> {$LANG->get('browse_image')}</button>
                        </div>
                    </div>
                    <div class="form-text text-muted">{$LANG->get('cfgsysDefaultImageNote')}</div>
                </div>
            </div>
            <div class="form-group row mb-0 pb-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-end"></label>
                <div class="col-12 col-sm-8 col-lg-6">
                    <input type="hidden" name="tokend" value="{$TOKEND}">
                    <button class="btn btn-space btn-primary" type="submit">{$LANG->get('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}=upload&amp;js"></script>
<script type="text/javascript">
$(document).on("nv.upload.ready", function() {
    $("#sysDefaultImageBtn").nvBrowseFile({
        adminBaseUrl: '{$NV_BASE_ADMINURL}',
        path: '{$UPLOADS_PATH}',
        currentpath: '{$UPLOADS_PATH}',
        type: 'image',
        area: '#sysDefaultImage'
    });
});
</script>
