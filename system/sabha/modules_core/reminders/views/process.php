<?php
switch (trim($ajaxaction)){
	case 'getreminderlist':
		displayReminderList($reminders,$navigation,$page);
		break;
}

function displayReminderList($reminders,$navigation,$page){
	if(count($reminders)>0){
		?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showReminderList();
	})
});
</script>
<table style="width: 100%" class="tblgrid" cellpadding="0"
	cellspacing="0">
	<col width="1%" />
	<col width="25%" />
	<col width="25%" />
	<col width="10%" />
	<col width="1%" />
	<col width="1%" />
	<thead>
		<tr>
			<th style="text-align: center;">S.No.</th>
			<th style="text-align: left;">Date</th>
			<th style="text-align: center">Reminder Remark</th>
			<th style="text-align: center;">Created By</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($reminders as $reminder){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i+$page['start'];?></td>
			<td style="text-align: left;"><?php echo $reminder->reminder_dt;?></td>
			<td style="text-align: center"><?php echo $reminder->reminder_remarks;?></td>
			<td style="text-align: center"><?php echo $reminder->username;?></td>
			<td><a onclick="editReminder('<?php echo $reminder->reminder_id?>');"
				class="info" style="cursor: pointer;"><?php echo icon('edit','edit','png');?></a></td>
			<td><a class="btn"
				onclick="deleteReminder('<?php echo $reminder->reminder_id?>')"><?php echo icon("delete","Delete","png");?></a></td>
		</tr>
		<?php
		$i++;
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td align="center" colspan="5">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>
<?php
	}else{
		echo '<label>No reminders has been added yet</label>';
	}
	}
?>