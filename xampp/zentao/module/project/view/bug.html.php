<?php
/**
 * The bug view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: bug.html.php 4660 2013-04-17 08:22:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<table class='table-1 fixed colored tablesorter'>
  <caption class='caption-tl pb-10px'>
    <div class='f-left'>
      <?php 
      echo $lang->project->bug;
      if($build) echo '<span class="red">(Build:' . $build->name . ')</span>';
      ?>
    </div>
    <div class='f-right'><?php common::printIcon('bug', 'create', "productID=$productID&extra=projectID=$project->id");?></div>
  </caption>
  <thead>
  <tr class='colhead'>
    <?php $vars = "projectID={$project->id}&orderBy=%s&build=$buildID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
    <th class='w-id'>      <?php common::printOrderLink('id',           $orderBy, $vars, $lang->idAB);?></th>
    <th class='w-severity'><?php common::printOrderLink('severity',     $orderBy, $vars, $lang->bug->severityAB);?></th>
    <th class='w-pri'>     <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
    <th>                   <?php common::printOrderLink('title',        $orderBy, $vars, $lang->bug->title);?></th>
    <th class='w-user'>    <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
    <th class='w-user'>    <?php common::printOrderLink('assignedTo',   $orderBy, $vars, $lang->assignedToAB);?></th>
    <th class='w-user'>    <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedBy);?></th>
    <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>
    <th class='w-140px {sorter:false}'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($bugs as $bug):?>
  <tr class='a-center'>
    <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id, '_blank');?></td>
    <td><span class='<?php echo 'severity' . $lang->bug->severityList[$bug->severity]?>'><?php echo $lang->bug->severityList[$bug->severity]?></span></td>
    <td><span class='<?php echo 'pri' . $lang->bug->priList[$bug->pri]?>'><?php echo $lang->bug->priList[$bug->pri]?></span></td>
    <td class='a-left' title="<?php echo $bug->title?>"><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
    <td><?php echo $users[$bug->openedBy];?></td>
    <td><?php echo $users[$bug->assignedTo];?></td>
    <td><?php echo $users[$bug->resolvedBy];?></td>
    <!-- coship start 2013-12-27 add by fujia -->
    <?php if($bug->toTask):?>
    <td style="background:#FFFF77"><?php echo 'T'.$lang->bug->resolutionList[$bug->resolution];?></td>
    <?php else:?>
    <!-- coship end -->
    <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
    <!-- coship start 2013-12-27 add by fujia -->
    <?php endif;?>
    <!-- coship end -->
    <!-- coship start 2013-10-17 add by fujia -->
    <?php if($projectStatus == 'suspended' && $bug->status != 'closed'):?>
    <td style="background:#FFFF77"><?php echo $lang->project->suspend;?></td>
    <?php else:?>
    <!-- coship end -->
    <td class='a-right'>
      <?php
      $params = "bugID=$bug->id";
      common::printIcon('bug', 'confirmBug', $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'assignTo',   $params, '',   'list', '', '', 'iframe', true);
      common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'edit',       $params, $bug, 'list');
      common::printIcon('bug', 'create',     "product=$bug->product&extra=bugID=$bug->id", $bug, 'list', 'copy');
      ?>
    </td>
    <!-- coship start 2013-10-17 add by fujia -->
    <?php endif;?>
    <!-- coship end -->
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='9'><?php $pager->show();?></td></tr></table>
</table>
<?php include '../../common/view/footer.html.php';?>
