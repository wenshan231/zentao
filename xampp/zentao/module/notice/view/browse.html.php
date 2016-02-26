<?php
/**
 * coship start
 * 公告列表页面
 * 2013-10-10 add file by fujia
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php js::set('moduleID', $moduleID);?>
<table class='cont-lt3'>
  <tr valign='top'>
    <td class='side' id='treebox'>
      <div class='box-title'><?php echo $lang->notice->index;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php common::printLink('tree', 'browse', "rootID=0&view=notice", $lang->notice->manageType);?>
          <?php common::printLink('tree', 'fix', "root=0&type=notice", $lang->tree->fix, 'hiddenwin');?>
        </div>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable'>
        <thead>
          <tr class='colhead'>
            <?php $vars = "module=$moduleID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&browseType=$browseType";?>
            <th class='w-id'>   <?php common::printOrderLink('id',        $orderBy, $vars, $lang->idAB);?></th>
            <th width="100%">   <?php common::printOrderLink('title',     $orderBy, $vars, $lang->notice->title);?></th>
            <th class='w-100px'><?php common::printOrderLink('addedBy',   $orderBy, $vars, $lang->notice->addedBy);?></th>
            <th class='w-120px'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->notice->addedDate);?></th>
            <?php $hasPriv  = common::hasPriv('notice', 'edit') || common::hasPriv('notice', 'delete');?>
            <?php if($hasPriv):?>
            <th class='w-100px {sorter:false}'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($notices as $key => $notice):?>
          <?php $viewLink = $this->createLink('notice', 'view', "noticeID=$notice->id");?>
          <tr class='a-center'>
            <td><?php echo html::a($viewLink, sprintf('%03d', $notice->id), '_blank');?></td>
            <td class='a-left' title="<?php echo $notice->title?>"><nobr><?php echo html::a($viewLink, $notice->title, '_blank');?></nobr></td>
            <td><?php isset($users[$notice->addedBy]) ? print($users[$notice->addedBy]) : print($notice->addedBy);?></td>
            <td><?php echo date("m-d H:i", strtotime($notice->addedDate));?></td>
            <?php if($hasPriv):?>
            <td>
              <?php 
              $vars = "notice={$notice->id}";
              common::printIcon('notice', 'edit',   $vars, '', 'list');
              common::printIcon('notice', 'delete', $vars, '', 'list', '', 'hiddenwin');
              ?>
            </td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
      </table>
    </td>              
  </tr>    
</table>  
<script type="text/javascript">
$(document).ready(function()
{
    $('#module' + moduleID).addClass('active'); 
});
</script>
<?php include '../../common/view/footer.html.php';?>
