<?php 
switch (trim($ajaxaction)){
	case 'getpartlist':
		displayPartList($parts,$navigation,$page);
		break;
}

function displayPartList($parts,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showPartList();
	})
});
</script>
	<table style="width:100%" class="tblgrid" cellpadding="0" cellspacing="0">
    <col width="1%" /><col width="25%" /><col width="25%" /><col width="1%" /><col width="1%" /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th style="text-align:center;">S.No.</th><th>Item Number / Name</th><th style="text-align:center;">Description</th><th>Model</th><th style="text-align:center;">Product</th><th style="text-align:center;">Brand</th><th></th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($parts as $part){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td><td><a ><?php echo $part->part_number_name;?></a></td><td style="text-align:center;"><?php echo $part->part_desc;?></td><td><?php echo $part->model_number;?></td><td align="center" style="text-align:center;"><?php echo $part->product_name;?></td><td align="center"><?php echo $part->brand_name;?></td><td><a class="info" style="cursor:pointer;" onclick="editPart('<?php echo $part->part_id?>')" ><?php echo icon('edit','edit','png');?></a></td><td><a class="btn" onclick="deletePart('<?php echo $part->part_id?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
    <?php	
	$i++;
		}
?>
		</tbody>
        <tfoot><tr><td align="center" colspan="5"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table>
<?php	
	}
?>