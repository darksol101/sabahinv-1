
<?php $this->load->view('dashboard/system_messages');?>
<form action="" method="post" name="orderForm" id="orderForm">
    <table width="50%" cellpadding="0" cellspacing="0" class="">
      <col width="40%" />
      <col />
      <tr>
      		<th> <label> Item Number </label> </th>
            <td> <?php echo $bad_part_details->part_number; ?></td>
      </tr>
      <tr>
        <th><label>Part Quantity</label></th>
        <td><?php echo $bad_part_details->part_quantity; ?></td>
      </tr>
      <tr>
        <th><label>Store</label></th>
        <td><?php echo $servicecenter_select;?></td>
      </tr>
      <tr>
        <th><label>Status</label></th>
        <td><?php echo $bad_parts_status;?></td>
      </tr>
      <tr>
        <td colspan="2">
			
			
			
        <input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
          <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" /></td>
      </tr>
    </table>
  
  <input type="hidden" name="bad_parts_id" id="bad_parts_id" value="<?php echo $bad_part_details->bad_parts_id;?>" />
 
</form>