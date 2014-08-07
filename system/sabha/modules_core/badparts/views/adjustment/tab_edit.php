<?php defined('BASEPATH') or die('Direct access is not allowed');?>
<?php $this->load->view('dashboard/system_messages');?>
<form action="<?php echo $this->uri->uri_string();?>" method="post" name="orderForm" id="orderForm">
    <table width="50%" cellpadding="0" cellspacing="0" class="">
      <col width="40%" /><col />
      <tr>
            <th> <label> Item Number </label> </th>
            <td><?php echo $adjustment->part_number;?></td>
      </tr>
      <tr>
        <th><label>Part Quantity</label></th>
        <td><?php echo $adjustment->part_quantity;?></td>
      </tr>
      <tr>
        <th><label>Store</label></th>
        <td><?php echo $adjustment->sc_name;?></td>
      </tr>
      <tr>
        <th><label>Approved</label></th>
        <td>
            <input type="checkbox"<?php if($adjustment->scraped==1){?> <?php }?> name="approved" id="approved"<?php if($adjustment->approved==1){?> checked="checked" <?php }?> />            
        </td>
      </tr>
      <?php if($adjustment->approved>0){?>
      <tr>
      	<th><label>Scraped</label></th>
        <td><input type="checkbox"<?php if($adjustment->approved==0){?> <?php }?> name="scraped" id="scraped" <?php if($adjustment->scraped==1){?> checked="checked" <?php }?> /></td>
      </tr>
      <?php }?>
      <tr>
        <td colspan="2">
        <input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
          <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" /></td>
      </tr>
    </table>
  
  <input type="hidden" name="adjustment_id" id="adjustment_id" value="<?php echo $adjustment->adjustment_id;?>" />
 
</form>