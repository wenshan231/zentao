<?php
/**
 * The create view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/chosen.html.php';
include '../../common/view/autocomplete.html.php';
include '../../common/view/alert.html.php';
include '../../common/view/kindeditor.html.php';
js::set('holders', $lang->bug->placeholder);
////js::set('userList', array_keys($users));
js::set('page', 'create');
?>
<!-- coship start 2013-08-15 update by fujia -->
<script language='Javascript'>
var userList = "<?php echo join(',', array_keys($users));?>".split(',');
var browserDiyList = "<?php echo join(',', array_keys($browserDiys));?>".split(',');
</script>
<!-- coship end -->
<form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
  <table class='table-1'> 
    <caption><?php echo $lang->bug->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->lblProductAndModule;?></th>
      <td>
        <?php 
		/** coship start
		 * 2014-01-22 update by shukaiming
		 */
        	echo "<select id='product' class='select-3' name='product'>
 		    <option value='$productID'>$productName</option></select>";
		/* coship end */
        ?>
        <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "onchange=setAssignedTo() ");?></span>
      </td>
     </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->bug->project;?></th>
      <td><span id='projectIdBox'>
       <?php 
         /**
          * coship start
          * 2014-01-21 add/update by shukaiming
          */
      	  $user = $this->app->user->account;
	      $userGroupID = $this->loadModel('user')->getGroups($user);
	      $userGroupID = array_values($userGroupID);//处理获得的用户权限组ID
	      $userGroupStatusID = $userGroupID[0];
	      $boolResult = ($userGroupStatusID == '200003');
       ?>
      <?php if($boolResult)
           {
      		echo html::select('project', $projects, $projectID, 'class=select-3');
           }else{
           	echo html::select('project', $projects, $projectID, 'class=select-3 onchange=loadProjectRelated(this.value)');
           }
           /* coship end */
      ?></span></td>
     </tr>
     <tr>
      <th class='rowhead'><?php echo $lang->bug->openedBuild;?></th>
      <td>
        <span id='buildBox'>    
        <?php 
        /**
         * coship start 
         * 2014-01-21 add/update by shukaiming
         */
        if($boolResult){
        	echo html::select('openedBuild[]', $builds=array('trunk'=>'Trunk'), $buildID='trunk', 'size=3 class=select-3');
        }else {
        	echo html::select('openedBuild[]', $builds, $buildID, 'size=3 multiple=multiple class=select-3');
        }
        /* coship end */
        ?>
        </span>
          <!-- coship start 2014-02-28 add by shukaiming 增加影响版本排序和最新版本提示 -->
          <div id="lastestBuild">
              <?php echo $lang->bug->lastestBuildNote;?>
          </div>
         <!-- coship end -->
        <?php 
        /**
         * coship start
         * 2014-01-21 add/update by shukaiming
         */
        if($boolResult)echo $lang->build->noticeToDeveloper;
        if((count($builds) == 1)&&!$boolResult) echo $lang->build->notice;
        /* coship end */
        ?>
      </td>
    </tr>
    <!-- coship start 2013-10-17 add by fujia -->
    <tr>
      <td class='rowhead'><?php echo $lang->bug->pri;?></td>
      <td><?php echo html::select('pri', $lang->bug->priList, $pri, 'class=select-3');?>
    </tr>
    <!-- coship end -->
    <tr>
      <th class='rowhead'><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
      <td><span id='assignedToBox'><?php echo html::select('assignedTo', $users, $assignedTo, 'class=select-3');?></span></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->title;?></th>
      <td><?php echo html::input('title', $bugTitle, "class='text-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->bug->steps;?></th>
      <td>
        <div class='w-p85 bd-none padding-zero f-left'><?php echo html::textarea('steps', $steps, "rows='10'");?></div>
        <div class='bd-none pl-10px f-left' id='tplBox'><?php echo $this->fetch('bug', 'buildTemplates');?></div>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->bug->lblStory;?></th>
      <td>
        <span id='storyIdBox'><?php echo html::select('story', $stories, $storyID);?></span>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->bug->task;?></th>
      <td><span id='taskIdBox'><?php echo html::select('task', $tasks, $taskID);?></span></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->lblTypeAndSeverity;?></th>
      <td> 
        <?php echo html::select('type', $lang->bug->typeList, $type, 'class=select-2');?> 
        <?php echo html::select('severity', $lang->bug->severityList, $severity, 'class=select-2');?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><nobr><?php echo $lang->bug->lblSystemBrowserAndHardware;?></nobr></th>
      <td>
        <?php echo html::select('os', $lang->bug->osList, $os, 'class=select-2');?>
        <?php echo html::select('browser', $lang->bug->browserList, $browser, 'class=select-2');?>
        <!-- coship start 2014-02-12 add by shukaiming -->
        <?php
        echo html::input('browserDiy',$browserDiy,'class=text-diy');echo $lang->bug->browserDiyAttention;
        ?>
        <!-- coship end -->
      </td>
    </tr>
    <tr>
      <th class='rowhead'><nobr><?php echo $lang->bug->lblMailto;?></nobr></th>
      <td>
        <?php 
        echo html::input('mailto', $mailto, 'class=text-1');
        //if($contactLists) echo html::select('', $contactLists, '', "onchange=\"setMailto('mailto', this.value)\"");
        ?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->bug->keywords;?></th>
      <td><?php echo html::input('keywords', $keywords, "class='text-1'");?></td>
    </tr>
    <!-- coship start 2014-02-12 add by shukaiming -->
    <tr>
      <th class='rowhead'><?php echo $lang->bug->lblRecurrenceRate;?></th>
      <td><?php echo html::select('recurrenceRate', $lang->bug->recurrenceRateList,$recurrenceRate, "class=select-2");?></td>
     </tr>
    <!-- coship end -->
    <tr>
      <th class='rowhead'><?php echo $lang->bug->files;?></th>
      <td><?php echo $this->fetch('file', 'buildform', 'fileCount=2&percent=0.85');?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'>
        <?php echo html::submitButton() . html::backButton() . html::hidden('case', $caseID);?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
