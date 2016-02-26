$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();

    /**
     * coship start
     * 2013-11-28 add by fujia
     */
    $("a.customFields").colorbox({width:540, height:340, iframe:true, transition:'none'});
    $('#' + browseType + 'Tab').addClass('active'); 
    $('#module' + moduleID).addClass('active'); 

    /* If customed and the browse is ie6, remove the ie6.css. */
    if(customed && $.browser.msie && Math.floor(parseInt($.browser.version)) == 6)
    {
        $("#browsecss").attr('href', '');
    }
    /* coship end */
});

function changeAction(formName, actionName, actionLink)
{
    if(actionName == 'batchClose') $('#' + formName).attr('target', 'hiddenwin');
    $('#' + formName).attr('action', actionLink).submit();
}

function showProject()
{
    $('#sidebar').hide();
    $('#project').show();
    $('#project-divider').show();
    $.cookie('projectBar', 'show');
}

function hideProject()
{
    $('#sidebar').show();
    $('#project').hide();
    $('#project-divider').hide();
    $.cookie('projectBar', 'hide');
}
