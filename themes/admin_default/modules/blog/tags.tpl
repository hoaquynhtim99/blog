<!-- BEGIN: main -->
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td>
				<form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="form-control text" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.searchTags}"/>
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
				<td class="center bl-col-id">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td class="bl-col-id">ID</td>
				<td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.title}</a></td>
				<td>{LANG.alias}</td>
				<td>{LANG.keywords}</td>
				<td>{LANG.description}</td>
				<td class="bl-col-number"><a href="{DATA_ORDER.numposts.data.url}" title="{DATA_ORDER.numposts.data.title}" class="{DATA_ORDER.numposts.data.class}">{LANG.categoriesnumPost}</a></td>
				<td class="bl-col-feature">{LANG.feature}</td>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
			<tr class="topalign">
				<td class="text-center">
					<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
				</td>
				<td class="text-center">{ROW.id}</td>
				<td>{ROW.title}</td>
				<td>{ROW.alias}</td>
				<td>{ROW.keywords}</td>
				<td>{ROW.description}</td>
				<td class="text-center"><strong>{ROW.numposts}</strong></td>
				<td class="text-center">
					<span class="edit-icon"><a href="{ROW.urlEdit}">{GLANG.edit}</a></span>
					<span class="delete-icon"><a href="javascript:void(0);" onclick="nv_delete_tags({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		<!-- END: row -->
		<tbody>
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="8">
					{GENERATE_PAGE}
				</td>
			</tr>
		<!-- END: generate_page -->
		<tbody>
		<tfoot>
			<tr>
				<td colspan="8">
					<!-- BEGIN: action -->
					<span class="{ACTION.class}-icon"><a onclick="nv_tags_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
					<!-- END: action -->
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- BEGIN: error --><div class="alert alert-danger">{ERROR}</div><!-- END: error -->
<form class="form-inline" method="post" action="">
<a name="edit"></a>
<table class="table table-striped table-bordered table-hover">
	<caption>{TABLE_CONTENT_CAPTION}</caption>
	<col class="bl-col-left-medium"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.title}</strong>
				 <span class="text-danger">(*)</span>
			</td>
			<td>
				<input type="text" required="required" class="form-control bl-txt-h" name="title" id="title" value="{DATA.title}" maxlength="255" onchange="if(document.getElementById('alias').value == '') get_alias('title','alias','tags');"/>
				<input type="button" class="btn btn-default" name="title" value="{LANG.aliasAutoGet}" onclick="get_alias('title','alias','tags');"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.alias}</strong>
				 <span class="text-danger">(*)</span>
			</td>
			<td><input type="text" required="required" class="form-control bl-txt-h" name="alias" id="alias" value="{DATA.alias}" maxlength="255"/></td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.keywords}</strong>
				 <span class="text-danger">(*)</span>
				<span class="note">({LANG.keywordsNote})</span>
			</td>
			<td><input type="text" required="required" class="form-control bl-txt-h" name="keywords" value="{DATA.keywords}" maxlength="255"/></td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.description}</strong>
				 <span class="text-danger">(*)</span>
				<span class="note">({LANG.descriptionNote})</span>
			</td>
			<td><input type="text" required="required" class="form-control bl-txt-h" name="description" value="{DATA.description}" maxlength="255"/></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center"><input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}"/></td>
		</tr>
	</tbody>
</table>
</form>
<!-- END: main -->