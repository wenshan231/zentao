<?php
/**
 * The create view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/autocomplete.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('holders',  $lang->task->placeholder); ?>
<!-- coship start 2013-08-15 update by fujia -->
<script language='javascript'> var userList = "<?php echo join(',', array_keys($users));?>".split(',');</script>
<!-- coship end -->
<?php ////js::set('userList', array_keys($users)); ?>
<form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
  <table align='center' class='table-1 a-left'> 
    <caption>
      <?php echo $lang->task->create;?>
      <div class='f-right'>
      <?php 
      common::printLink('project', 'importTask', "project=$project->id", $lang->project->importTask);
      common::printLink('project', 'importBug', "projectID=$project->id", $lang->project->importBug);
      ?>
      </div>
    </caption>
    <tr>
      <th class='rowhead'><?php echo $lang->task->project;?></th>
      <td><?php echo $project->name;?></td>
    </tr>  
    <?php if($project->type != 'sprint'):?>
    <tr>
      <th class='rowhead'><?php echo $lang->task->module;?></th>
      <td><span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $task->module, "class='select-3'");?></span></td>
    </tr>  
    <?php endif;?>
    <tr>
      <th class='rowhead'><?php echo $lang->task->assignedTo;?></th>
      <td><!-- ////<?php echo html::select('assignedTo[]', $members, $task->assignedTo, 'class=select-3');?> -->
        <!-- coship start 2013-12-11 update by fujia -->
        <?php echo html::select('assignedTo[]', $members, $task->assignedTo, 'class=select-3 onchange="showTaskButton();"');?>
        <button id='showTasks' type="button" class="hidden">显示进行中的任务</button>
        <!-- coship end -->
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->story;?></th>
      <td>
        <?php echo html::select('story', $stories, $task->story, 'class=select-1 onchange=setPreview();');?>
        <a href='' id='preview' class='iframe'><?php echo $lang->preview;?></a>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->name;?></th>
      <td>
      <?php
      echo html::input('name', $task->name, "class='text-1'");
      echo html::commonButton($lang->task->copyStoryTitle, 'onclick=copyStoryTitle()');?>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->desc;?></th>
      <td><?php echo html::textarea('desc', $task->desc, "rows='7' class='area-1'");?>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->pri;?></th>
      <td><?php echo html::select('pri', $lang->task->priList, $task->pri, 'class=select-3');?> 
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->estimate;?></th>
      <td><?php echo html::input('estimate', $task->estimate, "class='text-3'") . $lang->task->hour;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->estStarted;?></th>
      <td><?php echo html::input('estStarted', $task->estStarted, "class='text-3 date'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->deadline;?></th>
      <td><?php echo html::input('deadline', $task->deadline, "class='text-3 date'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->type;?></th>
      <td><?php echo html::select('type', $lang->task->typeList, $task->type, 'class=select-3 onchange="setOwners(this.value)"');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->mailto;?></th>
      <td>
        <?php
        echo html::input('mailto', $task->mailto, 'class="text-1"');
        if($contactLists) echo html::select('', $contactLists, '', "onchange=\"setMailto('mailto', this.value)\"");
        ?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->files;?></th>
      <td class='a-left'><?php echo $this->fetch('file', 'buildform');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->afterSubmit;?></th>
      <td><?php echo html::radio('after', $lang->task->afterChoices, 'continueAdding');?></td> 
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::backButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
