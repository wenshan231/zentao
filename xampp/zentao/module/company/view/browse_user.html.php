<?php
/**
 * coship start
 * 查看团队成员进行中任务
 * 2013-12-10 add file by fujia
 */
?>
<?php 
include '../../common/view/header.html.php';
include '../../common/view/tablesorter.html.php';
?>
<table class='cont-lt1'>
  <tr valign='top'>
    
    <td class='divider'></td>
    <td>
      <table class='table-1 tablesorter'>
        <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->idAB;?></th>
          <th class='w-user'><?php echo $lang->user->realname;?></th>
          <th><?php echo $lang->user->dept;?></th>
                  <th  class='w-p60'>进行任务</th>
        </tr>
        </thead>
    
    <tbody>

      <?php foreach($users as $user):?>
      <tr class='a-center'>          
                <?php if($user->id == $userId){?>
              <td><?php echo $user->id;?></td>
              <td><?php if(!common::printLink('user', 'profile', "account=$user->account", $user->realname)) echo $user->realname;?></td>
              <td><?php echo $user->deptName?></td>
                      <td align="left"><?php 
                          $tasks = $tasksMap[$user->account];
                            if(count($tasks) == 0) {
                                echo "<font color=red>". 
                                html::a($this->createLink('user', 'task', "account=$user->account"), "无进行中任务") . 
                                "</font>";
                            }
                            else {
                            for($i=0, $count=count($tasks); $i< $count; $i++) {
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
                        }?></td>
                <?php }?>

        </tr>
     <?php endforeach;?>
        </tbody>
      </table>
    </td>
  </tr>
</table>
<script lanugage='Javascript'>$('#dept<?php echo $deptID;?>').addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
