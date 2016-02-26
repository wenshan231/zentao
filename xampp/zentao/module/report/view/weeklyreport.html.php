<?php
/**
 * coship start
 * 周统计页面
 * 2013-09-24 add file by fujia
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <?php include 'blockreportlist.html.php';?>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1'>
        <tr valign='top'>
          <td><?php echo $charts['assignedTo'];?></td>
          <td width='300'>
            <div style="height:410px; overflow:auto">
              <table class='table-1 colored'>
                <caption><?php echo $lang->report->bugAssignedTo;?></caption>
                <tr>
                  <th><?php echo $lang->report->ranking;?></th>
                  <th><?php echo $lang->report->realname;?></th>
                  <th><?php echo $lang->report->bugs;?></th>
                </tr>
                <?php foreach($datas['assignedTo'] as $key =>$data):?>
                <tr class='a-center'>
                  <td><?php echo $key +1;?></td>
                  <td><?php echo $data->name;?></td>
                  <td><?php echo $data->value;?></td>
                </tr>
                <?php endforeach;?>
              </table>
              <div style="float:right;font-weight:bold;"><?php echo html::a($this->createLink('report', 'export', 'exportType=assignedTo'), $lang->report->exportBugs, '', 'class=export');?></div>
            </div>
          </td>
        </tr>
        <tr valign='top'>
          <td><?php echo $charts['openedBy'];?></td>
          <td width='300'>
            <div style="height:400px; overflow:auto">
              <table class='table-1 colored'>
                <caption><?php echo $lang->report->bugOpenedBy;?></caption>
                <tr>
                  <th><?php echo $lang->report->ranking;?></th>
                  <th><?php echo $lang->report->realname;?></th>
                  <th><?php echo $lang->report->bugs;?></th>
                </tr>
                <?php foreach($datas['openedBy'] as $key =>$data):?>
                <tr class='a-center'>
                  <td><?php echo $key +1;?></td>
                  <td><?php echo $data->name;?></td>
                  <td><?php echo $data->value;?></td>
                </tr>
                <?php endforeach;?>
              </table>
              <div style="float:right;font-weight:bold;"><?php echo html::a($this->createLink('report', 'export', 'exportType=openedBy'), $lang->report->exportBugs, '', 'class=export');?></div>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php echo $renderJS;?>
<?php include '../../common/view/footer.html.php';?>
