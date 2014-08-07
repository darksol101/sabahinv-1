


<table style="width: 100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="5%" />
	<col />
	<col width="5%" />
	<col width="20%" />
	<col width="1%" />
	<col width="1%" />
	<thead>
		<tr>
			
			<th>Item Number</th>
			<th>Quantity</th>
			
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
			<td><?php echo $list->part_number ;?></td>
			<td><?php echo $list->quantity ;?></td>
			
			
			
			<td><a class="btn"
				onclick="deletepart('<?php echo $list->vendor_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
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



