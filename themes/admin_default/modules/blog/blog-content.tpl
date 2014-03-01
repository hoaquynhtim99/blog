<!-- BEGIN: main -->
<h3 class="bl-post-caption">{TABLE_CAPTION}</h3>
<form method="post" action="{FORM_ACTION}">
	<div class="bl-post-col-right clearfix">
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogTools}</div>
			<div class="ct">
				<p class="center"><input type="submit" name="submit" value="{LANG.blogPublic}" class="blog-button"/></p>
				<p>{LANG.blogPubtime1}:</p>
				<p>
					<select name="pubTime_h" class="blog-input">
						<!-- BEGIN: pubTime_h --><option value="{HOUR.key}"{HOUR.pub}>{HOUR.title}</option><!-- END: pubTime_h -->
					</select> : 
					<select name="pubTime_m" class="blog-input">
						<!-- BEGIN: pubTime_m --><option value="{MIN.key}"{MIN.pub}>{MIN.title}</option><!-- END: pubTime_m -->
					</select> {GLANG.day}
					<input type="text" class="blog-input bl-col-day" value="{DATA.pubTime}" name="pubTime"/>
				</p>
				<p>{LANG.blogExptime1}:</p>
				<p>
					<select name="expTime_h" class="blog-input">
						<!-- BEGIN: expTime_h --><option value="{HOUR.key}"{HOUR.exp}>{HOUR.title}</option><!-- END: expTime_h -->
					</select> : 
					<select name="expTime_m" class="blog-input">
						<!-- BEGIN: expTime_m --><option value="{MIN.key}"{MIN.exp}>{MIN.title}</option><!-- END: expTime_m -->
					</select> {GLANG.day}
					<input type="text" class="blog-input bl-col-day" value="{DATA.expTime}" name="expTime"/>
				</p>
				<p>{LANG.blogExpMode1}:</p>
				<p>
					<select name="expMode" class="blog-input bl-txt-f">
						<!-- BEGIN: expMode --><option value="{EXPMODE.key}"{EXPMODE.selected}>{EXPMODE.title}</option><!-- END: expMode -->
					</select>
				</p>
			</div>
		</div>
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogInCats}</div>
			<div class="ct bl-post-fix-height large">
				<ul class="bl-list-item">
					<!-- BEGIN: cat --><li><label>{CAT.defis}<input type="checkbox" name="catids[]" value="{CAT.id}"{CAT.checked}/> {CAT.title}</label></li><!-- END: cat -->
				</ul>
			</div>
		</div>
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogTags}</div>
			<div class="ct">
				<input type="hidden" name="tagids" value="{TAGIDS}" id="post-tags"/>
				<ul class="bl-list-tags" id="post-tags-list"> 
				
				</ul>
				<!-- BEGIN: mostTags -->
				<div class="bl-hr">&nbsp;</div>
				<p>{LANG.tagsMost}:</p>
				<ul class="bl-list-tags cursor" id="post-tags-most">
					<!-- BEGIN: loop --><li rel="{MOSTTAGS.id}">{MOSTTAGS.title}</li><!-- END: loop -->
				</ul>
				<!-- END: mostTags -->
				<div class="bl-hr">&nbsp;</div>
				<p>{LANG.tagsSearch}:</p>
				<input type="text" class="blog-input bl-col-day" id="post-tags-type"/>
				<input type="button" class="blog-button-2" value="{LANG.add}" id="post-tags-button"/>
			</div>
		</div>
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogpostType}</div>
			<div class="ct bl-post-fix-height large">
				<ul class="bl-list-item">
					<!-- BEGIN: postType -->
					<li><label><input type="radio" name="postType" value="{POSTTYPE.key}"{POSTTYPE.checked}/> {POSTTYPE.title}</label></li>
					<!-- END: postType -->
				</ul>
			</div>
		</div>
	</div>
	<div class="bl-post-col-left">
		<dl class="bl-post-dl">
			<dt class="aright"><label for="title">{LANG.blogTitle}:</label></dt>
			<dd>
				<input type="text" class="blog-input bl-txt-fh" id="title" name="title" value="{DATA.title}" onchange="if(document.getElementById('alias').value == '') get_alias('title','alias','post');"/>
				<input type="button" class="blog-button-2" name="title" value="{LANG.aliasAutoGet}" onclick="get_alias('title','alias','post');"/>
			</dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="alias">{LANG.alias}:</label></dt>
			<dd><input type="text" class="blog-input bl-txt-f" id="alias" name="alias" value="{DATA.alias}"/></dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="siteTitle">{LANG.blogSiteTitle}:</label></dt>
			<dd><input type="text" class="blog-input bl-txt-f" id="siteTitle" name="siteTitle" value="{DATA.siteTitle}"/></dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="keywords">{LANG.keywords}:</label></dt>
			<dd><input type="text" class="blog-input bl-txt-f" id="keywords" name="keywords" value="{DATA.keywords}"/></dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="hometext">{LANG.blogHometext}:</label></dt>
			<dd><textarea type="text" class="blog-input bl-txt-f autoresize textarea-animated" id="hometext" name="hometext" rows="1">{DATA.hometext}</textarea></dd>
		</dl>
		<p><strong>{LANG.blogBodyhtml}:</strong></p>
		{DATA.bodyhtml}
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	BL.tags.init();
	$('.bl-post-panel .tl').click(function(){
		var $this = $(this).parent();
		
		if( $this.hasClass('collapse') ){
			$this.find('.ct').slideDown(200, function(){$this.removeClass('collapse').addClass('decollapse')});
		}else{
			$this.find('.ct').slideUp(200, function(){$this.removeClass('decollapse').addClass('collapse')});
		}
	});
});
</script>
<!-- END: main -->
