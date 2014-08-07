<div style="width: 600px;">

<table width="100%">
<col width="2%" /> <col width="6%" /><col width="12%" /><col width="8%" /><col width="8%" /><col width="8%" /><col width="8%" />
<thead>
<tr>
<th>S.N</th>
<th>Transit Number</th>
<th>Chalan Number</th>
<th>Courior Number</th>
<th>Vehicle Number</th>
<th>Boxes Number</th>
<th> </th>
</tr>
</thead>
<tbody>
<?php 
$i = 0;
foreach ($chalans as $chalan){
$trstyle=$i%2==0?" class='even' ": " class='odd' ";
$i = $i+1;?>
<tr> 
<td><?php echo $i;?> </td>
<td> <?php echo $chalan->transit_number;?></td>
<td> <?php echo $chalan->chalan_number;?></td>
<td><?php echo $chalan->courior_number;?> </td>
<td><?php echo $chalan->vehicle_number;?> </td>
<td><?php echo $chalan->box_number;?> </td>
<td><input type="button" id="chalandetail" value="Details" class="button"  onclick="chalandetail(<?php echo $chalan->transit_detail_id;?>);" /></td>

</tr>
	


<?php }?>

</tbody>
</table>

</div>