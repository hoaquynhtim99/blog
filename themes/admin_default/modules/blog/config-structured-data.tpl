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
</table>
<table class="tab1">
	<tbody>
		<tr>
			<td class="center"><input class="blog-button" type="submit" name="submit" value="{LANG.save}"/></td>
		</tr>
	</tbody>
</table>
</form>
<!-- END: main -->