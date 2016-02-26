<?php
/**
 * coship start
 * 编辑公告页面
 * 2013-10-15 add file by fujia
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
  <table class='table-1'> 
    <caption><?php echo $lang->notice->edit;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->notice->module;?></th>
      <td><?php echo html::select('module', $moduleOptionMenu, $notice->module, "class='select-3'");?></td>
    </tr>  
      <th class='rowhead'><?php echo $lang->notice->title;?></th>
      <td><?php echo html::input('title', $notice->title, "class='text-1'");?></td>
    </tr> 
    <tr id='contentBox'>
      <th class='rowhead'><?php echo $lang->notice->content;?></th>
      <td><?php echo html::textarea('content', htmlspecialchars($notice->content), "class='text-1' rows='8' style='width:90%; height:200px'");?></td>
    </tr>
    <tr id='fileBox'>
      <th class='rowhead' style="vertical-align:top;padding-top:15px;"><?php echo $lang->notice->files;?></th>
      <td style="padding-top:10px;">
      <?php if($notice->files):?>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $notice->files, 'fieldset' => 'false'));?>
      <br />
      <?php endif;?>
      <?php echo $this->fetch('file', 'buildform', 'fileCount=1');?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::backButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
