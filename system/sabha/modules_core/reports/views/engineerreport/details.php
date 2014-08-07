<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<form onsubmit="return false">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col width="10%" /><col /><col width="20%"><col width="20%" /><col width="7%" />
	<thead>
    	<tr>
        	<th><?php echo $this->lang->line('sn');?></th>
            <th><?php echo $this->lang->line('brand_name');?></th>
            <th><?php echo $this->lang->line('product_name');?></th>
             <th><?php echo $this->lang->line('model_number');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('total');?></th>
            
             
        </tr>
    </thead>
    <tbody>
	<?php
	$total =0;
	$i =1;
	foreach($verifieddetails as $verifieddetail){
			$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr<?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
            <td><?php echo $verifieddetail->brand_name;?></td>
			<td> <?php echo $verifieddetail->product_name;?></td>
             <td><?php echo $verifieddetail->model_number;?></td>
			<td style="text-align:center"><?php echo $verifieddetail->counter;?></td>
           <?php $total = $total+ $verifieddetail->counter;?>
		</tr>
	<?php $i++;}?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="4" align="right" style="text-align:right!important"><strong><?php echo $this->lang->line('grand_total');?></strong></td>
            <td><strong><?php echo $total;?></strong></td>
           
        </tr>
       
    </tfoot>
</table>
</form>