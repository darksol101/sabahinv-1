<?php 
switch (trim($ajaxaction)){
			case 'getcallist':
				displayCallList($calls);
				break;
			}
function displayCallList($calls){
?>
	<table style="width:100%" class="tablelist">
    <col width="1%" /><col width="25%" /><col width="25%" /><col width="15%" /><col width="5%" /><col width="5%" />
    	<thead>
        	<tr><th style="text-align:center;">S.No.</th><th>Brand Name</th><th style="text-align:center;">Company Name</th><th style="text-align:center;">OwnerShip</th><th align="center">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($calls as $brand){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$trstyle = '';
	$bstatus=$brand->call_status=="0"?icon('delete'):icon('tick');
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i;?></td><td><a style="cursor:pointer;" onclick="editCall('<?php echo $brand->call_id?>')" ><?php echo $brand->brand_name;?></a></td><td style="text-align:center;"><?php echo $brand->company_name;?></td><td style="text-align:center;"><?php echo $brand->ownership;?></td><td align="center" style="text-align:center;"><a style="cursor:pointer;" onclick="changestatus('<?php echo $brand->call_id;?>',<?php echo $brand->call_status;?>);"><?php echo $bstatus;?></a></td><td><a style="cursor:pointer;" onclick="deleteBrand('<?php echo $brand->call_id?>')"><img src="<?php echo base_url();?>assets/style/img/icons/delete.png" class="iconbtn"></a></td></tr>
    <?php	
	$i++;
		}
?>
		</tbody>
        <tfoot><tr><td align="center" colspan="6"></td></tr></tfoot>
    </table>
<?php	
	}
?>