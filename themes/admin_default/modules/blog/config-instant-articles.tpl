<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}" autocomplete="off">
    <div class="panel panel-default">
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="instantArticlesActive">{LANG.cfgInsArtActive}</label>
                <div class="col-sm-16 col-md-10 col-lg-9">
                    <div class="checkbox">
                        <label><input type="checkbox" name="instantArticlesActive" id="instantArticlesActive" value="1"{INSTANTARTICLESACTIVE}></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="instantArticlesTemplate">{LANG.cfgInsArtTemplate}</label>
                <div class="col-sm-16 col-md-10 col-lg-9 form-inline">
                    <input type="text" class="form-control" name="instantArticlesTemplate" id="instantArticlesTemplate" value="{DATA.instantArticlesTemplate}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="instantArticlesHttpauth">{LANG.cfgInsArtHttpauth}</label>
                <div class="col-sm-16 col-md-10 col-lg-9">
                    <div class="checkbox">
                        <label><input type="checkbox" name="instantArticlesHttpauth" id="instantArticlesHttpauth" value="1"{INSTANTARTICLESHTTPAUTH}></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="instantArticlesUsername">{LANG.cfgInsArtUsername}</label>
                <div class="col-sm-16 col-md-10 col-lg-9 form-inline">
                    <input type="text" class="form-control form-inline" name="instantArticlesUsername" id="instantArticlesUsername" value="{DATA.instantArticlesUsername}" autocomplete="new-password">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="instantArticlesPassword">{LANG.cfgInsArtPassword}</label>
                <div class="col-sm-16 col-md-10 col-lg-9 form-inline">
                    <div class="input-group">
                        <input type="password" class="form-control" name="instantArticlesPassword" id="instantArticlesPassword" value="{DATA.instantArticlesPassword}" autocomplete="new-password">
                        <div class="input-group-btn">
                            <button id="instantArticlesPasswordShowHide" type="button" tabindex="-1" class="btn btn-default" data-toggle="tooltip" title="{LANG.show_hide_pass}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="instantArticlesLivetime">{LANG.cfgInsArtLivetime}</label>
                <div class="col-sm-16 col-md-10 col-lg-9 form-inline">
                    <input type="number" class="form-control" name="instantArticlesLivetime" id="instantArticlesLivetime" value="{DATA.instantArticlesLivetime}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-8 col-md-6 control-label" for="instantArticlesGettime">{LANG.cfgInsArtGettime}</label>
                <div class="col-sm-16 col-md-10 col-lg-9 form-inline">
                    <input type="number" class="form-control" name="instantArticlesGettime" id="instantArticlesGettime" value="{DATA.instantArticlesGettime}">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-16 col-md-10 col-lg-9 col-sm-offset-8 col-md-offset-6">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">{GLANG.submit}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<h2 class="blog-panel-title mb-3">{LANG.cfgInsArtToolLink}</h2>
<div class="panel panel-default">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <label class="col-sm-8 col-md-6 control-label" for="instantArticlesArea">{LANG.cfgInsArtToolSelArea}</label>
            <div class="col-sm-16 col-md-10 col-lg-9 form-inline">
                <select class="form-control" id="instantArticlesArea">
                    <option value="">{LANG.cfgInsArtToolSelArea}</option>
                    <option value="{RSS_LINK_ALL}">{LANG.cfgInsArtToolSelAreaAll}</option>
                    <!-- BEGIN: cat -->
                    <option value="{CAT.rss_link}">{CAT.name}</option>
                    <!-- END: cat -->
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-8 col-md-6 control-label">{LANG.cfgInsArtToolResLink}</label>
            <div class="col-sm-16 col-md-18">
                <div class="checkbox">
                    <label class="pl-0"><strong id="instantArticlesLink"></strong></label>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#instantArticlesPasswordShowHide').on("click", function() {
        var icon = $(this).find('i');
        if (icon.is(".fa-eye")) {
            icon.removeClass("fa-eye");
            icon.addClass("fa-eye-slash");
            $("#instantArticlesPassword").prop("type", "text");
        } else {
            icon.addClass("fa-eye");
            icon.removeClass("fa-eye-slash");
            $("#instantArticlesPassword").prop("type", "password");
        }
    });
    $("#instantArticlesArea").on("change", function() {
        $("#instantArticlesLink").html($(this).val());
    });
    $("#instantArticlesArea").select2();
});
</script>
<!-- END: main -->
