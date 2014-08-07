<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="1%" /><col /><col width="20%" /><col width="10%" /><col width="30%" /><col width="5%" /><col width="5%" />
	<thead>
    	<tr>
        	<th><?php echo $this->lang->line('sn');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('sc_name');?></th>
             <th><?php echo $this->lang->line('part_number');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('total_quantity');?></th>
              <th><?php  echo $this->lang->line('in_transit');?></th>
              <th style="text-align:center"><?php echo $this->lang->line('available_quantity');?></th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
	<?php
	$i=1;
	foreach($stocklist as $stock){
		
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
			<tr<?php echo $trstyle;?>>
            	<td><?php echo $i;?></td>
                
                <td style="text-align:center"><?php echo $stock->sc_name;?></td>
                <td><a href="<?php echo site_url();?>stocks/stockdetails/<?php echo $stock->sc_id?>/<?php echo $stock->part_number?>"><?php echo $stock->part_number?></a></td>
                <td style="text-align:center"><?php echo $stock->stock_quantity;?></td>
                 <td ><?php echo $stock->parts_in_transit;?></td>
                  <td style="text-align:center"><?php echo $stock->stock_quantity - $stock->parts_in_transit;?></td>
                <td></td>
            </tr>
	<?php $i++; }?>
    </tbody>
</table>