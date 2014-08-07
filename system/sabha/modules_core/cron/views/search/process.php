<?php
switch (trim($ajaxaction)){
	case 'getsearchcitylist':
		displaySearchCityList($cities,$navigation,$page);
		break;
}
	
//for citylist
function displaySearchCityList($cities,$navigation,$page){
	?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showCityList();
	})
});
</script>
<table style="width: 100%" cellpadding="0" cellspacing="0"
	class="tblgrid">
	<col width="1%" />
	<col />
	<col width="15%" />
	<col width="15%" />
	<thead>
		<tr>
			<th>S.No.</th>
			<th>City</th>
			<th align="center">District</th>
			<th align="center">Zone</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($cities as $city){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i+$page['start'];?></td>
			<td><a class="btn"
				onclick="setCustomerCityInfo('<?php echo $city->zone_id;?>','<?php echo $city->district_id?>','<?php echo $city->city_id?>');"><?php echo $city->city_name;?></a></td>
			<td align="center"><?php echo $city->district_name; ?></td>
			<td><?php echo $city->zone_name;?></a></td>
		</tr>
		<?php
		$i++;
	}
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
<?php
}
?>


