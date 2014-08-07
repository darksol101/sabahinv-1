<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<div style="width:600px"> 
<table style="width:100%;" class="tblgrid">
    	<col width="1%" />
        <col width="7%" />
        <col width="18%" />
         <col width="10%" />
       <col width="7%" />
    
            <thead>
            <tr>
                <th>S.No.</th>
                <th>Item Number</th>
                  <th>Item description</th>
                <th style="text-align:center">Part Quantity</th>
                <th></th>
            </tr>
            </thead>
        <tbody>
        
        <?php
		$i=1;		
		foreach($badpart_list as $order){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
	
            <tr <?php echo $trstyle;?>>
                <td><?php echo $i;?></td>
                <td ><?php echo $order->part_number;?></td>
                  <td ><?php echo $order->part_desc;?></td>
                <td style="text-align:center"><?php echo $order->part_quantity;?></td>
                <td><a class="setorderpart">Return</a></td>
            </tr>
        <?php $i++; } ?>
        </tbody>
        <tfoot>
		<tr> 
			<td colspan="6">
			<div class="pagination"><?php // echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
    </table>
    </div>