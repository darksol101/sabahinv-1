<?php 
switch (trim($ajaxaction)){
			case 'getcallslist':
				displaycallsList($list);				
			break;
	}
			
//for screports

function displaycallsList($list){

?>		
<table style="width:40%" cellpadding="0" cellspacing="0" class="tblgrid" >

<col width="1%" /><col width="1%" /><col />
   <thead>
   	<tr>
		<th><label>S.N.</label></th>
        <th><input type="checkbox" name="chk" id="chk" onclick="checkAll();" checked="checked" /></th>
		<th><label>Call ID</label></th>
	</tr>
	</thead>
    <tbody>
	<?php
	$i=1;
	foreach ($list as $call) {
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?>
	<tr <?php echo $trstyle;?>>
		<td style="text-align:center;"><label><?php echo $i;?></label></td>		
		<td><input type="checkbox" id="chkbx_<?php echo $i?>" name="chkbx[]" class="chkb" checked="checked" value="<?php echo $call->call_id;?>"/></td>
        <td><label><?php echo $call->call_uid;?></label></td>
	</tr>
	<?php $i++; } ?>
	     </tbody>
</table>

<?php }?>
