<?php
if(count($models)>0){
	$prev=0;
	$product_id=$models[0]->product_id;
	$attr_models = 'class="model"  onclick="getModelparts(this.value);"';
	foreach($models as $model){
		$selected = false;
		if(in_array($model->model_id,$model_id)){
			$selected = true;
		}

		if($model->product_id!=$prev){ if($prev>0){ echo '</div>';}?>
<div id="model_<?php echo $model->product_id;?>">
<div class="subhead" style=""><label><?php echo $model->product_name;?></label></div>
		<?php }?>
<div class="box_check">
	<?php echo form_checkbox('models',$model->model_id,$selected,$attr_models.' id="submodel'.$model->model_id.'"');?><span
	class="chbx"><?php echo ucfirst(strtolower($model->model_number));?></span></div>
		<?php $prev =$model->product_id;}
}?>