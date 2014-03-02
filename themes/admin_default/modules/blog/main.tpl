<!-- BEGIN: main -->
<div class="bl-main-col clearfix">
	<div class="col-1">
		<div class="bl-post-panel decollapse">
			<div class="tl">{LANG.mainNotice}</div>
			<div class="ct">
				<!-- BEGIN: NoNotice --><div class="infook">{LANG.mainNoticeEmpty}</div><!-- END: NoNotice -->
				<!-- BEGIN: notice -->
				<ul class="bl-ul">
					<!-- BEGIN: loop --><li class="warning-icon"><a href="{NOTICE.link}" class="bl-warning">{NOTICE.title}</a></li><!-- END: loop -->
				</ul>
				<!-- END: notice -->
			</div>
		</div>
	</div>
</div>
<div class="bl-main-col clearfix">
	<div class="col-2">
		
	</div>
</div>
<div class="bl-main-col clearfix">
	<div class="col-3">
		
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