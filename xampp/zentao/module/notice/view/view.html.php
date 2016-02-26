<?php
/**
 * coship start
 * 公告查看页面
 * 2013-10-15 add file by fujia
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php echo css::internal($keTableCSS);?>
<div id='titlebar'>
  <div id='main' style="width:70%;text-align:center;font-size:35px;" <?php if($notice->deleted) echo "class='deleted'";?>><?php echo $notice->title;?></div>
  <div>
    <div class='a-center f-16px strong'>
      <?php
      $browseLink = $this->session->noticeList ? $this->session->noticeList : inlink('browse');
      $params     = "noticeID=$notice->id";
      if(!$notice->deleted)
      {
          ob_start();
          common::printIcon('notice', 'edit', $params);
          common::printIcon('notice', 'delete', $params, '', 'button', '', 'hiddenwin');
          common::printDivider();
          common::printRPN($browseLink, $preAndNext);

          $actionLinks = ob_get_contents();
          ob_end_clean();
          echo $actionLinks;
      }
      else
      {
          common::printRPN($browseLink);
      }
      ?>
    </div>
  </div>
</div>

<table class='cont-rt5'>
  <tr valign='top'>
    <td style="padding-left:50px;">
      <div class='content' style="margin-top:10px;"><?php echo $notice->content;?></div>
    </td>
    <td rowspan="3" class='divider'></td>
    <td rowspan="3" class='side'>
      <fieldset>
      <legend><?php echo $lang->notice->basicInfo;?></legend>
      <table class='table-1 a-left fixed'>
        <tr>
          <th class='rowhead'><?php echo $lang->notice->module;?></th>
          <td><?php echo $notice->moduleName ? $notice->moduleName : '/';?></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->notice->addedBy;?></th>
          <td><?php echo $users[$notice->addedBy];?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->notice->addedDate;?></th>
          <td><?php echo $notice->addedDate;?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->notice->editedBy;?></th>
          <td><?php echo $users[$notice->editedBy];?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->notice->editedDate;?></th>
          <td><?php echo $notice->editedDate;?></td>
        </tr>  
      </table>
    </td>
  </tr>
  <?php if($notice->files):?>
  <tr>
    <td style="padding-left:10px;padding-top:30px;padding-bottom:30px;">
      <div style="float:left;"><?php echo $lang->notice->files . '：';?>&nbsp;</div>
      <div style="float:left;">
      <?php foreach($notice->files as $file):?>
        <?php common::printLink('file', 'download', "fileID=$file->id", $file->title . '.' . $file->extension);?>
      <?php endforeach;?>
      </div>
    </td>
  </tr>
  <?php endif;?>
  <tr>
    <td><?php include '../../common/view/action.html.php';?></td>
  </tr>
</table>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.html.php';?>
