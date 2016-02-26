/* Switch to module browse. */
function browseByModule(active)
{
    $('.side').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').addClass('hidden');
}

/* Swtich to search module. */
function browseBySearch(active)
{
    $('#querybox').removeClass('hidden');
    $('.side').addClass('hidden');
    $('.divider').addClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
    $('#bymoduleTab').removeClass('active');
}

function changeAction(url)
{
  $('#batchForm').attr('action', url);
}

$(document).ready(function()
{
    $(".runCase").colorbox({width:900, height:550, iframe:true, transition:'none', onCleanup:function(){parent.location.href=parent.location.href;}});
    $('#' + browseType + 'Tab').addClass('active');
    $('#module' + moduleID).addClass('active'); 
    if(browseType == 'bysearch') ajaxGetSearchForm();
});

$(document).ready(function() 
{
    $(".results").colorbox({width:900, height:550, iframe:true, transition:'none'});
    /**
     * coship start
     * 2013-09-09 add by fujia
     */
    $(".import").colorbox({width:700, height:300, iframe:true, transition:'none'});
    $(".caseImport").colorbox({width:700, height:300, iframe:true, transition:'none'});
    /* coship end */
})
