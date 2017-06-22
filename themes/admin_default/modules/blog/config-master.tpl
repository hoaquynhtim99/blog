<!-- BEGIN: main -->
<form class="form-inline" method="post" action="{FORM_ACTION}">
<table class="table table-striped table-bordered table-hover">
	<caption>{LANG.cfgView}</caption>
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgindexViewType}</strong>
			</td>
			<td>
				<select name="indexViewType" class="form-control">
					<!-- BEGIN: indexViewType --><option value="{INDEXVIEWTYPE.key}"{INDEXVIEWTYPE.selected}>{INDEXVIEWTYPE.title}</option><!-- END: indexViewType -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgcatViewType}</strong>
			</td>
			<td>
				<select name="catViewType" class="form-control">
					<!-- BEGIN: catViewType --><option value="{CATVIEWTYPE.key}"{CATVIEWTYPE.selected}>{CATVIEWTYPE.title}</option><!-- END: catViewType -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgtagsViewType}</strong>
			</td>
			<td>
				<select name="tagsViewType" class="form-control">
					<!-- BEGIN: tagsViewType --><option value="{TAGSVIEWTYPE.key}"{TAGSVIEWTYPE.selected}>{TAGSVIEWTYPE.title}</option><!-- END: tagsViewType -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgnumPostPerPage}</strong>
			</td>
			<td>
				<input type="text" class="form-control" name="numPostPerPage" value="{DATA.numPostPerPage}"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgstrCutHomeText}</strong>
			</td>
			<td>
				<input type="text" class="form-control" name="strCutHomeText" value="{DATA.strCutHomeText}"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgnumSearchResult}</strong>
			</td>
			<td>
				<input type="text" class="form-control" name="numSearchResult" value="{DATA.numSearchResult}"/>
			</td>
		</tr>
	</tbody>
</table>
<table class="table table-striped table-bordered table-hover">
	<caption>{LANG.cfgTheme}</caption>
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgsysHighlightTheme}</strong>
			</td>
			<td>
				<select name="sysHighlightTheme" class="form-control">
					<!-- BEGIN: highlightTheme --><option value="{HIGHLIGHTTHEME.key}"{HIGHLIGHTTHEME.selected}>{HIGHLIGHTTHEME.title}</option><!-- END: highlightTheme -->
				</select>
			</td>
		</tr>
	<!-- BEGIN: iconClass -->
		<tr>
			<td>
				<strong>{ICONCLASS.title}</strong>
			</td>
			<td>
				<input type="text" class="form-control" name="iconClass{ICONCLASS.key}" value="{ICONCLASS.value}"/>
			</td>
		</tr>
	<!-- END: iconClass -->
	<tbody>
</table>
<table class="table table-striped table-bordered table-hover">
	<caption>{LANG.cfgPost}</caption>
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfginitPostExp}</strong>
			</td>
			<td>
				<select name="initPostExp" class="form-control">
					<!-- BEGIN: initPostExp --><option value="{INITPOSTEXP.key}"{INITPOSTEXP.selected}>{INITPOSTEXP.title}</option><!-- END: initPostExp -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfginitPostType}</strong>
			</td>
			<td>
				<select name="initPostType" class="form-control">
					<!-- BEGIN: initPostType --><option value="{INITPOSTTYPE.key}"{INITPOSTTYPE.selected}>{INITPOSTTYPE.title}</option><!-- END: initPostType -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfginitMediaType}</strong>
			</td>
			<td>
				<select name="initMediaType" class="form-control">
					<!-- BEGIN: initMediaType --><option value="{INITMEDIATYPE.key}"{INITMEDIATYPE.selected}>{INITMEDIATYPE.title}</option><!-- END: initMediaType -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfginitMediaHeight}</strong>
			</td>
			<td>
				<input type="text" class="form-control bl-txt-h" name="initMediaHeight" value="{DATA.initMediaHeight}"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfginitMediaResponsive}</strong>
			</td>
			<td>
                <input type="checkbox" value="1" name="initMediaResponsive"{INITMEDIARESPONSIVE}/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfginitMediaWidth}</strong>
			</td>
			<td>
				<input type="text" class="form-control bl-txt-h" name="initMediaWidth" value="{DATA.initMediaWidth}"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgfolderStructure}</strong>
			</td>
			<td>
				<select name="folderStructure" class="form-control">
					<!-- BEGIN: folderStructure --><option value="{FOLDERSTRUCTURE.key}"{FOLDERSTRUCTURE.selected}>{FOLDERSTRUCTURE.title}</option><!-- END: folderStructure -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfginitNewsletters}</strong>
			</td>
			<td>
				<input type="checkbox" name="initNewsletters"{INITNEWSLETTERS} value="1"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfginitAutoKeywords}</strong>
			</td>
			<td>
				<input type="checkbox" name="initAutoKeywords"{INITAUTOKEYWORDS} value="1"/>
			</td>
		</tr>
	</tbody>
</table>
<table class="table table-striped table-bordered table-hover">
	<caption>{LANG.cfgNewsletter}</caption>
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgnumberResendNewsletter}</strong>
			</td>
			<td>
				<select name="numberResendNewsletter" class="form-control">
					<!-- BEGIN: numberResendNewsletter --><option value="{NUMBERRESENDNEWSLETTER.key}"{NUMBERRESENDNEWSLETTER.selected}>{NUMBERRESENDNEWSLETTER.title}</option><!-- END: numberResendNewsletter -->
				</select>
			</td>
		</tr>
	</tbody>
</table>
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td class="text-center"><input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}"/></td>
		</tr>
	</tbody>
</table>
</form>
<!-- END: main -->