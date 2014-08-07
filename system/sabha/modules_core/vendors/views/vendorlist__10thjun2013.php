<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<table style="width: 100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="1%" /><col /><col width="5%" /><col width="20%" /><col width="1%" /><col width="1%" />
	<thead>
		<tr>
			<th>S.No.</th>
			<th>Vendors Name</th>
			<th>Phone</th>
			<th>Address</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach($lists as $list):{
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
			<td><?php echo $list->vendor_name ;?></td>
			<td><?php echo $list->vendor_phone ;?></td>
			<td><?php echo $list->vendor_address;?></td>
			<td><a class="btn" onclick="editvendor('<?php echo $list->vendor_id;?>')"><?php echo icon("edit","Edit","png");?></a></td>
			<td><a class="btn" onclick="deletevendor('<?php echo $list->vendor_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
		</tr>
		<?php
		$i++;
	} endforeach;
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
			<div class="pagination"><?php //echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>


