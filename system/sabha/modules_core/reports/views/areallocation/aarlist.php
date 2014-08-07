<style>
<!--
#tbldata tbody tr td {
	border-right: #000 1px solid;
	border-bottom: #000 1px solid;
}
-->
</style>
<form onsubmit="return false" id="fname" name="fname" method="post">
<table width="100%" cellpadding="0" cellspacing="0" class="tbl">
	<col />
	<col />
	<col />
	<col />
	<thead>
		<tr class="tblboxhead">
			<th colspan="2"><?php echo sprintf($this->lang->line('area_allocation_report')) ?></th>
			<th style="text-align: right;" colspan="2">
			<div class="tool-icon"><a class="btn" onclick="export_exl();"
				title="Download as excel"><?php echo icon('excel-download','Download as excel','png')?></a><a
				class="btn" onclick="email_pop();" title="E-mail"><?php echo icon('mail','E-mail','png');?></a></div>
			</th>
		</tr>
	</thead>
</table>
<div class="tblbox">
<table width="100%" cellpadding="0" cellspacing="0" class="tbl tblgrid"
	id="tbldata" border="1">
	<col width="1%" />
	<col width="10%" />
	<col />
	<col />
	<col>
	<thead>
		<tr>
			<th style="text-align: left"><?php echo $this->lang->line('S.No.');?></th>
			<th style="text-align: left"><?php echo $this->lang->line('service_center')?></th>
			<th style="text-align: center"><?php echo $this->lang->line('zone')?></th>
			<th style="text-align: center"><?php echo $this->lang->line('district')?></th>
			<th style="text-align: left"><?php echo $this->lang->line('city')?></th>
			<th><?php echo $this->lang->line('products');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$prev_sc_id = 0;
	for($i=0,$total = count($reports); $i<$total; $i++) {
		$trstyle=$i%2==0?' class="even svc_'.$reports[$i]->sc_id.'" ': ' class="odd svc_'.$reports[$i]->sc_id.'" ';
		$product_name = $this->lang->line('na');
		$zone_name = $this->lang->line('na');
		$city = $this->lang->line('na');
		$district_name = $this->lang->line('na');
		$tdstyle='';
		if($reports[$i]->sc_id){
			if($reports[$i]->sc_id!=$prev_sc_id){
				$products = $this->mdl_productallocationreport->getProductAllocation($reports[$i]->sc_id);
				if(count($products)>0){
					$product_name = str_replace(",","<br />",$products->product_name);
				}
			}else{
				$product_name='';
				$tdstyle=$trstyle;
			}
		}
		$prev_sc_id = $reports[$i]->sc_id;
		if($reports[$i]->district_id>0){
			$cities = $this->mdl_areallocationreport->getCitiesByDistrict($reports[$i]->district_id);
			if(count($cities)>0){
				$city = $cities->city_name;
				$district_name = $cities->district_name;
				$zone_name = $cities->zone_name;
			}
		}
		?>
		<tr <?php echo $trstyle;?>>
			<td rowspan="1" style="text-align: left; vertical-align: top;border-left: #000 1px solid;"><?php echo $i+1; ?></td>
			<td rowspan="1" style="text-align: left; vertical-align: top;"><?php echo $reports[$i]->sc_name;?></td>
			<td rowspan="1" style="text-align: center; vertical-align: top;"><?php echo $zone_name;?></td>
			<td rowspan="1" style="text-align: center; vertical-align: top;"><?php echo $district_name;?></td>
			<td rowspan="1" style="text-align: left; vertical-align: top;"><?php echo $city; ?></td>
			<?php if($product_name!=''){?>
			<td id="svc_<?php echo $reports[$i]->sc_id;?>" rowspan=""
				style="text-align: left; vertical-align: top;"
			<?php echo $tdstyle;?>><?php echo $product_name?></td>
			<?php }?>
		</tr>
		<?php }?>
	</tbody>
</table>
</div>
<input type="hidden" name="json" id="json" value="" /> <input
	type="hidden" name="sendmail" id="sendmail" value="sendmail" /></form>
