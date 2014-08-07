<?php 
switch (trim($ajaxaction)){
	case 'getcategorylist':
		displayCategoryList($categories,$navigation,$page);
		break;
}

function displayCategoryList($categories,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#ccurrentpage").val(this.id);
		showCategoryList();
	})
});
</script>
	<table style="width:100%" class="tblgrid" cellpadding="0" cellspacing="0"><col width="1%" /><col width="25%" /><col width="25%" /><col width="1%" /><col width="1%" /><col width="1%" /><thead><tr><th style="text-align:center;">S.No.</th><th>Category Name</th><th style="text-align:center;">Description</th><th style="text-align:center;">Status</th><th>&nbsp;</th><th>&nbsp;</th></tr></thead><tbody>
<?php
	$i=1;
	foreach ($categories as $category){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$cstatus=$category->prod_category_status=="0"?icon('delete'):icon('tick');
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td><td><?php echo $category->prod_category_name;?></td><td style="text-align:center;"><?php echo $category->prod_category_desc;?></td><td><a class="btn" onclick="editCategory('<?php echo $category->prod_category_id?>')" ><?php echo icon('edit','Edit','png');?></a></td><td align="center" style="text-align:center;"><a class="btn" onclick="changestatus('<?php echo $category->prod_category_id;?>',<?php echo $category->prod_category_status;?>);"><?php echo $cstatus;?></a></td><td><a class="btn" onclick="deleteCategory('<?php echo $category->prod_category_id?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
    <?php	
	$i++;
		}?>		
        </tbody><tfoot><tr><td align="center" colspan="5"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot></table><?php	}?>