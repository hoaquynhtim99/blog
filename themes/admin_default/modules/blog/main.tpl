<!-- BEGIN: main -->
<div class="bl-main-col clearfix">
	<div class="col-1">
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.mainNotice}</div>
			<div class="ct">
				<!-- BEGIN: NoNotice --><div class="alert alert-success bl-inline-box">{LANG.mainNoticeEmpty}</div><!-- END: NoNotice -->
				<!-- BEGIN: notice -->
				<ul class="bl-ul">
					<!-- BEGIN: loop --><li class="warning-icon"><a href="{NOTICE.link}" class="bl-warning">{NOTICE.title}</a></li><!-- END: loop -->
				</ul>
				<!-- END: notice -->
			</div>
		</div>
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.mainQuickLink}</div>
			<div class="ct">
				<ul class="bl-ul">
					<li><a href="{BASE_ADMIN_LINK}config-block-tags">{LANG.cfgBlockTags}</a></li>
					<li><a href="{BASE_ADMIN_LINK}config-sys">{LANG.cfgSys}</a></li>
					<li><a href="{BASE_ADMIN_LINK}config-structured-data">{LANG.cfgStructureData}</a></li>
					<li><a href="{BASE_ADMIN_LINK}config-comment">{LANG.cfgComment}</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="bl-main-col clearfix">
	<div class="col-2">
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.mainStat}</div>
			<div class="ct">
				<ul class="bl-ul">
					<!-- BEGIN: statistics -->
					<li class="note-icon"><a href="{STATISTICS.link}">{STATISTICS.title}</a></li>
					<!-- END: statistics -->
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="bl-main-col clearfix">
	<div class="col-3">
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.mainInfo}</div>
			<div class="ct">
				<ul class="bl-ul">
					<li class="ul-icon">{LANG.mainInfoVersion}: <strong>{MODULE_INFO.version}</strong></li>
					<li class="ul-icon">{LANG.mainInfoRelease}: <strong>{MODULE_INFO.date}</strong></li>
					<li class="ul-icon">{LANG.mainInfoAuthor}: <strong>{MODULE_INFO.author}</strong></li>
					<li class="ul-icon">{LANG.mainInfoContact}: <strong>{AUTHOR_CONTACT}</strong></li>
					<li class="ul-icon">{LANG.mainInfoSupport}: <strong><a href="https://github.com/hoaquynhtim99/blog/" target="_blank">https://github.com/hoaquynhtim99/blog/</a></strong></li>
					<li class="ul-icon">{LANG.mainInfoIssue}: <strong><a href="https://github.com/hoaquynhtim99/blog/issues" target="_blank">https://github.com/hoaquynhtim99/blog/issues</a></strong></li>
				</ul>
			</div>
		</div>
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.mainDonation}</div>
			<div class="ct center">
				<div class="bl-donate">
					<a target="_blank" href="https://www.nganluong.vn/button_payment.php?receiver={DONATE_EMAIL}&product_name={DONATE_ORDERID}&price={DONATE_AMOUNT}&return_url={DONATE_RETURN}&comments={LANG.donateComment}" ><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/nganluong.gif"  border="0" /></a>
					<form class="form-inline" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick"/>
						<input type="hidden" name="hosted_button_id" value="5C3MM5P45Z72L"/>
						<input type="image" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/paypal.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
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