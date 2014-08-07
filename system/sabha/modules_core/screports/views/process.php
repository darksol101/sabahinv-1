<?php 
switch (trim($ajaxaction)){
			case '':
			break;
			case 'getscreportslist':{
				displayscreportsList($sc_name,$sc_total,$sc_open,$sc_pending,$sc_partpending,$sc_closed,$sc_currentpending,$sc_avgpartpending,$sc_avgclosing,$sc_longclosure,$sc_less,$sc_between,$sc_greater);				
			break;
			}
	}
			
//for screports

function displayscreportsList($sc_name,$sc_total,$sc_open,$sc_pending,$sc_partpending,$sc_closed,$sc_currentpending,$sc_avgpartpending,$sc_avgclosing,$sc_longclosure,$sc_less,$sc_between,$sc_greater){

?>		
<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid" >

<col width="1%" /><col width="5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" /><col width="1.5%" />
   <thead>
   	<tr>
		<th><label>S.N.</label></th>
		<th><label>Store</label></th>	
		<th><label>Total Call Registered</label></th>
		<th><label>Number of Open Calls</label></th>
		<th><label>Number of Pending Calls</label></th>
		<th><label>Number of Part Pending Calls</label></th>
		<th><label>Number of Closed Calls</label></th>
		<th><label>Average Aging Time for Current Pending Calls</label></th>
		<th><label>Average Aging Time for Part Pending Calls</label></th>
		<th><label>Average Closing Time</label></th>
		<th><label>Long Closure Calls</label></th>
		<th><label>Closed calls < 12 hours</label></th>
		<th><label>Closed Calls < 12 and >24 hours</label></th>
		<th><label>Closed Calls > 24 hours</label></th>	
	</tr>
	</thead>
    <tbody>

	<?php for ($i=0;$i<count($sc_name);$i++) {
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?>
	<tr <?php echo $trstyle;?>>
		<td style="text-align:center;"><label><?php echo $i+1;?></label></td>		
		<td><label><?php echo $sc_name[$i];?></label></td>
		<td style="text-align:center;"><label><?php echo $sc_total[$i];?></label></td>
		<td style="text-align:center;"><label><?php echo $sc_open[$i];?></label></td>
		<td style="text-align:center;"><label><?php echo $sc_pending[$i];?></label></td>
		<td style="text-align:center;"><label><?php echo $sc_partpending[$i];?></label></td>
		<td style="text-align:center;"><label><?php echo $sc_closed[$i];?></label></td>
		<td style="text-align:center;"><label><?php if($sc_currentpending[$i]==NULL) echo "N/A";  else{?> <?php echo $sc_currentpending[$i];}?></label></td>
		<td style="text-align:center;"><label><?php if($sc_avgpartpending[$i]==NULL) echo "N/A";  else{?><?php echo $sc_avgpartpending[$i];}?></label></td>
		<td style="text-align:center;"><label><?php if($sc_avgclosing[$i]==NULL) echo "N/A";  else{?><?php echo $sc_avgclosing[$i];}?></label></td>
		<td style="text-align:center;"><label><?php if($sc_longclosure[$i]==NULL) echo "N/A";  else{?><?php echo $sc_longclosure[$i]; }?></label></td>
		<td style="text-align:center;"><label><?php echo $sc_less[$i];?></label></td>
		<td style="text-align:center;"><label><?php echo $sc_between[$i];?></label></td>
		<td style="text-align:center;"><label><?php echo $sc_greater[$i];?></label></td>
	</tr>
	<?php } ?>
	     </tbody>
</table>

<?php }?>
