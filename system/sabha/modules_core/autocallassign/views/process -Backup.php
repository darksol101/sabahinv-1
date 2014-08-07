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
function displayDistricts($districts,$district_by_sc){?>
<?php if(count($districts)>0){
	$all_selected = (count($districts)==count($district_by_sc))?' checked="checked"':'';	
	?><span><input type="checkbox" onclick="selectAllDistrict();" id="chkdistrict"<?php echo $all_selected;?> /> All</span><?php }?>
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
			//$attr = 'class="district dist_'.$district->zone_id.'" onclick="getCities();"';
			$attr = 'class="district dist_'.$district->zone_id.'" onclick="getCities();"';
			if($district->zone_id!=$prev){ echo '<div class="subhead" style=""><label>'.$district->zone_name.'</label></div>';}
			?>
			<div class="box_check"><?php echo form_checkbox('districts', $district->district_id, $selected,$attr);?><span class="chbx"><?php echo $district->district_name;?></span></div>
			<?php
			$prev =$district->zone_id; 
		}
	}else{ echo 'No district found';}
}?>

<?php
function displayCities($cities,$cities_by_sc){?>
<?php if(count($cities)>0){?><div><input type="checkbox" onclick="selectAllCity();" id="chkcity"  /> All<?php }?></div>
<?php $attr = 'class="city"';
if(count($cities)>0){?>
	<?php
	$prev=0;
	foreach($cities as $city){
		$selected = false;
		$arr = array();
		foreach($cities_by_sc as $row){
			$arr[]=$row->city_id;
		}
		if(in_array($city->city_id,$arr)){
			$selected = true;
		}
		if($city->district_id!=$prev){ echo '<div class="subhead" style=""><label>'.$city->district_name.'</label></div>';}
		?>
		<div class="box_check"><?php echo form_checkbox('cities', $city->city_id, $selected,$attr);?><span class="chbx"><?php echo $city->city_name;?></span></div>
	<?php $prev =$city->district_id;  }?>
    <?php }else{ echo 'No city found';}?>
<?php }?>
<?php
function displayProducts($products,$products_by_sc){?>
<?php if(count($products)>0){
	$all_selected = (count($products)==count($products_by_sc))?' checked="checked"':'';
	?><span><input type="checkbox" onclick="selectAllProduct();" id="chkproduct"<?php echo $all_selected;?> /> All</span><?php }?>
<?php
if(count($products)>0){
//$attr = 'class="product" onclick="getProductModels();"';
$attr = 'class="product"';
?>
	<?php
	$prev=0;
	foreach($products as $product){
		$selected = false;
		$arr = array();
		foreach($products_by_sc as $row){
			$arr[]=$row->product_id;
		}
		if(in_array($product->product_id,$arr)){
			$selected = true;
		}
		if($product->brand_id!=$prev){ echo '<div class="subhead" style=""><label>'.$product->brand_name.'</label></div>';}
		?>
		<div class="box_check"><?php echo form_checkbox('products', $product->product_id, $selected,$attr);?><span class="chbx"><?php echo ucfirst(strtolower($product->product_name));?></span></div>
	<?php $prev =$product->brand_id;  }?>
    <?php }else{ echo 'No products found';}?>
</fieldset>
<?php }?>
<?php
function displayProductModels($productmodels,$productmodels_by_sc){?>
<?php if(count($productmodels)>0){?><span><input type="checkbox" onclick="selectAllModels();" id="chkproductmodel" /> All</span><?php }?>
<?php
if(count($productmodels)>0){
$attr = 'class="productmodel"';
?>
	<?php
	$prev=0;
	foreach($productmodels as $productmodel){
		$selected = false;
		$arr = array();
		foreach($productmodels_by_sc as $row){
			$arr[]=$row->model_id;
		}
		if(in_array($productmodel->model_id,$arr)){
			$selected = true;
		}
		if($productmodel->product_id!=$prev){ echo '<div class="subhead" style=""><label>'.$productmodel->product_name.'</label></div>';}
		?>
		<div class="box_check"><?php echo form_checkbox('productmodels', $productmodel->model_id, $selected,$attr);?><span class="chbx"><?php echo ucfirst(strtolower($productmodel->model_number));?></span></div>
	<?php  $prev =$productmodel->product_id;  }?>
    <?php }else{ echo 'No models found';}?>
<?php }?>
<?php
function displayZones($zones,$zones_by_sc){?>
<div>
<?php
$all_selected = (count($zones)==count($zones_by_sc))?' checked="checked"':'';
?>
<input type="checkbox" onclick="selectAllZone();" id="chkzone"<?php echo $all_selected;?> />All</div>
        <?php
	$attr = 'class="zone" onclick="getDistricts();"';
	foreach($zones as $zone){
		$selected = (in_array($zone->value,$zones_by_sc))?true:false;
		?>
        <div class="box_check"><?php echo form_checkbox('zones', $zone->value, $selected,$attr);?> <span class="chbx"><?php echo $zone->text;?></span></div>
        <?php }	?>
<?php }?>
<?php
function displayBrands($brands,$brands_by_sc){?>
<div>
<?php
$all_selected = (count($brands)==count($brands_by_sc))?' checked="checked"':'';
?>
        <input type="checkbox" onclick="selectAllBrand();" id="chkbrand"<?php echo $all_selected;?> />
        All</div>
        <?php
            $attr_brands = 'class="brand" onclick="getProducts();"';
			foreach($brands as $brand){
				$selected = (in_array($brand->value,$brands_by_sc))?true:false;
				?>
        <div class="box_check"><?php echo form_checkbox('brands',$brand->value,$selected,$attr_brands);?><span class="chbx"><?php echo ucfirst(strtolower($brand->text));?></span></div>
        <?php }?>
<?php }?>