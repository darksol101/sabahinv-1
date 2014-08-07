<?php 
switch (trim($ajaxaction)){
			case '':
			break;
			case 'getengineerlist':{
				displayEngineerList($engineers,$navigation,$page);
			break;}
			
			}
function displayEngineerList($engineers,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showEngineerTable();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="5%" /><col /><col width="20%" /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Engineer's Name</th><th>Store</th><th></th><th style="text-align:center;">Status</th><th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($engineers as $engineer){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$status=$engineer->engineer_status=="1"?icon("tick","Active","png"):icon("cross","Inactive","png");
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td><td><?php echo $engineer->engineer_name;?></td><td><?php echo $engineer->sc_name;?></td><td><a class="btn" onclick="showengineer('<?php echo $engineer->engineer_id;?>')"><?php echo icon("edit","Edit","png");?></a></td><td style="text-align:center;" ><a class="btn" onclick="changeEstatus('<?php echo $engineer->engineer_id;?>','<?php echo $engineer->engineer_status;?>');"><?php echo $status;?></a></td><td><a class="btn" onclick="deletEngineer('<?php echo $engineer->engineer_id?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
    <?php	
	$i++;
		}
?>
		</tbody>
        <tfoot><tr><td colspan="5"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table> 
<?php
}
?>


