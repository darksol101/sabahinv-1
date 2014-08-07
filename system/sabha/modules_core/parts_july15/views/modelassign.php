<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style>
#facebox .footer {
	visibility: visible;
}
</style>
<script>
$(document).ready(function(){
	$( "#accordion" ).accordion({
		autoHeight: false,
		collapsible: true							
	});
	if($("#save_model_part").length==0){
		$(".footer").prepend('<input type="button" class="button" id="save_model_part" onclick="saveModelPart();" value="Save" />');
	}
	$("#product_box").html('');
	$("#model_box").html('');
});
</script>

<div style="padding-bottom:7px; font-size:12px;">
	<label><?php echo $this->lang->line('partnumber');?> : <?php echo $part_number;?>[<?php echo $part_id;?>]
	</label>
	<input type="hidden" name="hdnpart_number" id="hdnpart_number" value="<?php echo $part_number;?>"/>
	<input type="hidden" name="hdnpart_number" id="hdnpart_id" value="<?php echo $part_id;?>"/>
	
</div>
<div style="width:800px" id="accordion">

	<h3 id="brands_heading"><a href="#">Brands</a></h3>
	<div><?php $this->load->view('listbrands');?></div>
	
	<h3 id="products_heading"><a href="#">Products</a></h3>
	<div>


	
	<div id="box_check" style="display: none">
	<input type="checkbox" onclick="selectAllProduct();" id="chkproduct" /> All</div>

	<div id="product_box"></div>
	</div>
	<h3 id="models_heading"><a href="#">Models</a></h3>
	<div>
	<div id="box_check_model" style="display: none">
		<input type="checkbox" onclick="selectAllModel();" id="chkmodel" /> All</div>
	<div id="model_box"></div>
	</div>
</div>
<div id="result"></div>
<div style="clear:both"></div>