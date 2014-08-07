<?php 
switch (trim($ajaxaction)){
			case 'getservicecenterlist':
				displayServiceCenterList($service);
			break;
			}

function displayServiceCenterList($servicecenters){
?>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col width="25%" /><col width="25%" /><col width="15%" /><col width="5%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th style="text-align:center;">S.No.</th><th>Store Name</th><th>Store Code</th><th>Address</th><th style="text-align:center;">Email</th><th style="text-align:center;">Fax</th><th>&nbsp;</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($servicecenters as $servicecenter){

	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$trstyle = '';
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i;?></td><td><?php echo $servicecenter->sc_name;?></td><td><?php echo $servicecenter->sc_code;?></td><td><?php echo $servicecenter->sc_address;?></td><td  style="text-align:center;"><?php echo $servicecenter->sc_email;?></td><td  style="text-align:center;"><?php echo $servicecenter->sc_fax;?></td><td><a class="btn" onclick="editServiceCenter('<?php echo $servicecenter->sc_id?>')" ><?php echo icon('edit','Edit','png');?></a></td><td><a style="cursor:pointer;" onclick="deleteServiceCenter('<?php echo $servicecenter->sc_id?>')"><?php echo icon('delete','Delete','png');?></a></td></tr>
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