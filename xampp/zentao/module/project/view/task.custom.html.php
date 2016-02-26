<!-- coship start 2013-11-30 add file by fujia -->
<table class='cont-lt2'>
  <tr valign='top'>
    <div id="sidebar" onclick="showProject()" <?php if($this->cookie->projectBar != 'hide') echo "class='hidden'"?>></div>
    <td class='side <?php if($this->cookie->projectBar == 'hide') echo "hidden"?>' id='project'>
      <div class='box-title'>
        <?php echo $lang->project->projectTasks;?>
        <div class="f-right" id='hideButton' onclick="hideProject()"><span></span></div>
      </div>
      <div class='box-content'><?php echo $projectTree;?></div>
    </td>
    <td class='divider <?php if($this->cookie->projectBar == 'hide') echo "hidden"?>' id="project-divider"></td>
    <?php if($project->type !='sprint'):?>
    <td class='side'>
      <nobr>
      <div class='box-title'><?php echo $project->name;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php common::printLink('project', 'edit',   "projectID=$projectID", $lang->edit);?>
          <?php common::printLink('project', 'delete', "projectID=$projectID&confirm=no", $lang->delete, 'hiddenwin');?>
          <?php common::printLink('tree', 'browse',    "rootID=$projectID&view=task", $lang->tree->manage);?>
          <?php common::printLink('tree', 'fix',       "root=$projectID&type=task", $lang->tree->fix, 'hiddenwin');?>
        </div>
      </div>
      </nobr>
    </td>
    <td class='divider'></td>
    <?php endif?>
    <td>
      <form method='post' id='projectTaskForm'>
        <table class='table-1 colored tablesorter datatable'>
          <?php $vars = "projectID=$project->id&status=$status&parma=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage"; ?>
          <thead>
          <tr class='colhead'>
            <?php foreach($customFields as $fieldName):?>
            <th><nobr><?php common::printOrderLink($fieldName, $orderBy, $vars, $lang->task->$fieldName);?></nobr></th>
            <?php endforeach;?>
            <th class='w-140px {sorter:false}'><nobr><?php echo $lang->actions;?></nobr></th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($tasks as $task):?>
          <?php $taskLink = common::createLink('task', 'view', "taskID=$task->id");?>
          <tr>
            <?php $i = 0;?>
            <?php foreach($customFields as $fieldName):?>
            <?php $i ++;?>
            <td><nobr>
               <?php if($i == 1):?>
               <input type='checkbox' name='taskIDList[]'  value='<?php echo $task->id;?>'/> 
               <?php endif;?>
              <?php 
              if(preg_match('/^(id|name)$/i', $fieldName))
              {
                  echo html::a($taskLink, $task->$fieldName);
              }
              elseif(preg_match('/assignedTo|by/i', $fieldName))
              {
                  echo $users[$task->$fieldName];
              }
              elseif(preg_match('/^(type|pri|status)$/i', $fieldName))
              {
                  $key = $fieldName . 'List';
                  $list = $lang->task->$key;
                  echo $list[$task->$fieldName];
              }
              else
              {
                  echo !($task->$fieldName == '0') ? $task->$fieldName : '';
              }
              ?>
            </nobr></td>
            <?php endforeach;?>
            <td class='a-right'>
              <?php
              $params = "taskID=$task->id";
              common::printIcon('task', 'start', "projectID=$task->project&taskID=$task->id", '', 'list');
              common::printIcon('task', 'start', $params, '', 'list');
              common::printIcon('task', 'fnish', $params, '', 'list');
              common::printIcon('task', 'close',   $params, '', 'list');
              common::printIcon('task', 'edit',    $params, '', 'list');
              ?>
              </nobr>
            </td>
          </tr>
          <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan='<?php echo count($customFields) + 1?>'>
                <div class='f-left'>
                <?php 
                if(count($tasks))
                {
                    echo html::selectAll() . html::selectReverse();

                    if(common::hasPriv('task', 'batchEdit'))
                    {
                        $actionLink = $this->createLink('task', 'batchEdit', "projectID=$projectID&from=projectTask&orderBy=$orderBy");
                        echo html::commonButton($lang->edit, "onclick=\"changeAction('projectTaskForm', 'batchEdit', '$actionLink')\"");
                    }
                    if(common::hasPriv('task', 'batchClose') and strtolower($browseType) != 'closedBy')
                    {
                        $actionLink = $this->createLink('task', 'batchClose');
                        echo html::commonButton($lang->close, "onclick=\"changeAction('projectTaskForm', 'batchClose', '$actionLink')\"");
                    }
                }
                echo $summary;
                ?>
                </div>
                <?php $pager->show();?>
              </td>
            </tr>
          </tfoot>
        </table>
      </form>
    </td>
  </tr>
</table>
<script language='javascript'>
$('#project<?php echo $projectID;?>').addClass('active')
$('#<?php echo $browseType;?>Tab').addClass('active')
statusActive = '<?php echo isset($lang->project->statusSelects[$browseType]);?>';
if(statusActive) $('#statusTab').addClass('active')
</script>
<!-- coship end -->