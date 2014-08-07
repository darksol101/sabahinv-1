<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="5%" />
    <col width="30%"  />
    <col width="25%"  />
    <col width="20%"  />
    <col  />
 	<thead>
    	<th>S.No </th>
        <th>Item Number </th>
        <th>Quantity </th>
        <th>Reason</th>
     </thead>
    <tbody>
	<?php
	
	$i=0;
	foreach($results as $result){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
			<tr<?php echo $trstyle;?>>
            	<td> <?php echo $i+1;?> </td>
                <td><?php echo $result->part_number;?></td>
                <td><?php echo $result->quantity;?> </td>
                <td> <?php echo $this->mdl_mcb_data->getStatusDetails($result->reason,'badpart_reason');?></td>
            </tr>
	<?php $i++; }?>
    </tbody>
 </table>