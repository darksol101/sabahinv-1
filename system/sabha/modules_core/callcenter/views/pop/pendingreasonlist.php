<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		callreasonpendinglist('<?php echo $this->input->post('call_id');?>');
	});
});
</script>
<table cellpadding="0" cellspacing="0" class="tblgrid" width="100%">
	<col width="1%" /><col width="59%" /><col width="20%" /><col width="20%" /><col><col><col>
	<thead>
		<tr>
			<th><?php echo $this->lang->line('sn')?></th>
			<th><?php echo $this->lang->line('pending_reason');?></th>
			<th style="text-align:center"><?php echo $this->lang->line('service_center');?></th>
			<th style="text-align:center"><?php echo $this->lang->line('engineer');?></th>
			<th style="text-align:center"><?php echo $this->lang->line('engineer_remark');?></th>
			<th style="text-align: center;"><?php echo $this->lang->line('date_time');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('modified_by');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach($reason_pending_log as $row){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td style="text-align: center"><?php echo $i+$page['start'];?></td>
			<td><?php echo $row->call_reason_pending;?></td>
			<td style="text-align: center"><?php echo ($row->sc_name=='')?$this->lang->line('not_allocated'):$row->sc_name;?></td>
			<td style="text-align: center"><?php echo ($row->engineer_name=='')?$this->lang->line('not_assigned'):$row->engineer_name;?></td>
			<td style="text-align: center"><?php echo $row->call_engineer_remark;?></td>
			<td style="text-align: center"><?php echo date("Y-m-d h:i a",strtotime($row->log_date));?></td>
			<td style="text-align: center"><?php echo $row->username;?></td>
		</tr>
		<?php $i++;}?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4" style="text-align: center">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>
