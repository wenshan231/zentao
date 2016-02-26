<?php
/**
 * coship start
 * 月统计页面
 * 2013-09-25 add file by fujia
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<style>
.rate {
    background: none repeat scroll 0 0 #FFFACD;
}
.rowcolor {
    background: none repeat scroll 0 0 #F9F9F9;
}
</style>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <?php include 'blockreportlist.html.php';?>
    </td>
    <td class='divider'></td>
    <td>
    <div class="choose-date mb-10px f-left">
      <form method='post' enctype='multipart/form-data'>
        <?php echo html::input('beginDate', $beginDate, "class='select-7 date'");?>
        <span><?php echo $lang->report->to;?></span>
        <?php echo html::input('endDate', $endDate, "class='select-7 date'");?>
        &nbsp;&nbsp;<?php echo html::submitButton($lang->report->submit, "style='margin-top:-10px;'");?>
      </form>
    </div>
      <table class='table-1 colored tablesorter datatable border-sep' id='product'>
        <thead>
        <tr class='colhead'>
          <th colspan="2"><?php echo $lang->report->info;?></th>
          <th colspan="4"><?php echo $lang->report->scope;?></th>
          <th colspan="3"><?php echo $lang->report->schedule;?></th>
          <th colspan="6"><?php echo $lang->report->quality;?></th>
        </tr>
        <tr class='colhead'>
          <th><?php echo $lang->report->product;?></th>
          <th><?php echo $lang->report->project;?></th>
          <th><?php echo $lang->report->stories;?></th>
          <th><?php echo $lang->report->releasedStories;?></th>
          <th><?php echo $lang->report->goingOnStories;?></th>
          <th><?php echo $lang->report->achiveRate;?></th>
          <th><?php echo $lang->report->plans;?></th>
          <th><?php echo $lang->report->releasedPlans[1];?></th>
          <th><?php echo $lang->report->releasedRate;?></th>
          <th><?php echo $lang->report->testtasks;?></th>
          <th><?php echo $lang->report->releasedPlans[2];?></th>
          <th><?php echo $lang->report->passRate;?></th>
          <th><?php echo $lang->report->bugs;?></th>
          <th><?php echo $lang->report->resolvedBugs;?></th>
          <th><?php echo $lang->report->resolvedRate;?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($products)):?>
        <?php $color = false;?>
        <?php foreach($products as $product):?>
          <tr class="a-center">
            <?php $count = empty($product['projects']) ? 1 : count($product['projects']);?>
            <td align='left' rowspan="<?php echo $count;?>"><?php echo "<p>" . html::a($this->createLink('product', 'view', 'product='.$product['productId']), $product['productName']) . "</p>";?></td>
            <?php if(empty($product['projects'])) continue;?>
            <?php foreach($product['projects'] as $key => $project):?>
              <?php $class = $color ? 'rowcolor' : '';?>
              <?php if($key > 0) echo "<tr class='a-center'>"?>
                <td align='left' class="<?php echo $class;?>"><?php echo "<p>" . html::a($this->createLink('project', 'view', 'project='.$project['id']), $project['name']) . "</p>";?></td>
                <?php if($key == 0):?>
                  <td rowspan="<?php echo $count;?>"><?php echo $product['stories'];?></td>
                  <td rowspan="<?php echo $count;?>"><?php echo $product['releasedStories'];?></td>
                  <td rowspan="<?php echo $count;?>"><?php echo $product['goingOnStories'];?></td>
                  <td rowspan="<?php echo $count;?>" class="rate">
                  <?php if($product['stories'] == 0):?>
                    <?php if($product['releasedStories'] > 0):?>
                      <?php echo 'N/A';?>
                    <?php else:?>
                      <?php echo '0%';?>
                    <?php endif;?>
                  <?php else:?>
                    <?php echo round($product['releasedStories'] / $product['stories'] * 100, 2) . '%';?>
                  <?php endif;?>
                  </td>
                  <td rowspan="<?php echo $count;?>"><?php echo $product['plans'];?></td>
                  <td rowspan="<?php echo $count;?>"><?php echo $product['releasedPlans'];?></td>
                  <td rowspan="<?php echo $count;?>" class="rate">
                  <?php if($product['plans'] == 0):?>
                    <?php if($product['releasedPlans'] > 0):?>
                      <?php echo 'N/A';?>
                    <?php else:?>
                      <?php echo '0%';?>
                    <?php endif;?>
                  <?php else:?>
                    <?php echo round($product['releasedPlans'] / $product['plans'] * 100, 2) . '%';?>
                  <?php endif;?>
                  </td>
                <?php endif;?>
                <td class="<?php echo $class;?>"><?php echo $project['testtasks'];?></td>
                <?php if($key == 0):?>
                  <td rowspan="<?php echo $count;?>"><?php echo $product['releasedPlans'];?></td>
                  <td rowspan="<?php echo $count;?>" class="rate">
                  <?php if($product['totalTesttasks'] == 0):?>
                    <?php if($product['releasedPlans'] > 0):?>
                      <?php echo 'N/A';?>
                    <?php else:?>
                      <?php echo '0%';?>
                    <?php endif;?>
                  <?php else:?>
                    <?php echo round($product['releasedPlans'] / $product['totalTesttasks'] * 100, 2) . '%';?>
                  <?php endif;?>
                  </td>
                <?php endif;?>
                <td class="<?php echo $class;?>"><?php echo $project['bugs'];?></td>
                <td class="<?php echo $class;?>"><?php echo $project['resolvedBugs'];?></td>
                <td class="rate">
                  <?php if($project['bugs'] == 0):?>
                    <?php if($project['resolvedBugs'] > 0):?>
                      <?php echo 'N/A';?>
                    <?php else:?>
                      <?php echo '0%';?>
                    <?php endif;?>
                  <?php else:?>
                    <?php echo round($project['resolvedBugs'] / $project['bugs'] * 100, 2) . '%';?>
                  <?php endif;?>
                </td>
                <?php if($key > 0) echo "</tr>"?>
              <?php $color = !$color;?>
            <?php endforeach;?>
          </tr>
        <?php endforeach;?>
        <?php endif;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
