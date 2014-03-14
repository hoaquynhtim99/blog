<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
<table class="tab1">
	<caption>{LANG.cfgView}</caption>
	<col class="bl-col-left-largest"/>
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
	<caption>{LANG.cfgPost}</caption>
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfginitPostExp}</strong>
			</td>
			<td>
				<select name="initPostExp" class="blog-input">
					<!-- BEGIN: initPostExp --><option value="{INITPOSTEXP.key}"{INITPOSTEXP.selected}>{INITPOSTEXP.title}</option><!-- END: initPostExp -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfginitPostType}</strong>
			</td>
			<td>
				<select name="initPostType" class="blog-input">
					<!-- BEGIN: initPostType --><option value="{INITPOSTTYPE.key}"{INITPOSTTYPE.selected}>{INITPOSTTYPE.title}</option><!-- END: initPostType -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfginitMediaType}</strong>
			</td>
			<td>
				<select name="initMediaType" class="blog-input">
					<!-- BEGIN: initMediaType --><option value="{INITMEDIATYPE.key}"{INITMEDIATYPE.selected}>{INITMEDIATYPE.title}</option><!-- END: initMediaType -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfginitMediaHeight}</strong>
			</td>
			<td>
				<input type="text" class="blog-input bl-txt-h" name="initMediaHeight" value="{DATA.initMediaHeight}"/>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgfolderStructure}</strong>
			</td>
			<td>
				<select name="folderStructure" class="blog-input">
					<!-- BEGIN: folderStructure --><option value="{FOLDERSTRUCTURE.key}"{FOLDERSTRUCTURE.selected}>{FOLDERSTRUCTURE.title}</option><!-- END: folderStructure -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfginitNewsletters}</strong>
			</td>
			<td>
				<input type="checkbox" name="initNewsletters"{INITNEWSLETTERS} value="1"/>
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