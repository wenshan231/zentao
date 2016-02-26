<?php
/**
 * coship start
 * 2013-09-04 add file by fujia
 * 测试用例导入excel预览页面
 */
?>
<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/chosen.html.php';?>
<div id="wrap">
  <div class="outer" style="min-height: 684px;">
<form method="post" target="hiddenwin">
<table class="table-1">
  <caption class="caption-tl"><?php echo $lang->testcase->importPreview;?></caption>
  <tbody>
    <tr class="colhead">
      <?php if(in_array('caseCode', $fields)) echo '<th>' . $lang->testcase->caseCode . '</th>';?>
      <?php if(in_array('testItem', $fields)) echo '<th>' . $lang->testcase->testItem . '</th>';?>
      <th><?php echo $lang->testcase->title;?></th>
      <th><?php echo $lang->testcase->pri;?></th>
      <th><?php echo $lang->testcase->type;?></th>
      <th><?php echo $lang->testcase->precondition;?></th>
      <th><?php echo $lang->testcase->steps;?>
        <table class="table-1">
        <tbody>
          <tr>
            <th><?php echo $lang->testcase->stepDesc;?></th>
            <th><?php echo $lang->testcase->stepExpect;?></th>
          </tr>
        </tbody>
        </table>
      </th>
    </tr>
    <?php foreach($cases as $caseID => $case):?>
    <tr valign="top" align="center">
      <?php if(in_array('caseCode', $fields)) echo '<td>' . html::input("caseCode[$caseID]", $case['caseCode'], 'class=text-1') . '</td>';?>
      <?php if(in_array('testItem', $fields)) echo '<td>' . html::input("testItem[$caseID]", $case['testItem'], 'class=text-1') . '</td>';?>
      <td><?php echo html::input("title[$caseID]", $case['title'], 'class=text-1');?></td>
      <td><?php echo html::select("pri[$caseID]", (array)$lang->testcase->priList, $case['pri'], 'class=select-1');?></td>
      <td><?php echo html::select("type[$caseID]", $lang->testcase->typeList, 'feature', "class=select-1");?></td>
      <td><?php echo html::textarea("precondition[$caseID]", $case['precondition'], "rows='3' class='w-p90'");?></td>
      <td><table class="table-1">
          <tbody>
            <?php for($i = 0; $i < $case['total']; $i++):?>
            <?php echo html::hidden("total[$caseID]", $case['total']);?>
            <tr>
              <td><?php if(!empty($case['steps'][$i])) echo html::textarea("steps[$caseID][$i]", $case['steps'][$i], "rows='2' class='w-p90'");?></td>
              <td><?php if(!empty($case['expects'][$i])) echo html::textarea("expects[$caseID][$i]", $case['expects'][$i], "rows='2' class='w-p90'");?></td>
            </tr>
            <?php endfor;?>
          </tbody>
          </table></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<p><?php echo html::submitButton() . html::backButton();?></p>
</form>
</div>
<?php include '../../../common/view/footer.html.php';?>
