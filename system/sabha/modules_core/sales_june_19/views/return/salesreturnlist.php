<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<table width="100%" class="tblgrid">
    <thead>
        <tr>
            <th><?php echo $this->lang->line('sn');?></th>
            <th><?php echo $this->lang->line('sales_return_number');?></th>
            <th><?php echo $this->lang->line('bill_number');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('sales_return_date');?></th>
            <th style="text-align:right"><?php echo $this->lang->line('total_price');?></th>
            <th><?php echo $this->lang->line('service_center');?></th>
            <th></th>
        </tr>
    </thead>
<tbody>
<?php
$i=1;
foreach($salesreturns as $salesreturn){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?>
    <tr<?php echo $trstyle;?>>
        <td><?php echo $i;?></td>
        <td><a href="<?php echo (($salesreturn->sales_return_status==0)?site_url('sales/salesreturn/edit/'.$salesreturn->sales_return_id):site_url('sales/salesreturn/view/'.$salesreturn->sales_return_id));?>"><?php echo ($salesreturn->sales_return_number)?$salesreturn->sc_code.'SR/'.$salesreturn->sales_return_number:'';?></a></td>
        <td><?php echo $salesreturn->sc_code.($salesreturn->bill_type==1?'SI':'TI').'/'.$salesreturn->bill_number;?></td>
        <td style="text-align:center"><?php echo $salesreturn->sales_return_date;?></td>
        <td style="text-align:right"><?php echo $salesreturn->sales_return_rounded_grand_total_price;?></td>
        <td><?php echo $salesreturn->sc_name;?></td>
        <td><a href="<?php echo (($salesreturn->sales_return_status==0)?site_url('sales/salesreturn/edit/'.$salesreturn->sales_return_id):site_url('sales/salesreturn/view/'.$salesreturn->sales_return_id));?>"><?php echo ($salesreturn->sales_return_status==0)?icon('edit','Edit','png'):icon('view','View','png')?></a></td>
    </tr>
<?php $i++; } ?>
</tbody>
</table>