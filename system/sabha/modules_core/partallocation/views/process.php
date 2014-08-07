<?php
switch (trim($ajaxaction)){
	case 'getproductlist':
		displayProductList($products,$navigation,$page);
		break;
}
?>
<?php
function displayProductList($products,$navigation,$page){
	?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#pcurrentpage").val(this.id);
		showProductList();
	})
});
</script>
<table style="width: 100%" class="tblgrid" cellpadding="0"
	cellspacing="0">
	<col width="5%" />
	<col />
	<col width="15%" />
	<col width="10%" />
	<col width="1%" />
	<col width="1%" />
	<col width="1%" />
	<thead>
		<tr>
			<th>S.No.</th>
			<th>Product Name</th>
			<th style="text-align: center;">Description</th>
			<th>Brand</th>
			<th>&nbsp;</th>
			<th style="text-align: center;">Status</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($products as $product){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		$status=$product->product_status=="0"?icon('delete'):icon('tick');

		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i+$page['start'];?></td>
			<td><a
				href="<?php echo site_url();?>productmodel?p=<?php echo $product->product_id;?>"><?php echo $product->product_name;?></td>
			<td style="text-align: center;"><?php echo $product->product_desc;?></td>
			<td><?php echo $product->brand_name;?></td>
			<td><a style="cursor: pointer;"
				onclick="editProduct('<?php echo $product->product_id?>')"><?php echo icon('edit','Click to edit','png');?></a></td>
			<td style="text-align: center;"><a
				onclick="changeProductstatus('<?php echo $product->product_id;?>','<?php echo $product->product_status;?>');"
				class="btn"><?php echo $status;?></a></td>
			<td><a onclick="deleteProduct('<?php echo $product->product_id;?>');"
				class="btn"><?php echo icon('delete','Delete','png');?></a></td>
		</tr>
		<?php
		$i++;}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>
<?php }?>
