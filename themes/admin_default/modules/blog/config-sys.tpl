<!-- BEGIN: main -->
<form class="form-inline" method="post" action="{FORM_ACTION}">
<table class="table table-striped table-bordered table-hover">
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgsysDismissAdminCache}</strong>
			</td>
			<td>
				<input type="checkbox" class="" name="sysDismissAdminCache" value="1"{SYSDISMISSADMINCACHE}/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgsysRedirect2Home}</strong>
			</td>
			<td>
				<input type="checkbox" class="" name="sysRedirect2Home" value="1"{SYSREDIRECT2HOME}/>
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
<!-- END: main -->