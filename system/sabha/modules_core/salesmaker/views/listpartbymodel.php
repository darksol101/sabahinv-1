<?php 

if(count($parts)>0){
	$prev=0;
	$attr_models = 'class="part"';	
	foreach($parts as $part){
		$selected = false;
			if(in_array($part->part_id,$part_id)){
			$selected = true;
		}
		if($part->part_id!=$prev){ if($prev>0){ echo '</div>';}?>
<div id="part_<?php echo $part->model_id;?>">

		<?php }?>
<div class="box_check"><?php echo form_checkbox('parts',$part->part_id,$selected,$attr_models);?><span
	class="chbx"><?php echo $part->part_number;?></span></div>
	
	<?php $prev =$part->part_id;}
}
?>