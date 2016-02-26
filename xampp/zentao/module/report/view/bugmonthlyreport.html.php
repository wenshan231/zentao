<?php
/**
 * coship start
 * Bug环比数据
 * 2013-09-27 add file by fujia
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<style>
.light {
    background: none repeat scroll 0 0 #FFFACD;
}
.dark {
    background: none repeat scroll 0 0 #EEEACD;
}
</style>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <?php include 'blockreportlist.html.php';?>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1'>
        <tr valign='top'>
          <!--<td><?php echo $charts['assignedTo'];?></td>-->
          <td width='300'>
            <div style="height:400px; overflow:auto">
              <table class='table-1 colored'>
                <caption><?php echo $lang->report->resolveSpeed;?></caption>
                <tr>
                  <th><?php echo $lang->report->month;?></th>
                  <th><?php echo $lang->report->newBugs;?></th>
                  <th><?php echo $lang->report->resolvedBugs;?></th>
                  <th><?php echo $lang->report->openingBugs;?></th>
                </tr>
                <?php
                $lastMonth = '';
                $color = false;
                foreach($datas as $key =>$data)
                {
                    $class = $color ? 'dark' :'light';
                ?>
                    <tr class='a-center'>
                      <td><?php echo $key;?></td>
                      <td><?php echo $data['opened'];?></td>
                      <td><?php echo $data['resolved'];?></td>
                <?php
                      if(!empty($lastMonth))
                      {
                          $openingBugs = $openingBugs + $data['opened'] - $data['resolved'];
                      }
                      else
                      {
                          $openingBugs = $data['opened'] - $data['resolved'];
                      }
                      $lastMonth = $key;
                ?>
                      <td class="<?php echo $class;?>"><?php echo $openingBugs;?></td>
                    </tr>
                <?php
                    $color = !$color;;
                }
                ?>
              </table>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php //echo $renderJS;?>
<?php include '../../common/view/footer.html.php';?>
