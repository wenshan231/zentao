<?php
/**
 * The doc view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<table class='table-1 fixed colored tablesorter' align='center'>
  <caption class='caption-tl pb-10px'>
    <div class='f-left'> <?php echo $lang->project->doc;?></div>
    <div class='f-right'><?php common::printIcon('doc', 'create', "libID=project&moduleID=0&productID=0&projectID=$project->id&from=project");?></div>
  </caption>
  <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th><?php echo $lang->doc->module;?></th>
      <th><?php echo $lang->doc->title;?></th>
       <!-- coship start 2013-08-05 add by fujia -->
       <th class='w-100px'>UBB链接</th>
       <!-- coship end -->
      <th><?php echo $lang->doc->addedBy;?></th>
      <th><?php echo $lang->doc->addedDate;?></th>
      <th class='w-100px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($docs as $key => $doc):?>
    <?php
    $viewLink = $this->createLink('doc', 'view', "docID=$doc->id");
    $canView  = common::hasPriv('doc', 'view');
    ?>
    <tr class='a-center'>
      <td><?php if($canView) echo html::a($viewLink, sprintf('%03d', $doc->id)); else printf('%03d', $doc->id);?></td>
      <td><?php @print($modules[$doc->module]);?></td>
      <td class='a-left nobr'><nobr><?php echo html::a($viewLink, $doc->title);?></nobr></td>
      <!-- coship start 2013-08-05 add by fujia -->
      <td><input type="text"  value="<?php echo "[url='doc-view-{$doc->id}']{$doc->title}[/url]";    ?>"  /> </td>
      <!-- coship end -->
      <td><?php echo $users[$doc->addedBy];?></td>
      <td><?php echo $doc->addedDate;?></td>
      <td>
        <?php 
        $vars = "doc={$doc->id}";
        common::printIcon('doc', 'edit',   $vars);
        common::printIcon('doc', 'delete', $vars, '', 'list', '','hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
