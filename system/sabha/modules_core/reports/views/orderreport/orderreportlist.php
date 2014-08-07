




<table id="order_report" class="tblgrid" border="0" width="100%" cellpadding="0" cellspacing="0">
		<col width="6%" />
		<col width="10%" />
		<col width="13%" />
		<col width="12%" />
		<col width="26%" />
		<col width="11%" />
		<col width="9%" />
<thead>
	<tr>
		<th>S.N</th>
		<th><?php echo $this->lang->line('to_svc');?></th>
		<th><?php echo $this->lang->line('order_no');?></th>
		<th><?php echo $this->lang->line('part_number');?></th>
		<th><?php echo $this->lang->line('part_desc');?></th>
		<th><?php echo $this->lang->line('dispatched_quantity');?></th>
		<th><?php echo $this->lang->line('received_quantity');?></th>
	</tr>
</thead>
<tbody>
<?php
$j=1;
foreach ($results as $result) {
	$trclass=($j%2==0)?" class='even' ": " class='odd' "; ?>

	<tr <?php echo $trclass;?>>
		<td><?php echo $j; ?></td>
		<td><?php echo $result->sc_name; ?></td>
		<td> <?php echo $result->order_number;?> </td>
		<td> <?php echo $result->part_number;?> </td>
		<td><?php echo $result->part_desc;?></td>
		<td> <?php echo $result->part_quantity + $result->differance; ?></td>
		<td> <?php echo $result->part_quantity;?> </td>

	</tr>
<?php $j++; } ?>
</tbody>

</table>
</form>
