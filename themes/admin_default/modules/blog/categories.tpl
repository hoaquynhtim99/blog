<!-- BEGIN: main -->
<!-- BEGIN: empty --><div class="alert alert-danger">{LANG.categoriesEmpty}</div><!-- END: empty -->
<!-- BEGIN: data -->
<table class="table table-striped table-bordered table-hover">
	<caption>{BREADCRUMBS}</caption>
	<thead>
		<tr>
			<td class="bl-col-id">ID</td>
			<td class="bl-col-order">{LANG.order}</td>
			<td>{LANG.title}</td>
			<td>{LANG.description}</td>
			<td class="bl-col-number">{LANG.categoriesnumPost}</td>
			<td class="bl-col-status">{LANG.status}</td>
			<td class="bl-col-feature">{LANG.feature}</td>
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN: loop -->
		<tr>
			<td class="text-center">{ROW.id}</td>
			<td class="text-center">
				<select class="form-control" name="weight" id="weight{ROW.id}" onchange="nv_change_cat_weight({ROW.id});">
					<!-- BEGIN: weight --><option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option><!-- END: weight -->
				</select>
			</td>
			<td>{ROW.title}</td>
			<td>{ROW.description}</td>
			<td class="text-center"><strong>{ROW.numposts}</strong></td>
			<td class="text-center"><input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_cat_status({ROW.id})" /></td>
			<td class="text-center">
				<span class="edit-icon"><a href="{ROW.urlEdit}">{GLANG.edit}</a></span>
				<span class="delete-icon"><a href="javascript:void(0);" onclick="nv_delete_cat({ROW.id});">{GLANG.delete}</a></span>
			</td>
		</tr>
	<!-- END: loop -->
	<tbody>
</table>
<!-- END: data -->
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
				<input type="text" required="required" class="form-control bl-txt-h" name="title" id="title" value="{DATA.title}" maxlength="255" onchange="if(document.getElementById('alias').value == '') get_alias('title','alias','cat');"/>
				<input type="button" class="btn btn-default" name="title" value="{LANG.aliasAutoGet}" onclick="get_alias('title','alias','cat');"/>
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
			<td>
				<strong>{LANG.categoriesInCat}</strong>
				 <span class="text-danger">(*)</span>
			</td>
			<td>
				<select class="form-control" name="parentid">
					<!-- BEGIN: cat --><option value="{CAT.id}"{CAT.selected}>{CAT.name}</option><!-- END: cat -->
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="text-center"><input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}"/></td>
		</tr>
	</tbody>
</table>
</form>
<!-- END: main -->