<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showPartList();
	})
});
</script>
<table style="width: 100%" class="tblgrid" cellpadding="0"
	cellspacing="0">
	<col width="1%" />
	<col width="20%" />
	<col width="20%" />
	<col width="10%" />
    <col width="10%" />
	<col width="10%" />
	<col width="1%" />
	<col width="1%" />
	<col width="1%" />
	<thead>
		<tr>
			<th style="text-align: center;">S.No.</th>
			<th><label><?php echo $this->lang->line('partnumber');?></label></th>
			<th style="text-align: center;"><label><?php echo $this->lang->line('description');?></label></th>
			<th style="text-align: center;"><label><?php echo $this->lang->line('landing_price');?></label></th>
            <th style="text-align: center;"><label><?php echo $this->lang->line('customer_price');?></label></th>
			<th style="text-align: center;"><label><?php echo $this->lang->line('service_center_price');?></label></th>
			<th></th>
			<th>&nbsp;</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($parts as $part){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i+$page['start'];?></td>
			<td><a><?php echo $part->part_number;?></a></td>
			<td style="text-align: center;"><?php echo $part->part_desc;?></td>
			<td style="text-align: center;"><?php echo $part->part_landing_price;?></td>
            <td style="text-align: center;"><?php echo $part->part_customer_price;?></td>
			<td style="text-align: center;"><?php echo $part->part_sc_price;?></td>
			<td style="text-align:center"><a class="btn" onclick="addtoproductmodel('<?php echo $part->part_id;?>','<?php echo $part->part_number;?>')"><?php echo icon('add','add','png')?></a></td>
			<td style="text-align: center;"><a class="info" style="cursor: pointer;" onclick="editPart('<?php echo $part->part_id?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align: center;"><a class="btn"	onclick="deletePart('<?php echo $part->part_id?>')"><?php echo icon("delete","Delete","png");?></a></td>
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
