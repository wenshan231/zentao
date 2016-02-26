<?php
/**
 * The edit view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: edit.html.php 1150 2010-11-09 07:19:37Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-4'> 
      <caption><?php echo $lang->product->edit;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->product->name;?></th>
        <td class='a-left'><input type='text' name='name' value='<?php echo $product->name;?>' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->product->code;?></th>
        <td class='a-left'><input type='text' name='code' value='<?php echo $product->code;?>' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->product->desc;?></th>
        <td class='a-left'><textarea name='desc' style='width:100%' rows='5'><?php echo $product->desc;?></textarea></td>
      </tr>  
      <tr>
        <td colspan='2'>
          <input type='submit' value='<?php echo $lang->product->saveButton;?>' accesskey='S' />
          <input type='reset'  value='<?php echo $lang->reset;?>' />
          <input type='hidden' value='<?php echo $product->id;?>' name='id' />
        </td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>