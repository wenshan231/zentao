<?php
/**
 * coship start
 * 2013-08-16 add file by fujia
 */
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
js::import($jsRoot . 'jquery/tablesorter/min.js');
js::import($jsRoot . 'jquery/tablesorter/metadata.js');
?>
<script language='javascript'>
$(function() {

    $('.tablesorter').tablesorter(
        {
            headers:{
				0:{sorter:false},
				1:{sorter:false},
				2:{sorter:false},
				4:{sorter:false},
				5:{sorter:false},
				6:{sorter:false}
				},
            widgets: ['zebra'], 
            widgetZebra: {css: ['odd', 'even'] }
        }
    ); 
    $('.tablesorter tbody tr').hover(
        function(){$(this).addClass('hoover')},
        function(){$(this).removeClass('hoover')}
    );

    /* IE6下面click事件和colorbox冲突。暂时去除该功能。*/
    if($.browser.msie && Math.floor(parseInt($.browser.version)) == 6) return; 
    $('.tablesorter tbody tr').click(
        function()
        {
            if($(this).attr('class').indexOf('clicked') > 0)
            {
                $(this).removeClass('clicked');
            }
            else
            {
                $(this).addClass('clicked');
            }
        }
    );
});
</script>