<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
<table class="tab1">
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgblockTagsShowType}</strong>
			</td>
			<td>
				<select name="blockTagsShowType" class="blog-input">
					<!-- BEGIN: blockTagsShowType --><option value="{BLOCKTAGSSHOWTYPE.key}"{BLOCKTAGSSHOWTYPE.selected}>{BLOCKTAGSSHOWTYPE.title}</option><!-- END: blockTagsShowType -->
				</select>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfgblockTagsNums}</strong>
			</td>
			<td>
				<input type="text" class="blog-input" name="blockTagsNums" value="{DATA.blockTagsNums}"/>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgblockTagsCacheIfRandom}</strong>
			</td>
			<td>
				<input type="checkbox" class="blog-input" name="blockTagsCacheIfRandom" value="1"{BLOCKTAGSCACHEIFRANDOM}/>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfgblockTagsCacheLive}</strong>
			</td>
			<td>
				<input type="text" class="blog-input" name="blockTagsCacheLive" value="{DATA.blockTagsCacheLive}"/> ({GLANG.min})
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