<table cellpadding="0" cellspacing="0" width="100%">
	<col />
	<col />
	<col />
	<col />
	<thead>
		<tr>
			<th
				style="text-align: left; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('S.No.');?></th>
			<th
				style="text-align: left; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('service_center');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('zone');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('district');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('dity');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('products');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	$data = json_decode($json);
	foreach($data as $row){
		if(count($row)>0){
			$trstyle=$i%2==0?' style="text-align:left;vertical-align:top;background: none repeat scroll 0 0 #F2F9FC;"':' style="text-align:left;vertical-align:top;"';
			?>
		<tr <?php echo $trstyle;?>>
		<?php foreach($row as $v){
			$arr  = explode("_",$v);
			
			?>
			<td rowspan="<?php echo $arr[1];?>"><?php echo $arr[0];?></td>
			<?php  }?>
		</tr>
		<?php $i++;} }?>
	</tbody>
</table>
