<?php defined('BASEPATH') or die('Direct access script in not allowed');?>
<div style="width:570px;">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col /><col width="30%" /><col width="30%" /><col width="30%" />
<thead>
	<tr>
    	<th style="text-align:center">S.No.</th>
        <th style="text-align:left">Item Number</th>
        <th style="text-align:center">Requested Quanitiy</th>
        <th style="text-align:center">Requested Company</th>
        <th style="text-align:left">Available Quanitiy</th>
    </tr>
</thead>
<tbody>
<?php
$i=0;
foreach($part_number as $part){
	$company = $this->mdl_company->getcompanyid($company_id[$i]);
	$check_stock = $this->mdl_parts_stocks->checkPartsStock($sc_id,$part,$company);
	$trstyle=$i%2==1?" class='even' ": " class='odd' ";
	?>
	<tr<?php echo $trstyle;?>>
    	<td style="text-align:center"><?php echo ($i+1);?></td>
        <td style="text-align:left"><?php echo $part;?></td>
        <td style="text-align:center"><?php echo $part_quantity[$i];?></td>
         <td style="text-align:center"><?php echo $company_id[$i];?></td>
        <td style="text-align:left">
        <?php 
        if($check_stock->parts_available == true){
        	if($check_stock->stock_quantity<$part_quantity[$i]){
				echo $check_stock->stock_quantity.' ( '.$this->lang->line('quantity_unavailable').' )';
			}else{
				echo $check_stock->stock_quantity;
			}
        }else{
        	echo $this->lang->line('part_unavailable');
        }
        ?>
        </td> 
    </tr>
<?php $i++;}?>
</tbody>
</table>
</div>