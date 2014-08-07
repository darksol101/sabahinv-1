<?php
$data = array( 
				array("S.No"=>"",$this->lang->line('partnumber') => "", $this->lang->line('description') => "", $this->lang->line('landing_price') => "",$this->lang->line('customer_price')=>"",$this->lang->line('service_center_price')=>""), 
					);

$flag = false; foreach($data as $row) {
	if(!$flag) { 
		// display field/column names as first row
		echo implode("\t", array_keys($row)) . "\r\n"; $flag = true; 
	} 
	echo implode("\t", array_values($row)) . "\r\n"; 
} ?>

<?php /*?><table width="70%" border="0" cellspacing="0" cellpadding="0" border="1" bordercolor="#000000">
<col width="1%" /><col /><col width="20%" /><col width="10%" /><col width="10%" />
  <thead>
    <tr>
      	<th style="text-align: center;background: none repeat scroll 0 0 #00689C; color:#FFF">S.No.</th>
        <th style="text-align:left;background: none repeat scroll 0 0 #00689C; color:#FFF"><label><?php echo $this->lang->line('partnumber');?></label></th>
        <th style="text-align: center;background: none repeat scroll 0 0 #00689C; color:#FFF"><label><?php echo $this->lang->line('description');?></label></th>
        <th style="text-align: center;background: none repeat scroll 0 0 #00689C; color:#FFF;"><label><?php echo $this->lang->line('landing_price');?></label></th>
        <th style="text-align: center;background: none repeat scroll 0 0 #00689C; color:#FFF"><label><?php echo $this->lang->line('customer_price');?></label></th>
        <th style="text-align: center;background: none repeat scroll 0 0 #00689C; color:#FFF"><label><?php echo $this->lang->line('service_center_price');?></label></th>
    </tr>
  </thead>
  <tbody>
  <?php for($i=0;$i<20;$i++){?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php }?>
  </tbody>
</table>
<?php */?>