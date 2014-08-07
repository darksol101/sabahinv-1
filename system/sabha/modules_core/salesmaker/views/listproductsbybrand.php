<?php
if(count($products)>0){
	$prev=0;
	$brand_id=$products[0]->brand_id;
	foreach($products as $product){
		$attr_products = 'class="product" id="product_'.$product->product_id.'" onclick="getProductmodels(this.value);"';
		$selected = false;
		if(in_array($product->product_id,$product_id)){
			$selected = true;
		}

		if($product->brand_id!=$prev){ if($prev>0){ echo '</div>';}?>
<div id="product_<?php echo $product->brand_id;?>">
<div class="subhead" style=""><label><?php echo $product->brand_name;?></label></div>
		<?php }?>
<div class="box_check"><?php echo form_checkbox('products',$product->product_id,$selected,$attr_products);?><span
	class="chbx"><?php echo ucfirst(strtolower($product->product_name));?></span></div>
		<?php $prev =$product->brand_id;}
}?>