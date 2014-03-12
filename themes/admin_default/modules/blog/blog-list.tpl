<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
				<form id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="blog-input text" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.searchPost}"/>
					{LANG.filter_from}
					<input class="blog-input text bl-col-time" value="{DATA_SEARCH.from}" type="text" id="from" name="from" readonly="readonly"/>
					{LANG.filter_to}
					<input class="blog-input text bl-col-time" value="{DATA_SEARCH.to}" type="text" id="to" name="to" readonly="readonly"/>
					<select class="blog-input text" name="catid">
						<option value="0">{LANG.filter_all_cat}</option>
						<!-- BEGIN: cat -->
						<option value="{CAT.id}"{CAT.selected}>{CAT.name}</option>
						<!-- END: cat -->
					</select>
					<select class="blog-input text" name="status">
						<option value="10">{LANG.filter_all_status}</option>
						<!-- BEGIN: status -->
						<option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option>
						<!-- END: status -->
					</select>
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
	$("#from,#to").datepicker({
		showOn: "button",
		dateFormat: "dd.mm.yy",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		buttonText: '{LANG.select}',
		showButtonPanel: true,
		showOn: 'focus'
	});
	$('input[name=clear]').click(function(){
		$('#filter-form .text').val('');
	});
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();
		var f_from = $('input[name=from]').val();
		var f_to = $('input[name=to]').val();
		var f_catid = $('select[name=catid]').val();
		var f_status = $('select[name=status]').val();

		if( f_q != '' || f_from != '' || f_to != '' || f_catid != '0' || f_status != '10' ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&from=' + f_from + '&to=' + f_to + '&catid=' + f_catid + '&status=' + f_status;	
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
				<td class="center bl-col-id">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td class="bl-col-id">ID</td>
				<td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.title}</a></td>
				<td class="bl-col-number"><a href="{DATA_ORDER.postTime.data.url}" title="{DATA_ORDER.postTime.data.title}" class="{DATA_ORDER.postTime.data.class}">{LANG.blogpostTime}</a></td>
				<td class="bl-col-number"><a href="{DATA_ORDER.updateTime.data.url}" title="{DATA_ORDER.updateTime.data.title}" class="{DATA_ORDER.updateTime.data.class}">{LANG.blogupdateTime}</a></td>
				<td class="bl-col-feature">{LANG.status1}</td>
				<td class="bl-col-feature">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{ROW.class}>
			<tr class="topalign">
				<td class="center">
					<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
				</td>
				<td class="center">{ROW.id}</td>
				<td>{ROW.title}</td>
				<td class="center"><strong>{ROW.postTime}</strong></td>
				<td class="center"><strong>{ROW.updateTime}</strong></td>
				<td class="center">{ROW.statusText}</td>
				<td class="center">
					<span class="edit-icon"><a href="{ROW.urlEdit}">{GLANG.edit}</a></span>
					<span class="delete-icon"><a href="javascript:void(0);" onclick="nv_delete_post({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="8">
					{GENERATE_PAGE}
				</td>
			</tr>
		</tbody>
		<!-- END: generate_page -->
		<tfoot>
			<tr>
				<td colspan="8">
					<!-- BEGIN: action -->
					<span class="{ACTION.class}-icon"><a onclick="nv_post_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
					<!-- END: action -->
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->