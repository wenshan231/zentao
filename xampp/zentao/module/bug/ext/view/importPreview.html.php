<?php
/**
 * coship start
 * 2013-11-27 add file by ÁõÖ¾Î°
 * ²âÊÔÓÃÀýµ¼ÈëexcelÔ¤ÀÀÒ³Ãæ
 */
?>

<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/chosen.html.php';?>
<script language="javascript">
function loadProjectRelated_import(projectID,bugID){
    oldOpenedBuild = $('#openedBuild'+bugID).val() ? $('#openedBuild'+bugID).val() : 0;
    if(projectID){
       link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + <?php echo $productID ?> + '&varName=openedBuild&build=' + oldOpenedBuild);
    }else{
       link = createLink('build', 'ajaxGetProductBuilds', 'productID=' +<?php echo $productID ?> + '&varName=openedBuild&build=' + oldOpenedBuild);
    }
    $('#buildBox'+bugID).load(link);
}
</script>
<div id="wrap">
  <div class="outer" style="min-height: 684px;">
<form method="post" target="hiddenwin">
<table class="table-1">
  <caption class="caption-tl"><?php echo $lang->bug->importPreview;?></caption>
  <tbody>
    <tr class="colhead">
      <th><?php echo $lang->bug->project;?></th>
      <th><?php echo $lang->bug->openedBuild;?></th>
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
    <?php foreach($bugs as $bugID => $bug):?>
    <tr valign="top" align="center"> 
      <td><?php echo html::select('project['.$bugID.']', $projects, $projectID, "style=width:100px onchange=loadProjectRelated_import(this.value,$bugID)");?></td>
      <td><span id='buildBox<?php echo $bugID; ?>'><?php echo html::select('openedBuild['.$bugID.']', $builds, $buildID, 'style=width:100px');?></td>
      <td><?php echo html::input("title[$bugID]", $bug['title'], 'style=width:100px');?></td>
      <td><?php echo html::select("severity[$bugID]",(array)$lang->bug->severityList,$bug['severity'], 'style=width:50px');?></td>
      <td><?php echo html::select("pri[$bugID]", (array)$lang->bug->priList, $bug['pri'], 'style=width:50px');?></td>
      <td><?php echo html::select("type[$bugID]",(array)$lang->bug->typeList, $bug['type'], 'style=width:90px');?></td>
      <td><?php echo html::textarea("steps[$bugID]", $bug['steps'], "rows='4' style=width:200px");?></td>
      <td><?php echo html::select("status[$bugID]",(array)$lang->bug->statusList,$bug['status'], 'style=width:63px');?></td>
      <td><?php echo html::select("openedBy[$bugID]", $users, $bug['openedBy'], 'style=width:73px');?></td>
      <td><?php echo html::input("openedDate[$bugID]", $bug['openedDate'], 'style=width:68px');?></td>
      <td><?php echo html::select("assignedTo[$bugID]", $users, $bug['assignedTo'], 'style=width:73px');?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<p><?php echo html::submitButton() . html::backButton();?></p>
</form>
</div>
<?php include '../../../common/view/footer.html.php';?>
