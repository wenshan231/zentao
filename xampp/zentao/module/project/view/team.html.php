<?php
/**
 * The team view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: team.html.php 4143 2013-01-18 07:01:06Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<!-- coship start 2013-08-05 update by fujia -->
<table align='center'  class='table-1 fixed colored tablesorter datatable'>
<!-- coship end -->
<!-- ////<table align='center' class='table-5 tablesorter'> -->
  <thead>
  <tr class='colhead'>
    <th><?php echo $lang->team->account;?></th>
    <th><?php echo $lang->team->role;?></th>
    <!-- coship start 2013-08-05 delete by fujia
    ////<th><?php echo $lang->team->join;?></th>
    ////<th><?php echo $lang->team->days;?></th>
    ////<th><?php echo $lang->team->hours;?></th>
    ////<th><?php echo $lang->team->totalHours;?></th>
    ////<th><?php echo $lang->actions;?></th>
    -->
    <!-- coship start 2013-08-05 add by fujia -->
    <th  class='w-p60'>进行任务</th>
    <!-- coship end -->
    <?php if(common::hasPriv('project', 'unlinkmember')) echo "<th>$lang->actions</th>";?>
  </tr>
  </thead>
  <tbody>
  <?php $totalHours = 0;?>
  <?php foreach($teamMembers as $member):?>
  <tr class='a-center'>
    <td>
    <?php 
    if(!common::printLink('user', 'view', "account=$member->account", $member->realname)) print $member->realname;
    $memberHours = $member->days * $member->hours;
    $totalHours  += $memberHours;
    ?>
    </td>
    <td><?php echo $member->role;?></td>
    <!-- coship start 2013-08-05 delete by fujia
    ////<td><?php echo substr($member->join, 2);?></td>
    ////<td><?php echo $member->days;?></td>
    ////<td><?php echo $member->hours;?></td>
    ////<td><?php echo $memberHours;?></td>
    -->
    <!-- coship start 2013-08-05 add by fujia -->
 	<td align="left"><?php 
			$tasks = $tasksMap[$member->account];
			if(count($tasks) == 0) {
				echo "<font color=red>". 
					html::a($this->createLink('user', 'task', "account=$member->account"), "无进行中任务") . 
					"</font>";
			}
			else {
				for($i=0, $count=count($tasks); $i< $count; $i++) {
					//common::printLink('user', 'task', "account=$user->account", $task->name);
					//if(isset($task->delay))
					//echo "<br>";
					$task = $tasks[$i];
					$delayTxt = isset($task->delay) ? "<font color=red>[迟]</font>" : "";
					echo /*"#$task->id " . */
							"[".substr($task->deadline, 5, 6) . "] " .
							html::a($this->createLink('project', 'task', 'project=' . $task->project), $task->projectName) . 
							"，" .
							html::a($this->createLink('task', 'view', "task=$task->id"), mb_substr($task->name, 0, 25, 'utf-8')) . 
						 $delayTxt;
					if($i < $count-1) {
						echo "<br>";
					}
				}
			}
		   ?>
    </td>
    <!-- coship end -->
    <td><?php common::printIcon('project', 'unlinkMember', "projectID=$project->id&account=$member->account", '', 'list', '', 'hiddenwin');?></td>
  </tr>
  <?php endforeach;?>
  </tbody>     
  <tfoot>
  <tr>
    <!-- coship start 2013-08-05 update by fujia -->
    <td colspan='4'>
    <!-- coship end -->
    <!-- ////<td colspan='7'> -->
      <div class='f-left'><?php echo $lang->team->totalHours . '：' .  "<strong>$totalHours</strong>";?></div>
      <div class='f-right'><?php common::printLink('project', 'managemembers', "projectID=$project->id", $lang->project->manageMembers);?></div>
    </td>
  </tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
