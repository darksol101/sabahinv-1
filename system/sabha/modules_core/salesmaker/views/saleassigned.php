<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style>
#facebox .footer {
	visibility: visible;
}
</style>
<script>
$(document).ready(function(){
	if($("#save_model_part").length==0){
		$(".footer").prepend('<input type="button" class="button" id="save_model_part" onclick="saveModelPart();" value="Save" />');
	}
	$("#product_box").html('');
	$("#model_box").html('');
});
</script>
<fieldset id="salesmaker">
	<legend><?php echo $sale_name; ?></legend>
<div style="padding-bottom:7px; font-size:12px;">


	<input type="hidden" name="maker_id" id="maker_id" value="<?php echo $maker_id;?>"/>

</div>
<div style="width:800px" id="accordion">

	<h3 id="brands_heading"><a href="#">Brands</a></h3>
	<div><?php $this->load->view('listbrands');?></div>
	
	<div style="clear:both;">
		
	</div>
	<h3 id="products_heading"><a href="#">Products</a></h3>
	<div>


	
	<div id="box_check" style="display: none">
	<input type="checkbox" onclick="selectAllProduct();" id="chkproduct" /> All</div>

	<div id="product_box"></div>
	</div>

	<div style="clear:both;">
		
	</div>

	<h3 id="models_heading"><a href="#">Models</a></h3>
	<div>
	<div id="box_check_model" style="display: none">
		<input type="checkbox" onclick="selectAllModel();" id="chkmodel" /> All</div>
	<div id="model_box">
		
	</div>
	</div>
		
	<div style="clear:both;">
		
	</div>
	<h3 id="part_heading"><a href="#">Items</a></h3>
	<div>
	<div id="box_check_part" style="display: none">
		<input type="checkbox" onclick="selectAllPart();" id="chkpart" /> All</div>
	<div id="part_box">
	</div>
	</div>
		
	<div style="clear:both;">
		
	</div>
</div>

<div id="result"></div>
<div style="clear:both"></div>
	</fieldset>