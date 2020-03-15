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
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="sysDismissAdminCache" name="sysDismissAdminCache" value="1"{if $DATA.sysDismissAdminCache} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfgsysDismissAdminCache')}</span>
                    </label>
                </div>
            </div>
            <div class="form-group row py-0">
                <label class="col-12 col-sm-3 col-form-label text-sm-right d-none d-sm-block"></label>
                <div class="col-12 col-sm-8 col-lg-6 form-check mt-1">
                    <label class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="sysRedirect2Home" name="sysRedirect2Home" value="1"{if $DATA.sysRedirect2Home} checked="checked"{/if}><span class="custom-control-label">{$LANG->get('cfgsysRedirect2Home')}</span>
                    </label>
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
