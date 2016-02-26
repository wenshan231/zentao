<?php
/**
 * coship start
 * 2013-09-04 add file by fujia
 * 导入excel选择文件页面
 */
?>
<?php include '../../../common/view/header.lite.html.php';?>
<style type="text/css">
table{border-collapse:collapse;}
th,td{border:1px solid #CCC;}
</style>
<script language="javascript">
function changeTemplate(title){
    var objTitle = document.getElementById(title);
    var objCol = document.getElementsByName(title+'Col');
    for(var i = 0 ;i<objCol.length;i++){
        if(objTitle.checked == true){
            objCol[i].style.display = '';
        }else{
            objCol[i].style.display = 'none';
        }
    }
}
</script>
<form method='post' enctype='multipart/form-data' class='a-center'>
<div style="margin-top:10px;margin-left:30px;text-align:left">
  <table class="table-5">
  <caption class="caption-tl" style="color:red;"><?php echo $lang->testcase->importTemplate;?></caption>
  <tbody>
    <tr class="colhead">
      <th name="caseCodeCol" style="display:none;color:blue;"><?php echo $lang->testcase->caseCode;?></th>
      <th name="testItemCol" style="display:none;color:blue;"><?php echo $lang->testcase->testItem;?></th>
      <th><?php echo $lang->testcase->title;?></th>
      <th><?php echo $lang->testcase->pri;?></th>
      <th><?php echo $lang->testcase->precondition;?></th>
      <th><?php echo $lang->testcase->stepDesc;?></th>
      <th><?php echo $lang->testcase->stepExpect;?></th>
    </tr>
    <tr valign="top" align="center">
      <td name="caseCodeCol" style="display:none;"></td>
      <td name="testItemCol" style="display:none;"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td name="caseCodeCol" style="display:none;"></td>
      <td name="testItemCol" style="display:none;"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
  </table>
  <div style="height:30px;margin-top:20px;">
    <span style="float:left;width:100px;font-weight:bold;"><?php echo $lang->testcase->optionItem;?></span>
    <span style="float:left;width:100px;">
      <input type='checkbox' name='optionTitle[]' id='caseCode' value='caseCode' onclick="changeTemplate('caseCode');" />
      <?php echo $lang->testcase->caseCode;?>
    </span>
    <span style="float:left;width:100px;">
      <input type='checkbox' name='optionTitle[]' id='testItem' value='testItem' onclick="changeTemplate('testItem');" />
      <?php echo $lang->testcase->testItem;?>
    </span>
  </div>
  <div>
    <input type='file' name='file' class='text-5' />
    <?php echo html::select('fileType', $lang->importEncodeList,'gbk');?>
    <?php echo html::submitButton('确定');?>
  </div>
</div>
</form>
