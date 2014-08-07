<div>
	<input type="checkbox" onclick="selectAllBrands();" id="chkbrand"
<?php ?> /> All</div>
<?php
$attr_brands = 'class="brand" onclick="getProducts(this.value);"';
foreach($brands as $brand){
	$selected = false;
?>

<div class="box_check">
<?php echo form_checkbox('brands',$brand->value,$selected,$attr_brands.' id="brand_'.$brand->value.'"');?>

<span class="chbx">
	<?php echo ucfirst(strtolower($brand->text));?></span>
</div>

<?php }?>
