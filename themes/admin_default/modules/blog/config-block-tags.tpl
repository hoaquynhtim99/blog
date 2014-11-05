<!-- BEGIN: main -->
<form class="form-inline" method="post" action="{FORM_ACTION}">
<table class="table table-striped table-bordered table-hover">
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgblockTagsShowType}</strong>
			</td>
			<td>
				<select name="blockTagsShowType" class="form-control">
					<!-- BEGIN: blockTagsShowType --><option value="{BLOCKTAGSSHOWTYPE.key}"{BLOCKTAGSSHOWTYPE.selected}>{BLOCKTAGSSHOWTYPE.title}</option><!-- END: blockTagsShowType -->
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgblockTagsNums}</strong>
			</td>
			<td>
				<input type="text" class="form-control" name="blockTagsNums" value="{DATA.blockTagsNums}"/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgblockTagsCacheIfRandom}</strong>
			</td>
			<td>
				<input type="checkbox" class="" name="blockTagsCacheIfRandom" value="1"{BLOCKTAGSCACHEIFRANDOM}/>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{LANG.cfgblockTagsCacheLive}</strong>
			</td>
			<td>
				<input type="text" class="form-control" name="blockTagsCacheLive" value="{DATA.blockTagsCacheLive}"/> ({GLANG.min})
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