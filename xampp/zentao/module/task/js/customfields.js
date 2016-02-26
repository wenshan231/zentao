/**
 * coship start
 * 2013-11-28 add file by fujia
 */
function restoreDefault()
{
    $('#customFields option').remove();
    $('#defaultFields option').clone().appendTo('#customFields');
}
/* coship end */