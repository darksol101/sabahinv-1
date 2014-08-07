<?php 
switch (trim($ajaxaction)){
			case '':
			break;
			case 'getcitylist':{
				displayCityList($cities,$navigation,$page);
			break;}
			}
			
//for citylist
function displayCityList($cities,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showCityTable();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col width="15%" /><col width="15%" /><col width="1%" /><col width="5%" />
    	<thead>
        	<tr><th>S.No.</th><th>City</th><th>District</th><th>Zone</th><th>&nbsp;</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($cities as $city){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td><td><?php echo $city->city_name;?></td><td align="center"><?php echo $city->district_name; ?></td><td><?php echo $city->zone_name;?></a></td><td><a class="btn" onclick="showcity('<?php  echo $city->city_id; ?>')"><?php echo icon('edit','edit','png');?></a></td><td><a onclick="deletCity('<?php echo $city->city_id;?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
    <?php	
	$i++;
		}
?>
		</tbody>
        <tfoot><tr><td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table> 
<?php
}
//end city list
?>


