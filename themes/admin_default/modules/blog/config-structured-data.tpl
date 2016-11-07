<!-- BEGIN: main -->
<form class="form-inline" method="post" action="{FORM_ACTION}">
<table class="table table-striped table-bordered table-hover">
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgsysGoogleAuthor}</strong>
			</td>
			<td>
				<input type="text" class="form-control bl-txt-h" name="sysGoogleAuthor" value="{DATA.sysGoogleAuthor}"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgsysFbAppID}</strong>
			</td>
			<td>
				<input type="text" class="form-control bl-txt-h" name="sysFbAppID" value="{DATA.sysFbAppID}"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgsysFbAdminID}</strong>
			</td>
			<td>
				<input type="text" class="form-control bl-txt-h" name="sysFbAdminID" value="{DATA.sysFbAdminID}"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgsysLocale}</strong>
			</td>
			<td>
				<select name="sysLocale" class="form-control">
					<!-- BEGIN: sysLocale --><option value="{SYSLOCALE.key}"{SYSLOCALE.selected}>{SYSLOCALE.title}</option><!-- END: sysLocale -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgsysDefaultImage}</strong>
			</td>
			<td>
				<input type="text" class="form-control bl-txt-q" name="sysDefaultImage" id="sysDefaultImage" value="{DATA.sysDefaultImage}"/>
				<input type="button" class="btn btn-default" name="imagesBrowser" id="imagesBrowser" value="{LANG.browser}" onclick="nv_open_browse(script_name + '?' + nv_name_variable + '=upload&popup=1&area=sysDefaultImage&path={UPLOADS_PATH}&type=image', 'NVImg', '850', '420', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');"/>
				<input type="button" class="btn btn-default" name="imagesView" id="imagesView" value="{LANG.view}" onclick="viewImages();"/>
			</td>
		</tr>
	</tbody>
</table>
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td class="text-center"><input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}"/></td>
		</tr>
	</tbody>
</table>
</form>
<script type="text/javascript">
function viewImages(){
	var images = $('#sysDefaultImage').val();
	if( images == '' ){
		return;
	}
    modalShow('{LANG.view_before_image}', '<div class="text-center"><img src="' + images + '" class="img-responsive"/></div>');
}
</script>
<!-- END: main -->