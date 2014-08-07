<table cellpadding="0" cellspacing="0" border="1">
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<thead>
		<tr>
			<th colspan="15"
				style="background: none repeat scroll 0 0 #00689C; color: #FFF"><?php echo sprintf($this->lang->line('daily_service_report'),$report_dt);?></th>
		</tr>
	</thead>
</table>
<table width="100%" cellpadding="0" cellspacing="0" class="tbl tblgrid"
	id="tbldata">
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<thead>
		<tr>
			<th
				style="text-align: left; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('service_center');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('registered_calls');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('closed_calls');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('cancelled_calls');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('total_pending_calls');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('partpending_calls');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('otherpending_calls');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('calls_not_assigned');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('less_than_12hrs');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('between_12and24hrs');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('between_1and2');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('between_2and7');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('between_7and15');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('between_15and30');?></th>
			<th
				style="text-align: center; background: none repeat scroll 0 0 #EEEEEE; border-bottom: 1px solid #00689C; font-weight: bold; line-height: 100% !important; padding: 6px 3px; color: #555555;"><?php echo $this->lang->line('greater_than30');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	$data = json_decode($json);
	foreach($data as $row){
		$trstyle=$i%2==0?' style="text-align:center;background: none repeat scroll 0 0 #F2F9FC;"':' style="text-align:center;"';
		?>
		<tr <?php echo $trstyle;?>>
		<?php $j=1; foreach($row as $v){
			$tdstyle=($j==1)?' style="text-align:left;"':' style="text-align:center;"';
			if($i==count($data) && $j==1){
				$tdstyle=' style="text-align:left; font-weight:bold"';
			}else{
				if($i==count($data)){
					$tdstyle=' style="text-align:center; font-weight:bold"';
				}
			}
			?>
			<td <?php echo $tdstyle;?>><?php echo $v;?></td>
			<?php $j++; }?>
		</tr>
		<?php $i++;}?>
	</tbody>
</table>
