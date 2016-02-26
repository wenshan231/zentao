<?php
/**
 * coship start
 * 导出excel页面
 * 2013-10-09 add file by fujia
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/colorbox.html.php';?>
<script>
function setDownloading()
{
    if($.browser.opera) return true;   // Opera don't support, omit it.

    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        parent.$.fn.colorbox.close();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
</script>
<form method='post' target='hiddenwin' onsubmit='setDownloading();' style='margin-top:10px'>
  <table class='table-1'>
    <caption><?php echo $lang->export;?></caption>
    <tr>
      <td class='a-center' style='padding:30px'>
        <?php
        echo $lang->setFileName . ' ';
        echo html::input('fileName', '', 'class=text-2');
        echo '.xls';
        echo html::submitButton();
        ?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
