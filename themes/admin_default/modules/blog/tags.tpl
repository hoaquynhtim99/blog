<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
				<form id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="blog-input text" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.searchTags}"/>
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
				<td class="center bl-col-id">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td class="bl-col-id">ID</td>
				<td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.title}</a></td>
				<td>{LANG.alias}</td>
				<td>{LANG.keywords}</td>
				<td>{LANG.description}</td>
				<td class="bl-col-number"><a href="{DATA_ORDER.numPosts.data.url}" title="{DATA_ORDER.numPosts.data.title}" class="{DATA_ORDER.numPosts.data.class}">{LANG.categoriesnumPost}</a></td>
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
				<td>{ROW.alias}</td>
				<td>{ROW.keywords}</td>
				<td>{ROW.description}</td>
				<td class="center"><strong>{ROW.numPosts}</strong></td>
				<td class="center">
					<span class="edit-icon"><a href="{ROW.urlEdit}">{GLANG.edit}</a></span>
					<span class="delete-icon"><a href="javascript:void(0);" onclick="nv_delete_tags({ROW.id});">{GLANG.delete}</a></span>
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
					<span class="{ACTION.class}-icon"><a onclick="nv_tags_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
					<!-- END: action -->
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- BEGIN: error --><div class="infoerror">{ERROR}</div><!-- END: error -->
<form method="post" action="">
<a name="edit"></a>
<table class="tab1">
	<caption>{TABLE_CONTENT_CAPTION}</caption>
	<col class="bl-col-left-medium"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.title}</strong>
				<span class="require">ӿ</span>
			</td>
			<td>
				<input type="text" required="required" class="txt-half blog-input" name="title" id="title" value="{DATA.title}" maxlength="255" onchange="if(document.getElementById('alias').value == '') get_alias('title','alias','tags');"/>
				<input type="button" class="blog-button-2" name="title" value="{LANG.aliasAutoGet}" onclick="get_alias('title','alias','tags');"/>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.alias}</strong>
				<span class="require">ӿ</span>
			</td>
			<td><input type="text" required="required" class="txt-half blog-input" name="alias" id="alias" value="{DATA.alias}" maxlength="255"/></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.keywords}</strong>
				<span class="require">ӿ</span>
				<span class="note">({LANG.keywordsNote})</span>
			</td>
			<td><input type="text" required="required" class="txt-half blog-input" name="keywords" value="{DATA.keywords}" maxlength="255"/></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.description}</strong>
				<span class="require">ӿ</span>
				<span class="note">({LANG.descriptionNote})</span>
			</td>
			<td><input type="text" required="required" class="txt-half blog-input" name="description" value="{DATA.description}" maxlength="255"/></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td colspan="2" class="center"><input class="blog-button" type="submit" name="submit" value="{LANG.save}"/></td>
		</tr>
	</tbody>
</table>
</form>
<!-- END: main -->