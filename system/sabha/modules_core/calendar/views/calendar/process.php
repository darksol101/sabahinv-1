<?php 
switch (trim($ajaxaction)){
	case 'getbrandlist':
		displayBrandList($brands,$navigation,$page);
		break;
}

function displayBrandList($brands,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#bcurrentpage").val(this.id);
		showBrandList();
	})
});
</script>
	<table style="width:100%" class="tblgrid" cellpadding="0" cellspacing="0">
    <col width="1%" /><col width="25%" /><col width="25%" /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th style="text-align:center;">S.No.</th><th>Brand Name</th><th style="text-align:center;">Description</th><th></th><th style="text-align:center;">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($brands as $brand){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$bstatus=$brand->brand_status=="0"?icon('delete'):icon('tick');
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td><td><a href="<?php echo site_url();?>products?b=<?php echo $brand->brand_id;?>"><?php echo $brand->brand_name;?></a></td><td style="text-align:center;"><?php echo $brand->brand_desc;?></td><td><a class="info" style="cursor:pointer;" onclick="editBrand('<?php echo $brand->brand_id?>')" ><?php echo icon('edit','edit','png');?></a></td><td align="center" style="text-align:center;"><a class="btn" onclick="changestatus('<?php echo $brand->brand_id;?>','<?php echo $brand->brand_status;?>');"><?php echo $bstatus;?></a></td><td><a class="btn" onclick="deleteBrand('<?php echo $brand->brand_id?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
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