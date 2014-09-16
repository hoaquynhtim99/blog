<!-- BEGIN: main -->
<div class="infoalert">{LANG.cfgCommentNote}</div>
<form method="post" action="{FORM_ACTION}">
<table class="tab1">
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgCommentPerPage}</strong>
				<span class="require">ӿ</span>
			</td>
			<td>
				<input type="text" class="blog-input" name="commentPerPage" value="{DATA.commentPerPage}"/>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfgCommentType}</strong>
				<span class="require">ӿ</span>
			</td>
			<td>
				<select name="commentType" class="blog-input">
					<!-- BEGIN: commentType --><option value="{COMMENTTYPE.key}"{COMMENTTYPE.selected}>{COMMENTTYPE.title}</option><!-- END: commentType -->
				</select>
			</td>
		</tr>
	</tbody>
</table>
<table class="tab1 comment-table" id="comment-facebook">
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgcommentFacebookColorscheme}</strong>
				<span class="require">ӿ</span>
			</td>
			<td>
				<select name="commentFacebookColorscheme" class="blog-input">
					<!-- BEGIN: commentFacebookColorscheme --><option value="{COMMENTFACEBOOKCOLORSCHEME.key}"{COMMENTFACEBOOKCOLORSCHEME.selected}>{COMMENTFACEBOOKCOLORSCHEME.title}</option><!-- END: commentFacebookColorscheme -->
				</select>
			</td>
		</tr>
	</tbody>
</table>
<table class="tab1 comment-table" id="comment-disqus">
	<col class="bl-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgcommentDisqusShortname}</strong>
				<span class="require">ӿ</span>
			</td>
			<td><input type="text" class="blog-input bl-txt-h" name="commentDisqusShortname" value="{DATA.commentDisqusShortname}"/></td>
		</tr>
	</tbody>
</table>
<table class="tab1 comment-table" id="comment-sys">
	<tbody>
		<tr>
			<td>
				<div class="infoerror">{LANG.isDevelop}</div>
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
<script type="text/javascript">
function nv_control_comment_table(){
	var commentType = $('[name="commentType"]').val();
	var tableId = 'comment-' + commentType;
	
	$('.comment-table').hide();
	if( document.getElementById(tableId) ){
		$('#' + tableId).show();
	}
}
$(function(){
	$('[name="commentType"]').change(function(){
		nv_control_comment_table();
	});
	nv_control_comment_table();
});
</script>
<!-- END: main -->