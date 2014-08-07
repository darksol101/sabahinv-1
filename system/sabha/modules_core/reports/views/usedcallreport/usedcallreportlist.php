<style>
.first { background:#CCC}
.second { background:}
</style>

<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showusedcalllist();
	})
});
</script>
<div style="float:right; margin-top:-16px;"><input type="button" value="<?php echo $this->lang->line('download');?>" class="button" onclick="downloadUsedCallReport();" title="Excel Download" /> </div>
<div style="margin-top:20px;">&nbsp;</div>
<div>
<form onsubmit="return false" name="fname" id="fname" method="post">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">

	<thead>
		<tr>
			<th align="left"><?php echo($this->lang->line('part_used_call_report'));?></th>
            <th align="center"><?php echo (sprintf("Reports from %s  to %s",$fromdate,$todate));?></th>
		</tr>
	</thead>
</table>
</div>
<table id="tbl_used_call_report" class="tblgrid" border="0" width="100%" cellpadding="0" cellspacing="0">
<col width="6%" />
<col width="15%" />
<col width="15%" />
<col width="20%" />
<col width="10%" />
<col width="15%" />
<col width="15%" />
<thead>
<tr>
<th>S.N</th>
<th>Call Id</th>
<th><?php echo $this->lang->line('part_number');?></th>
<th><?php echo $this->lang->line('part_desc');?></th>
<th><?php echo $this->lang->line('quantity');?></th>
<th><?php echo $this->lang->line('engineer_name');?></th>
<th><?php echo $this->lang->line('date');?></th>
</tr>
</thead>
<?php
$j=1;
foreach ($results as $result) {
	$trclass=($j%2==0)?" class='even' ": " class='odd' "; ?>
<tbody>
<tr <?php echo $trclass;?>>
<td><?php echo $j; ?></td>
<td><?php echo $result->call_uid; ?></td>
<td> <?php echo $result->part_number;?> </td>
<td> <?php echo $result->part_desc;?> </td>
<td><?php echo $result->part_quantity;?></td>
<td> <?php echo $result->engineer_name; ?></td>
<td> <?php echo $result->created_date;?> </td>
<?php $j++; } ?>
<tr>
</tbody>

</table>
</form>
