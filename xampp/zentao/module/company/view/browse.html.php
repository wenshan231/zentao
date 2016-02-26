<?php
/**
 * The browse view file of product dept of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4775 2013-05-05 08:25:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php 
/**
 * coship start
 * 2013-12-10 add by fujia
 */
if($userFlag == 1)
{
    include 'browse_user.html.php';
    exit;
}
/* coship end */

include '../../common/view/header.html.php';
include '../../common/view/treeview.html.php';
include '../../common/view/colorize.html.php';
/**
 * coship start
 * 2013-08-16 add by fujia
 * 增加进行任务的排序
 */
include 'tasksorter.html.php';
/* coship end */
js::set('deptID', $deptID);
?>
<table class='cont-lt1'>
  <tr><td colspan='3'><div id='querybox'><?php echo $searchForm?></div></td></tr>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->dept->common;?></div>
      <div class='box-content'>
        <?php echo $deptTree;?>
        <div class='a-right'><?php common::printLink('dept', 'browse', '', $lang->dept->manage);?></div>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <!-- coship start 2013-08-16 update by fujia -->
      <table class='table-1 tablesorter colored tasksorter'>
      <!-- coship end -->
      <!-- ////<table class='table-1 tablesorter colored tasksorter'> -->
        <thead>
        <tr class='colhead'>
          <?php $vars = "param=$param&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <th class='w-id'><?php common::printorderlink('id', $orderBy, $vars, $lang->idAB);?></th>
          <!-- coship start 2013-08-06 update by fujia -->
          <th class='w-user'><?php common::printorderlink('realname', $orderBy, $vars, $lang->user->realname);?></th>
          <!-- coship end -->
          <!-- ////<th><?php common::printorderlink('realname', $orderBy, $vars, $lang->user->realname);?></th> -->
          <!-- coship start 2013-08-06 delete by fujia
          ////<th><?php common::printOrderLink('account',  $orderBy, $vars, $lang->user->account);?></th>
          ////<th><?php common::printOrderLink('role',     $orderBy, $vars, $lang->user->role);?></th>
          ////<th><?php common::printOrderLink('email',    $orderBy, $vars, $lang->user->email);?></th>
          ////<th><?php common::printOrderLink('gender',   $orderBy, $vars, $lang->user->gender);?></th>
          ////<th><?php common::printOrderLink('phone',    $orderBy, $vars, $lang->user->phone);?></th>
          ////<th><?php common::printOrderLink('qq',       $orderBy, $vars, $lang->user->qq);?></th>
          coship end -->
          <!-- coship start 2013-08-06 update by fujia -->
          <th><?php common::printOrderLink('dept',   $orderBy, $vars, $lang->user->dept);?></th>
          <!-- coship end -->
          <!-- ////<th><?php common::printOrderLink('`join`',   $orderBy, $vars, $lang->user->join);?></th> -->
          <!-- coship start 2013-08-05 add by fujia -->
          <th  class='w-p50'>进行任务</th>
          <!-- coship end -->
          <th><?php common::printOrderLink('last',     $orderBy, $vars, $lang->user->last);?></th>
          <th><?php common::printOrderLink('visits',   $orderBy, $vars, $lang->user->visits);?></th>
          <th class='w-60px'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <form action='<?php echo $this->createLink('user', 'batchEdit', "deptID=$deptID")?>' method='post' id='userListForm'>
        <?php
        /**
         * coship start
         * 2013-08-05 add by fujia
         */
        function getTasks($userTasks, $account){
            $tasks = array();
            foreach($userTasks as $userTask) {
                if($userTask->account == $account) {
                    $tasks[] = $userTask;
                }
            }
            return $tasks;
        }
        /* coship end */
        ?>
        <?php foreach($users as $user):?>
        <tr class='a-center'>
          <td><?php echo "<input type='checkbox' name='users[]' value='$user->account'> "; printf('%03d', $user->id);?></td>
          <!-- coship start 2013-08-06 update by fujia -->
          <td><?php if(!common::printLink('user', 'profile', "account=$user->account", $user->realname)) echo $user->realname;?></td>
          <!-- coship end -->
          <!-- ////<td><?php if(!common::printLink('user', 'view', "account=$user->account", $user->realname)) echo $user->realname;?></td> -->
          <!-- coship start 2013-08-06 delete by fujia
          ////<td><?php echo $user->account;?></td>
          ////<td><?php echo $lang->user->roleList[$user->role];?></td>
          ////<td><?php echo html::mailto($user->email);?></td>
          ////<td><?php if(isset($lang->user->genderList[$user->gender])) echo $lang->user->genderList[$user->gender];?></td>
          ////<td><?php echo $user->phone;?></td>
          ////<td><?php if($user->qq) echo html::a("tencent://message/?uin=$user->qq", $user->qq);?></td>
          coship end -->
          <!-- coship start 2013-08-06 update by fujia -->
          <td><?php echo $user->deptName;?></td>
          <!-- coship end -->
          <!-- ////<td><?php echo $user->join;?></td> -->
          <!-- coship start 2013-08-05 add by fujia -->
		  <td align="left"><?php 
		  	$tasks = $tasksMap[$user->account];
			if(count($tasks) == 0) {
				echo "<font color=red>". 
					html::a($this->createLink('user', 'task', "account=$user->account"), "无进行中任务") . 
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
		   ?></td>
          <!-- coship end -->
          <td><?php echo date('Y-m-d', $user->last);?></td>
          <td><?php echo $user->visits;?></td>
          <td class='a-left'>
            <?php 
            /**
             * coship start
             * 2013-08-06 add by fujia
             */
            common::printIcon('user', 'task', "account=$user->account", '', 'list');
            /* coship end */
            common::printIcon('user', 'edit',      "userID=$user->id&from=company", '', 'list');
            if(strpos($this->app->company->admins, ",{$user->account},") === false) common::printIcon('user', 'delete', "userID=$user->id", '', 'list', '', "hiddenwin");
            if((strtotime(date('Y-m-d H:i:s')) - strtotime($user->locked)) < $this->config->user->lockMinutes * 60) 
            {
                common::printIcon('user', 'unlock', "userID=$user->account", '', 'list', '', "hiddenwin");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
          <td colspan='12'>
          <?php
          echo html::selectAll() . html::selectReverse();
          echo html::submitButton($lang->edit, 'onclick=batchEdit()');
          echo html::submitButton($lang->user->contacts->manage, 'onclick=manageContacts()');
          $pager->show();
          ?>
          </td>
        </tr>
        </tfoot>
      </table>
    </td>
  </tr>
</table>
<script lanugage='javascript'>$('#dept<?php echo $deptID;?>').addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
