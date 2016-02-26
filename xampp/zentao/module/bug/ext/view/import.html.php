<?php
/**
 * coship start
 * 2013-09-04 add file by fujia
 * 导入excel选择文件页面
 */
?>
<?php include '../../../common/view/header.lite.html.php';?>
<?php js::set('page', 'import');?>
<style type="text/css">
table{border-collapse:collapse;}
th,td{border:1px solid #CCC;}
</style>
<script language="javascript">
function loadProjectRelated_import(projectID){
    oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;
    if(projectID){
        link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + <?php echo $productID ?> + '&varName=openedBuild&build=' + oldOpenedBuild);
    }else{
       link = createLink('build', 'ajaxGetProductBuilds', 'productID=' +<?php echo $productID ?> + '&varName=openedBuild&build=' + oldOpenedBuild);    
    }
    $('#buildBox').load(link);
}
function checkNull(){
     if($('#project').val() ==""){
          alert("[所属项目]不能为空");return false;
     }else if($('#openedBuild').val() ==""){
          alert("[影响版本]不能为空");return false;
     }else if($('#file').val() ==""){
          alert("[文件选择框]不能为空");return false;
     }
}
</script>
<form method='post' enctype='multipart/form-data' class='a-center'>
<div style="margin-left:30px;text-align:left">
  <div style="color:red;">
    <span><strong><?php echo $lang->bug->importTemplate;?></strong></span>
    <span><?php echo html::linkButton($lang->bug->downloadTpl, inlink('downloadTpl'));?></span>
  </div>
  <table class="table-5" style="margin-top:15px;">
  <tbody>
    <tr class="colhead">   
      <th><?php echo $lang->bug->title;?></th>
      <th><?php echo $lang->bug->severity;?></th>
      <th><?php echo $lang->bug->pri;?></th>
      <th><?php echo $lang->bug->type;?></th>
      <th><?php echo $lang->bug->steps;?></th>
      <th><?php echo $lang->bug->status;?></th>
      <th><?php echo $lang->bug->openedBy;?></th>
      <th><?php echo $lang->bug->openedDate;?></th>
      <th><?php echo $lang->bug->assignedTo;?></th>
    </tr>
    <tr valign="top" align="center">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="top" align="center">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
  </table>
  <div style="margin-top:15px;">
    <span><strong><?php echo $lang->bug->project;?></strong></span>
    <span id='projectIdBox' style="margin-left:15px;"><?php echo html::select('project', $projects, $projectID, 'class=select-3 onchange=loadProjectRelated_import(this.value)');?></span>
  </div>
  <div style="margin-top:10px;">
    <span><strong><?php echo $lang->bug->openedBuild;?></strong></span>
    <span id='buildBox' style="margin-left:15px;"><?php echo html::select('openedBuild', $builds, $buildID, 'class=select-3' );?></span>
  </div>
  <div style="margin-top:15px;">
    <input type='file' name='file' id="file" class='text-5' />
    <?php echo html::select('fileType', $lang->importEncodeList,'gbk');?>
    <?php echo html::submitButton('确定',"onclick='javascript:return checkNull();'");?>
  </div>
</div>
</form>
