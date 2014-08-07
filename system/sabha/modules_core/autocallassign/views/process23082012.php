<?php 
switch (trim($ajaxaction)){
	case 'getdistricts':
		displayDistricts($districts,$district_by_sc);
		break;
	case 'getcities':
		displayCities($cities,$cities_by_sc);
		break;
	case 'getproducts':
		displayProducts($products,$products_by_sc);
		break;
	case 'getproductmodels':
		displayProductModels($productmodels,$productmodels_by_sc);
		break;
	case 'getzones';
		displayZones($zones,$zones_by_sc);
		break;
	case 'getbrands';
		displayBrands($brands,$brands_by_sc);
		break;	
}
?>
<?php
function displayZones($zones,$zones_by_sc){?>
<div>
<?php $all_selected = (count($zones)==count($zones_by_sc))?' checked="checked"':'';?>
<input type="checkbox" onclick="selectAllZone();" id="chkzone"<?php echo $all_selected;?> />All</div>
        <?php
	foreach($zones as $zone){
		$attr = 'class="zone" onclick="getDistricts(this.value);" id="zone_'.$zone->value.'"';
		$selected = (in_array($zone->value,$zones_by_sc))?true:false;
		?>
        <div class="box_check"><?php echo form_checkbox('zones', $zone->value, $selected,$attr);?> <span class="chbx"><?php echo $zone->text;?></span></div>
        <?php }	?>
<?php }?>
<?php
function displayDistricts($districts,$district_by_sc){?>
<?php if(count($districts)>0){
	$all_selected = (count($districts)==count($district_by_sc))?' checked="checked"':'';	
	?><?php }?>
<?php 
	if(count($districts)>0){
		$prev=0;
		foreach($districts as $district){
			$selected = false;
			$arr = array();
			foreach($district_by_sc as $row){
				$arr[]=$row->district_id;
			}
			if(in_array($district->district_id,$arr)){
				$selected = true;
			}
			$attr = 'class="district" onclick="getCities(this.value);" id="district_'.$district->district_id.'"';
			if($district->zone_id!=$prev){
				if($prev>0){ echo '</div>';}
				echo '<div id="zonebox_'.$district->zone_id.'">';
				echo '<div class="subhead"><label>'.$district->zone_name.'</label></div>';}
			?>
			<div class="box_check"><?php echo form_checkbox('districts', $district->district_id, $selected,$attr);?><span class="chbx"><?php echo $district->district_name;?></span></div>
			<?php
			$prev =$district->zone_id; 
		}
	}else{ echo 'No district found';}
}?>

<?php
function displayCities($cities,$cities_by_sc){?>
<?php $attr = 'class="city"';
if(count($cities)>0){?>
	<?php
	$prev=0;
	foreach($cities as $city){
		$selected = false;
		$selected =  $city->selected;
		if($city->district_id!=$prev){
			if($prev>0){ echo '</div>';}
				echo '<div id="districtbox_'.$city->district_id.'">';
				echo '<div class="subhead" style=""><label>'.$city->district_name.'</label></div>';}
		?>
		<div class="box_check"><?php echo form_checkbox('cities', $city->city_id, $selected,$attr);?><span class="chbx"><?php echo $city->city_name;?></span></div>
	<?php $prev =$city->district_id;  }?>
    <?php }else{ echo 'No city found';}?>
<?php }?>
<?php
function displayProducts($products,$products_by_sc){
if(count($products)>0){
	$prev=0;
	foreach($products as $product){
		$attr = 'class="product" id="product_'.$product->product_id.'"';
		$selected = false;
		$arr = array();
		foreach($products_by_sc as $row){
			$arr[]=$row->product_id;
		}
		if(in_array($product->product_id,$arr)){
			$selected = true;
		}
		if($product->brand_id!=$prev){
			if($prev>0){ echo '</div>';}
			echo '<div id="brandbox_'.$product->brand_id.'">';
			echo '<div class="subhead" style=""><label>'.$product->brand_name.'</label></div>';
		}
		?>
		<div class="box_check"><?php echo form_checkbox('products', $product->product_id, $selected,$attr);?><span class="chbx"><?php echo ucfirst(strtolower($product->product_name));?></span></div>
	<?php $prev =$product->brand_id;  }?>
    <?php }?>
<?php }?>
<?php
function displayBrands($brands,$brands_by_sc){?>
        <?php
			foreach($brands as $brand){
				$attr_brands = 'class="brand" onclick="getProducts(this.value);" id="brand_'.$brand->value.'"';
				$selected = (in_array($brand->value,$brands_by_sc))?true:false;
				?>
        <div class="box_check"><?php echo form_checkbox('brands',$brand->value,$selected,$attr_brands);?><span class="chbx"><?php echo ucfirst(strtolower($brand->text));?></span></div>
        <?php }?>
<?php }?>