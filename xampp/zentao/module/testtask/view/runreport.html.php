<?php
/**
 * coship start
 * 2013-10-23 add by fujia
 * 执行结果统计页面
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
#runreport td, tr, th{ border:1px solid #E4E4E4;}
#report-list li{list-style:none outside none; white-space:nowrap; overflow:hidden;}
ul#report-list {margin:0;}
.case {margin:0 10px 5px 0; float:right; font-size:14px;}
.case-icon {
background:url("theme/default/images/main/zt-icons.png") repeat scroll -80px -322px rgba(0, 0, 0, 0);
padding:1px 7px 1px 25px;
}
.group-title {color:#009900; font-weight:bold; height:20px; margin:20px 0 0 10px;}
.rate {background:none repeat scroll 0 0 #FFFACD;}
</style>

<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <div class='box-title' style="width:230px"><?php echo $lang->testtask->browse;?></div>
      <div class='box-content' style="width:230px">
        <ul id="report-list">
        <?php
        foreach($tasks as $task)
        {
            if($task->status != 'done')
            {
                echo '<li>' . html::a(inlink('runreport', "productID=$task->product&taskID=$task->id"), $task->name) . '</li>';
            }
            if($task->id == $taskID)
            {
                $taskName = $task->name;
            }
        }
        ?>
        </ul>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <!-- 全部关联用例统计 start -->
      <div class="case"><?php echo html::a(inlink('cases', "taskID=$taskID"), $lang->testcase->legendCases, '', 'class=case-icon');?></div>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id="runreport">
        <thead>
        <tr class='colhead'>
          <th width="230px;"><?php echo $lang->testtask->common;?></th>
          <th width="65px;"><?php echo $lang->testcase->linkCases;?></th>
          <th><?php echo $lang->testcase->wait;?></th>
          <th><?php echo $lang->testcase->waitRate;?></th>
          <th><?php echo $lang->testcase->resultList['pass'];?></th>
          <th><?php echo $lang->testcase->passRate;?></th>
          <th><?php echo $lang->testcase->resultList['fail'];?></th>
          <th><?php echo $lang->testcase->failRate;?></th>
          <th><?php echo $lang->testcase->resultList['blocked'];?></th>
          <th><?php echo $lang->testcase->blockedRate;?></th>
          <th><?php echo $lang->testtask->statusList['done'];?></th>
          <th><?php echo $lang->testcase->doneRate;?></th>
        </tr>
        </thead>
        <tbody>
          <tr class="a-center">
            <td align="left">
              <span style="margin-left:5px;"><?php echo html::a(inlink('view', "taskID=$taskID"), $taskName);?></span>
            </td>
            <td><?php echo $taskCases['total'];?></td>
            <td><?php echo $taskCases['waitTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($taskCases['waitTotal'], $taskCases['total']);?></td>
            <td><?php echo $taskCases['passTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($taskCases['passTotal'], $taskCases['total']);?></td>
            <td><?php echo $taskCases['failTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($taskCases['failTotal'], $taskCases['total']);?></td>
            <td><?php echo $taskCases['blockedTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($taskCases['blockedTotal'], $taskCases['total']);?></td>
            <td><?php echo $taskCases['doneTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($taskCases['doneTotal'], $taskCases['total']);?></td>
          </tr>
        </tbody>
      </table> 
      <!-- 全部关联用例统计 end -->

      <!-- 按模块统计 start -->
      <?php if($caseByModule):?>
      <div class="group-title"><?php echo $lang->testcase->byModule;?></div>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id="runreport">
        <thead>
        <tr class='colhead'>
          <th width="230px;"><?php echo $lang->testcase->module;?></th>
          <th width="65px;"><?php echo $lang->testcase->linkCases;?></th>
          <th><?php echo $lang->testcase->wait;?></th>
          <th><?php echo $lang->testcase->waitRate;?></th>
          <th><?php echo $lang->testcase->resultList['pass'];?></th>
          <th><?php echo $lang->testcase->passRate;?></th>
          <th><?php echo $lang->testcase->resultList['fail'];?></th>
          <th><?php echo $lang->testcase->failRate;?></th>
          <th><?php echo $lang->testcase->resultList['blocked'];?></th>
          <th><?php echo $lang->testcase->blockedRate;?></th>
          <th><?php echo $lang->testtask->statusList['done'];?></th>
          <th><?php echo $lang->testcase->doneRate;?></th>
        </tr>
        </thead>
        <tbody>
          <?php foreach($modules as $moduleID => $moduleName):?>
          <?php if(!isset($caseByModule[$moduleID])) continue;?>
          <tr class="a-center">
            <td align="left">
              <span style="margin-left:5px;"><?php echo ($moduleID == 0) ? $lang->testcase->noModule : trim($moduleName, '/');?></span>
            </td>
            <td><?php echo $caseByModule[$moduleID]['total'];?></td>
            <td><?php echo $caseByModule[$moduleID]['waitTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($caseByModule[$moduleID]['waitTotal'], $caseByModule[$moduleID]['total']);?></td>
            <td><?php echo $caseByModule[$moduleID]['passTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($caseByModule[$moduleID]['passTotal'], $caseByModule[$moduleID]['total']);?></td>
            <td><?php echo $caseByModule[$moduleID]['failTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($caseByModule[$moduleID]['failTotal'], $caseByModule[$moduleID]['total']);?></td>
            <td><?php echo $caseByModule[$moduleID]['blockedTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($caseByModule[$moduleID]['blockedTotal'], $caseByModule[$moduleID]['total']);?></td>
            <td><?php echo $caseByModule[$moduleID]['doneTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($caseByModule[$moduleID]['doneTotal'], $caseByModule[$moduleID]['total']);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php endif;?>
      <!-- 按模块统计 end -->

      <!-- 按优先级统计 start -->
      <?php if($caseByPri):?>
      <div class="group-title"><?php echo $lang->testcase->byPri;?></div>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id="runreport">
        <thead>
        <tr class='colhead'>
          <th width="80px;"><?php echo $lang->testtask->pri;?></th>
          <th width="65px;"><?php echo $lang->testcase->linkCases;?></th>
          <th><?php echo $lang->testcase->wait;?></th>
          <th><?php echo $lang->testcase->waitRate;?></th>
          <th><?php echo $lang->testcase->resultList['pass'];?></th>
          <th><?php echo $lang->testcase->passRate;?></th>
          <th><?php echo $lang->testcase->resultList['fail'];?></th>
          <th><?php echo $lang->testcase->failRate;?></th>
          <th><?php echo $lang->testcase->resultList['blocked'];?></th>
          <th><?php echo $lang->testcase->blockedRate;?></th>
          <th><?php echo $lang->testtask->statusList['done'];?></th>
          <th><?php echo $lang->testcase->doneRate;?></th>
        </tr>
        </thead>
        <tbody>
          <?php foreach($caseByPri as $priID => $pri):?>
          <tr class="a-center">
            <td><?php echo $lang->testcase->priList[$priID];?></td>
            <td><?php echo $pri['total'];?></td>
            <td><?php echo $pri['waitTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($pri['doneTotal'], $pri['total']);?></td>
            <td><?php echo $pri['passTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($pri['passTotal'], $pri['total']);?></td>
            <td><?php echo $pri['failTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($pri['failTotal'], $pri['total']);?></td>
            <td><?php echo $pri['blockedTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($pri['blockedTotal'], $pri['total']);?></td>
            <td><?php echo $pri['doneTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($pri['doneTotal'], $pri['total']);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php endif;?>
      <!-- 按优先级统计 end -->

      <!-- 按指派给统计 start -->
      <?php if($caseByAssignedTo):?>
      <div class="group-title"><?php echo $lang->testcase->byAssignedTo;?></div>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id="runreport">
        <thead>
        <tr class='colhead'>
          <th width="80px;"><?php echo $lang->testtask->assignedTo;?></th>
          <th width="65px;"><?php echo $lang->testcase->linkCases;?></th>
          <th><?php echo $lang->testcase->wait;?></th>
          <th><?php echo $lang->testcase->waitRate;?></th>
          <th><?php echo $lang->testcase->resultList['pass'];?></th>
          <th><?php echo $lang->testcase->passRate;?></th>
          <th><?php echo $lang->testcase->resultList['fail'];?></th>
          <th><?php echo $lang->testcase->failRate;?></th>
          <th><?php echo $lang->testcase->resultList['blocked'];?></th>
          <th><?php echo $lang->testcase->blockedRate;?></th>
          <th><?php echo $lang->testtask->statusList['done'];?></th>
          <th><?php echo $lang->testcase->doneRate;?></th>
        </tr>
        </thead>
        <tbody>
          <?php foreach($caseByAssignedTo as $account => $assignedTo):?>
          <tr class="a-center">
            <td align="left">
              <span style="margin-left:20px;"><?php echo empty($account) ? $lang->testcase->noAssignedTo : $users[$account];?></span>
            </td>
            <td><?php echo $assignedTo['total'];?></td>
            <td><?php echo $assignedTo['waitTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($assignedTo['waitTotal'], $assignedTo['total']);?></td>
            <td><?php echo $assignedTo['passTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($assignedTo['passTotal'], $assignedTo['total']);?></td>
            <td><?php echo $assignedTo['failTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($assignedTo['failTotal'], $assignedTo['total']);?></td>
            <td><?php echo $assignedTo['blockedTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($assignedTo['blockedTotal'], $assignedTo['total']);?></td>
            <td><?php echo $assignedTo['doneTotal'];?></td>
            <td class="rate"><?php echo $this->formatCaseRate($assignedTo['doneTotal'], $assignedTo['total']);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php endif;?>
      <!-- 按指派给统计 end -->
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
