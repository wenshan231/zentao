<?php
/**
 * coship start
 * 任务自定义字段选择页面
 * 2013-11-28 add file by fujia
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/colorbox.html.php';?>
<form method='post' class='mt-20px'>
  <table class='table-4' align='center'> 
    <caption class='caption-tl'><?php echo $lang->task->customFields;?></caption>
    <tr class='colhead'>
      <th><?php echo $lang->task->lblAllFields;?></th>
      <th></th>
      <th><?php echo $lang->task->lblCustomFields;?></th>
      <th></th>
    </tr>  
    <tr>
      <td>
        <?php 
        echo html::select('allFields[]', $allFields, '', 'class=select-2 size=10 multiple');
        echo html::select('defaultFields[]', $defaultFields, '', 'class=hidden');
        ?>
      </td>
      <td>
        <?php
        echo html::commonButton('>', "onclick=\"addItem('allFields', 'customFields')\"") . '<br />';
        echo html::commonButton('<', "onclick=delItem('customFields')")  . '<br />';
        ?>
      </td>
      <td><?php echo html::select('customFields[]', $customFields, '', 'class=select-2 size=10 multiple');?></td>
      <td>
        <?php
        echo html::commonButton('+', "onclick=upItem('customFields')")  . '<br />';
        echo html::commonButton('-', "onclick=downItem('customFields')")  . '<br />';
        echo html::commonButton($lang->task->restoreDefault, "onclick=restoreDefault()")  . '<br />';
        ?>
      </td>
    </tr>  
    <tr><td colspan='4' class='a-center'><?php echo html::submitButton('', 'onclick=selectItem("customFields")');?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
