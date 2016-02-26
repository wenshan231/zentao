<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $task->name;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->task->consumed;?></th>
      <td><?php echo html::input('consumed', $task->consumed, "class='text-3'") . $lang->task->hour;?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->task->assignedTo;?></td>
      <td><?php echo html::select('assignedTo', $users, $task->openedBy, "class='select-3'");?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->task->finishedDate;?></td>
      <td><?php echo html::input('finishedDate',$date, "class='text-3'");?></td>
    </tr>

    <tr>
      <td class='rowhead'><?php echo $lang->comment;?></td>
      <!-- coship start 2013-08-05 update by fujia -->
      <td>
        <?php echo html::textarea('comment', "相关文档：", "rows='6' class='area-1'");?>
        <br>将文档库中的文档UBB链接copy到备注中，可生成文档链接。
      </td>
      <!-- coship end -->
      <!-- ////<td><?php echo html::textarea('comment', '', "rows='6' class='area-1'");?></td> -->
    </tr>
      <!-- coship start 2013-08-05 add by fujia -->
      <th class='rowhead'><font size=+2 color=red>提醒:</font></th>
      <br><td>
        点击此链接总结一下吧:
        <?php common::printLink('doc', 'create', "libID=project&moduleID=0&productID=0&projectID=$task->project&from=project&taskID=$task->id", "撰写文档", "_blank"); ?>
        。如：解决问题的心得体会，设计文档、BUG的分析等。
      </td>
      <!-- coship end -->
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton();?></td>
    </tr>
  </table>
  <?php include '../../common/view/action.html.php';?>
</form>
<?php include '../../common/view/footer.html.php';?>
