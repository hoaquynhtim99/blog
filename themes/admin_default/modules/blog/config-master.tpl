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
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfgcatViewType}</strong>
			</td>
			<td>
				<select name="catViewType" class="blog-input">
					<!-- BEGIN: catViewType --><option value="{CATVIEWTYPE.key}"{CATVIEWTYPE.selected}>{CATVIEWTYPE.title}</option><!-- END: catViewType -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgtagsViewType}</strong>
			</td>
			<td>
				<select name="tagsViewType" class="blog-input">
					<!-- BEGIN: tagsViewType --><option value="{TAGSVIEWTYPE.key}"{TAGSVIEWTYPE.selected}>{TAGSVIEWTYPE.title}</option><!-- END: tagsViewType -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfgnumPostPerPage}</strong>
			</td>
			<td>
				<input type="text" class="blog-input" name="numPostPerPage" value="{DATA.numPostPerPage}"/>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgstrCutHomeText}</strong>
			</td>
			<td>
				<input type="text" class="blog-input" name="strCutHomeText" value="{DATA.strCutHomeText}"/>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfgnumSearchResult}</strong>
			</td>
			<td>
				<input type="text" class="blog-input" name="numSearchResult" value="{DATA.numSearchResult}"/>
			</td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<caption>{LANG.cfgTheme}</caption>
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgsysHighlightTheme}</strong>
			</td>
			<td>
				<select name="sysHighlightTheme" class="blog-input">
					<!-- BEGIN: highlightTheme --><option value="{HIGHLIGHTTHEME.key}"{HIGHLIGHTTHEME.selected}>{HIGHLIGHTTHEME.title}</option><!-- END: highlightTheme -->
				</select>
			</td>
		</tr>
	</tbody>
	<!-- BEGIN: iconClass -->
	<tbody{ICONCLASS.class}>
		<tr>
			<td>
				<strong>{ICONCLASS.title}</strong>
			</td>
			<td>
				<input type="text" class="blog-input" name="iconClass{ICONCLASS.key}" value="{ICONCLASS.value}"/>
			</td>
		</tr>
	</tbody>
	<!-- END: iconClass -->
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
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgsysGoogleAuthor}</strong>
			</td>
			<td>
				<input type="text" class="blog-input bl-txt-h" name="sysGoogleAuthor" value="{DATA.sysGoogleAuthor}"/>
			</td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<caption>{LANG.cfgNewsletter}</caption>
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgnumberResendNewsletter}</strong>
			</td>
			<td>
				<select name="numberResendNewsletter" class="blog-input">
					<!-- BEGIN: numberResendNewsletter --><option value="{NUMBERRESENDNEWSLETTER.key}"{NUMBERRESENDNEWSLETTER.selected}>{NUMBERRESENDNEWSLETTER.title}</option><!-- END: numberResendNewsletter -->
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