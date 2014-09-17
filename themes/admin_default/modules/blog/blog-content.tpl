<!-- BEGIN: main -->
<h3 class="bl-post-caption">{TABLE_CAPTION}</h3>
<!-- BEGIN: error --><div class="infoerror">{ERROR}</div><!-- END: error -->
<form method="post" action="{FORM_ACTION}" id="post-form">
	<input type="hidden" name="id" id="post-id" value="{ID}"/>
	<div class="bl-post-col-right clearfix">
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.blogTools}</div>
			<div class="ct">
				<p class="center">
					<input type="submit" name="submit" value="{LANG.blogPublic}" class="blog-button bl-button-min" id="button-public"/>
					<input type="submit" name="draft" value="{LANG.blogSaveDraft}" class="blog-button-2 bl-button-min" id="button-draft"/>
					<p id="post-message" class="center">&nbsp;</p>
				</p>
				<p>{LANG.blogPubtime1}:</p>
				<p>
					<select name="pubTime_h" class="blog-input">
						<!-- BEGIN: pubTime_h --><option value="{HOUR.key}"{HOUR.pub}>{HOUR.title}</option><!-- END: pubTime_h -->
					</select> : 
					<select name="pubTime_m" class="blog-input">
						<!-- BEGIN: pubTime_m --><option value="{MIN.key}"{MIN.pub}>{MIN.title}</option><!-- END: pubTime_m -->
					</select> {GLANG.day}
					<input type="text" class="blog-input bl-col-day" value="{DATA.pubTime}" name="pubTime" id="pubTime"/>
				</p>
				<p>{LANG.blogExptime1}:</p>
				<p>
					<select name="expTime_h" class="blog-input">
						<!-- BEGIN: expTime_h --><option value="{HOUR.key}"{HOUR.exp}>{HOUR.title}</option><!-- END: expTime_h -->
					</select> : 
					<select name="expTime_m" class="blog-input">
						<!-- BEGIN: expTime_m --><option value="{MIN.key}"{MIN.exp}>{MIN.title}</option><!-- END: expTime_m -->
					</select> {GLANG.day}
					<input type="text" class="blog-input bl-col-day" value="{DATA.expTime}" name="expTime" id="expTime"/>
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
					<!-- BEGIN: tag --><li rel="{TAG.id}">{TAG.title}<span>&nbsp;</span></li><!-- END: tag -->
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
		<div class="bl-post-panel collapse">
			<div class="tl">{LANG.blogOtherOption}</div>
			<div class="ct bl-post-fix-height large">
				<ul class="bl-ul">
					<li><label><input type="checkbox" name="newsletters" value="1"{NEWSLETTERS}/> {LANG.blogSendNewsletter}</label></li>
					<li><label><input type="checkbox" name="fullPage" value="1"{FULLPAGE}/> {LANG.blogFullPage}</label></li>
					<li>
						<label>
							{LANG.blogGoogleAuthor}: 
							<input type="text" class="blog-input bl-col-day" name="postGoogleID" value="{DATA.postGoogleID}"/>
						</label>
					</li>
					<li><label><input type="checkbox" name="isAutoKeywords" value="1"{ISAUTOKEYWORDS}/> {LANG.blogIsAutoKeywords}</label></li>
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
		<div class="bl-editor">
			{DATA.bodyhtml}
		</div>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="images">{LANG.blogImages}:</label></dt>
			<dd>
				<input type="text" class="blog-input bl-txt-q" name="images" id="images" value="{DATA.images}"/>
				<input type="button" class="blog-button-2" name="imagesBrowser" id="imagesBrowser" value="{LANG.browser}" onclick="nv_open_browse_file(script_name + '?' + nv_name_variable + '=upload&popup=1&area=images&path={UPLOADS_PATH}&type=image&currentpath={CURRENT_PATH}', 'NVImg', '850', '420', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');"/>
				<input type="button" class="blog-button-2" name="imagesView" id="imagesView" value="{LANG.view}" onclick="viewImages();"/>
			</dd>
		</dl>
		<dl class="bl-post-dl">
			<dt class="aright"><label for="mediaType">{LANG.blogmediaType}:</label></dt>
			<dd>
				<select name="mediaType" id="mediaType" class="blog-input">
					<!-- BEGIN: mediaType --><option value="{MEDIATYPE.key}"{MEDIATYPE.selected}>{MEDIATYPE.title}</option><!-- END: mediaType -->
				</select>
			</dd>
		</dl>
		<dl class="bl-post-dl" id="mediaHeight-wrap">
			<dt class="aright"><label for="mediaHeight">{LANG.blogMediaHeight}:</label></dt>
			<dd><input type="text" class="blog-input bl-txt-q" name="mediaHeight" id="mediaHeight" value="{DATA.mediaHeight}"/></dd>
		</dl>
		<dl class="bl-post-dl" id="mediaValue-wrap">
			<dt class="aright"><label for="mediaValue">{LANG.blogMediaValue}:</label></dt>
			<dd>
				<input type="text" class="blog-input bl-txt-q" name="mediaValue" id="mediaValue" value="{DATA.mediaValue}"/>
				<input type="button" class="blog-button-2" name="mediaBrowser" id="mediaBrowser" value="{LANG.browser}" onclick="nv_open_browse_file(script_name + '?' + nv_name_variable + '=upload&popup=1&area=mediaValue&path={UPLOADS_PATH}&type=file&currentpath={CURRENT_PATH}', 'NVImg', '850', '420', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');"/>
			</dd>
		</dl>
	</div>
</form>
<script type="text/javascript">
function mediaHandle(){
	var mediaType = $('#mediaType').val();
	if( mediaType != '2' && mediaType != '3' &&  mediaType != 4 ){
		$('#mediaHeight').attr('disabled','disabled');
		$('#mediaHeight-wrap').hide();
	}
	else
	{
		$('#mediaHeight').removeAttr('disabled');
		$('#mediaHeight-wrap').show();
	}
	if( mediaType == '0' ){
		$('#mediaValue').attr('disabled','disabled');
		$('#mediaValue-wrap').hide();
	}
	else
	{
		$('#mediaValue').removeAttr('disabled');
		$('#mediaValue-wrap').show();
	}
	if( mediaType == '0' || mediaType == '4' ){
		$('#mediaBrowser').hide();
	}else{
		$('#mediaBrowser').show();
	}
}
function viewImages(){
	var images = $('#images').val();
	if( images == '' ){
		return;
	}
	Shadowbox.open({
		content : images,
		player : 'img',
		hanleOversize: 'resize'
	});
}
$(document).ready(function(){
	BL.tags.init();
	BL.post.init({EDITOR});
	mediaHandle();
	// Xu ly panel
	$('.bl-post-panel .tl').click(function(){
		var $this = $(this).parent();
		
		if( $this.hasClass('collapse') ){
			$this.find('.ct').slideDown(200, function(){$this.removeClass('collapse').addClass('decollapse')});
		}else{
			$this.find('.ct').slideUp(200, function(){$this.removeClass('decollapse').addClass('collapse')});
		}
	});
	
	// Chon ngay thang
	$("#pubTime, #expTime").datepicker({
		dateFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		showButtonPanel: true,
		showOn: 'focus'
	});
	
	// Xu ly media
	$('#mediaType').change(function(){
		mediaHandle();
	});
});
</script>
<!-- END: main -->

<!-- BEGIN: complete -->
<div class="infook center">
	<p>{MESSAGE}</p>
	<p><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..." height="8"/></p>
</div>
<!-- END: complete -->