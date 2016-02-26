<?php
/**
 * The pause file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: pause.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<!-- coship start 2013-08-08 add by fujia -->
<?php include '../../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $task->name;?></caption>
    <tr>
      <td class='rowhead'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "rows='6' class='area-1'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList);?></td>
    </tr>
  </table>
  <?php include '../../../common/view/action.html.php';?>
</form>
<?php include '../../../common/view/footer.html.php';?>
<!-- coship end -->
