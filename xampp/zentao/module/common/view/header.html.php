<?php
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
include 'header.lite.html.php';
include 'colorbox.html.php';
include 'chosen.html.php';
//include 'validation.html.php';
?>
<?php if(empty($_GET['onlybody']) or $_GET['onlybody'] != 'yes'):?>
<div id='header'>
  <table class='cont' id='topbar'>
    <tr>
      <td class='w-p50'>
        <?php
        echo "<span id='companyname'>{$app->company->name}</span> ";
        if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
        if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');
        /**
         * coship start
         * 2013-12-27 add by fujia
         */
        echo html::a('http://192.168.99.102:8002/',  'confluence',  '_blank');
        /* coship end */
        ?>
      </td>
      <td class='a-right'>
        <!-- coship start 2013-12-20 update by fujia -->
        <?php echo html::a($this->createLink('notice', 'shining'), $lang->common->shining, '_blank');?>
        <?php commonModel::printTopBar();?>
        <!-- coship end -->
      </td>
    </tr>
  </table>
  <table class='cont' id='navbar'>
    <tr><td id='mainmenu'><?php commonModel::printMainMenu($this->moduleName); commonModel::printSearchBox();?></td></tr>
  </table>
</div>
<table class='cont' id='navbar'><tr><td id='modulemenu'><?php commonModel::printModuleMenu($this->moduleName);?></td></tr></table>
<div id='wrap'>
<?php endif;?>
  <div class='outer'>
