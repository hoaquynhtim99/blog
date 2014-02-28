<!-- BEGIN: main -->
<!-- BEGIN: empty --><div class="infoerror">{LANG.categoriesEmpty}</div><!-- END: empty -->
<!-- BEGIN: data -->
<table class="tab1">
	<caption>{BREADCRUMBS}</caption>
	<thead>
		<tr>
			<td class="bl-col-id">ID</td>
			<td class="bl-col-id">{LANG.order}</td>
			<td>{LANG.title}</td>
			<td>{LANG.description}</td>
			<td class="bl-col-number">{LANG.categoriesnumPost}</td>
			<td class="bl-col-status">{LANG.status}</td>
			<td class="bl-col-feature">{LANG.feature}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td class="center">{ROW.id}</td>
			<td class="center">
				<select class="blog-input" name="weight" id="weight{ROW.id}" onchange="nv_change_cat_weight({ROW.id});">
					<!-- BEGIN: weight --><option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option><!-- END: weight -->
				</select>
			</td>
			<td>{ROW.title}</td>
			<td>{ROW.description}</td>
			<td class="center"><strong>{ROW.numPosts}</strong></td>
			<td class="center"><input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_cat_status({ROW.id})" /></td>
			<td class="center">
				<span class="edit-icon"><a href="{ROW.urlEdit}">{GLANG.edit}</a></span>
				<span class="delete-icon"><a href="javascript:void(0);" onclick="nv_delete_cat({ROW.id});">{GLANG.delete}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: data -->
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
				<input type="text" required="required" class="txt-half blog-input" name="title" id="title" value="{DATA.title}" maxlength="255" onchange="if(document.getElementById('alias').value == '') get_alias('title','alias');"/>
				<input type="button" class="blog-button-2" name="title" value="{LANG.aliasAutoGet}" onclick="get_alias('title','alias');"/>
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
			<td>
				<strong>{LANG.categoriesInCat}</strong>
				<span class="require">ӿ</span>
			</td>
			<td>
				<select class="blog-input" name="parentid">
					<!-- BEGIN: cat --><option value="{CAT.id}"{CAT.selected}>{CAT.name}</option><!-- END: cat -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2" class="center"><input class="blog-button" type="submit" name="submit" value="{LANG.save}"/></td>
		</tr>
	</tbody>
</table>
</form>
<!-- END: main -->