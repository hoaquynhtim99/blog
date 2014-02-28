<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
<table class="tab1">
	<caption>{LANG.cfgView}</caption>
	<col class="bl-col-left-medium"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgindexViewType}</strong>
			</td>
			<td>
				<select name="indexViewType" class="blog-input">
					<!-- BEGIN: indexViewType --><option value="{INDEXVIEWTYPE.key}"{INDEXVIEWTYPE.selected}>{INDEXVIEWTYPE.title}</option><!-- END: indexViewType -->
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