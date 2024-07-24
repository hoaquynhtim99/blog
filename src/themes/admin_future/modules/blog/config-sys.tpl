{if not empty($ERROR)}
<div role="alert" class="alert alert-danger">
    <i class="far fa-times-circle"></i> {$ERROR}
</div>
{/if}
<div class="card">
    <div class="card-body">
        <form method="post" action="{$FORM_ACTION}" autocomplete="off">
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="sysDismissAdminCache" name="sysDismissAdminCache" value="1"{if $DATA.sysDismissAdminCache} checked="checked"{/if}>
                        <label for="sysDismissAdminCache" class="form-check-label">{$LANG->get('cfgsysDismissAdminCache')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 mt-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="sysRedirect2Home" name="sysRedirect2Home" value="1"{if $DATA.sysRedirect2Home} checked="checked"{/if}>
                        <label for="sysRedirect2Home" class="form-check-label">{$LANG->get('cfgsysRedirect2Home')}</label>
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
