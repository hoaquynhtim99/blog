<!-- BEGIN: main -->
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td>
				<form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="form-control text" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.searchEmail}"/>
					<input class="btn btn-primary" type="button" name="do" value="{LANG.filter_action}"/>
					<input class="btn btn-primary" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input class="btn btn-primary" type="button" name="clear" value="{LANG.filter_clear}"/>
				</form>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$('input[name=clear]').click(function(){
		$('#filter-form .text').val('');
	});
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();

		if( f_q != '' ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q;	
		}else{
			alert ('{LANG.filter_err_submit}');
		}
	});
});
</script>
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td class="center bl-col-order">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td><a href="{DATA_ORDER.email.data.url}" title="{DATA_ORDER.email.data.title}" class="{DATA_ORDER.email.data.class}">{LANG.nltEmail}</a></td>
				<td class="bl-col-number">{LANG.nltregip}</td>
				<td class="bl-col-number"><a href="{DATA_ORDER.regtime.data.url}" title="{DATA_ORDER.regtime.data.title}" class="{DATA_ORDER.regtime.data.class}">{LANG.nltregtime}</a></td>
				<td class="bl-col-number">{LANG.nltconfirmtime}</td>
				<td class="bl-col-number"><a href="{DATA_ORDER.lastsendtime.data.url}" title="{DATA_ORDER.lastsendtime.data.title}" class="{DATA_ORDER.lastsendtime.data.class}">{LANG.nltlastsendtime}</a></td>
				<td class="bl-col-number"><a href="{DATA_ORDER.numemail.data.url}" title="{DATA_ORDER.numemail.data.title}" class="{DATA_ORDER.numemail.data.class}">{LANG.nltnumemail}</a></td>
				<td class="center bl-col-feature">{LANG.status1}</td>
				<td class="center bl-col-feature">{LANG.feature}</td>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
			<tr class="topalign">
				<td class="text-center">
					<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
				</td>
				<td>{ROW.email}</td>
				<td><strong>{ROW.regip}</strong></td>
				<td><strong>{ROW.regtime}</strong></td>
				<td><strong>{ROW.confirmtime}</strong></td>
				<td><strong>{ROW.lastsendtime}</strong></td>
				<td><strong>{ROW.numemail}</strong></td>
				<td>{ROW.status}</td>
				<td class="text-center">
					<span class="delete-icon"><a class="nounderline" href="javascript:void(0);" onclick="nv_delete_newsletters({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		<!-- END: row -->
		<tbody>
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="9">
					{GENERATE_PAGE}
				</td>
			</tr>
		<!-- END: generate_page -->
		<tbody>
		<tfoot>
			<tr>
				<td colspan="9">
					<!-- BEGIN: action -->
					<span class="{ACTION.class}-icon"><a onclick="nv_newsletters_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
					<!-- END: action -->
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->