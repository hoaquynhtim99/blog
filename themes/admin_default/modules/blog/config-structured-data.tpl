<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
<table class="tab1">
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgsysGoogleAuthor}</strong>
			</td>
			<td>
				<input type="text" class="blog-input bl-txt-h" name="sysGoogleAuthor" value="{DATA.sysGoogleAuthor}"/>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfgsysFbAppID}</strong>
			</td>
			<td>
				<input type="text" class="blog-input bl-txt-h" name="sysFbAppID" value="{DATA.sysFbAppID}"/>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgsysLocale}</strong>
			</td>
			<td>
				<select name="sysLocale" class="blog-input">
					<!-- BEGIN: sysLocale --><option value="{SYSLOCALE.key}"{SYSLOCALE.selected}>{SYSLOCALE.title}</option><!-- END: sysLocale -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgsysDefaultImage}</strong>
			</td>
			<td>
				<input type="text" class="blog-input bl-txt-q" name="sysDefaultImage" id="sysDefaultImage" value="{DATA.sysDefaultImage}"/>
				<input type="button" class="blog-button-2" name="imagesBrowser" id="imagesBrowser" value="{LANG.browser}" onclick="nv_open_browse_file(script_name + '?' + nv_name_variable + '=upload&popup=1&area=sysDefaultImage&path={UPLOADS_PATH}&type=image', 'NVImg', '850', '420', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');"/>
				<input type="button" class="blog-button-2" name="imagesView" id="imagesView" value="{LANG.view}" onclick="viewImages();"/>
			</td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<tbody>
		<tr>
			<td class="center"><input class="blog-button" type="submit" name="submit" value="{LANG.save}"/></td>
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
	Shadowbox.open({
		content : images,
		player : 'img',
		hanleOversize: 'resize'
	});
}
</script>
<!-- END: main -->