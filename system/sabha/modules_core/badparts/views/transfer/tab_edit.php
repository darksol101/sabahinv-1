<?php defined('BASEPATH') or die('Direct access is not allowed');?>
<?php $this->load->view('dashboard/system_messages');?>
<form action="<?php echo $this->uri->uri_string();?>" method="post" name="orderForm" id="orderForm">
    <table width="50%" cellpadding="0" cellspacing="0" class="">
      <col width="40%" />
      <col />
      <tr>
      		<th> <label> Item Number </label> </th>
            <td><?php echo $order_parts->part_number;?></td>
      </tr>
      <tr>
        <th><label>Part Quantity</label></th>
        <td><?php echo $order_parts->part_quantity;?></td>
      </tr>
      <tr>
        <th><label>From Store</label></th>
        <td><?php echo $servicecenter_select_from?></td>
      </tr>
      <tr>
        <th><label>To Store</label></th>
        <td><?php echo $servicecenter_select_to;?></td>
      </tr>
      <tr>
        <th><label>Status</label></th>
        <td><?php echo $status_select;?></td>
      </tr>
      <tr>
        <td colspan="2">
        <input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
          <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" /></td>
      </tr>
    </table>
  
  <input type="hidden" name="return_sc_id" id="return_sc_id" value="<?php echo $order_parts->return_sc_id;?>" />
 
</form>