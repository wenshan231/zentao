<?php 
	$this->app->loadLang('my',$exit = false);
?>
<script language='Javascript'>
	function scrollToFoot(){
		$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
		$body.animate({scrollTop: $('p.a-center').offset().top}, 1000);
	}
	function thanksForUsing(){
		if (confirm(<?php echo '"'.$this->lang->my->todocalendarpriv.'"';?>)){
			parent.location.href = <?php echo '"'.helper::createLink('group','browse').'"';?>;
		}
	}
	window.onload = function(){ scrollToFoot();setTimeout(thanksForUsing,1100);};
</script>
