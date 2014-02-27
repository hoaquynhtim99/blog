<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
				<form id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="blog-input text" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.searchEmail}"/>
					<input class="blog-button" type="button" name="do" value="{LANG.filter_action}"/>
					<input class="blog-button" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input class="blog-button" type="button" name="clear" value="{LANG.filter_clear}"/>
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
<form action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="tab1">
		<thead>
			<tr>
				<td class="center bl-col-order">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td><a href="{DATA_ORDER.email.data.url}" title="{DATA_ORDER.email.data.title}" class="{DATA_ORDER.email.data.class}">{LANG.nltEmail}</a></td>
				<td class="bl-col-number">{LANG.nltregIP}</td>
				<td class="bl-col-number"><a href="{DATA_ORDER.regTime.data.url}" title="{DATA_ORDER.regTime.data.title}" class="{DATA_ORDER.regTime.data.class}">{LANG.nltregTime}</a></td>
				<td class="bl-col-number">{LANG.nltconfirmTime}</td>
				<td class="bl-col-number"><a href="{DATA_ORDER.lastSendTime.data.url}" title="{DATA_ORDER.lastSendTime.data.title}" class="{DATA_ORDER.lastSendTime.data.class}">{LANG.nltlastSendTime}</a></td>
				<td class="bl-col-number"><a href="{DATA_ORDER.numEmail.data.url}" title="{DATA_ORDER.numEmail.data.title}" class="{DATA_ORDER.numEmail.data.class}">{LANG.nltnumEmail}</a></td>
				<td class="center bl-col-feature">{LANG.status1}</td>
				<td class="center bl-col-feature">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{ROW.class}>
			<tr class="topalign">
				<td class="center">
					<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
				</td>
				<td>{ROW.email}</td>
				<td><strong>{ROW.regIP}</strong></td>
				<td><strong>{ROW.regTime}</strong></td>
				<td><strong>{ROW.confirmTime}</strong></td>
				<td><strong>{ROW.lastSendTime}</strong></td>
				<td><strong>{ROW.numEmail}</strong></td>
				<td>{ROW.status}</td>
				<td class="center">
					<span class="delete-icon"><a class="nounderline" href="javascript:void(0);" onclick="nv_delete_newsletters({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="9">
					{GENERATE_PAGE}
				</td>
			</tr>
		</tbody>
		<!-- END: generate_page -->
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