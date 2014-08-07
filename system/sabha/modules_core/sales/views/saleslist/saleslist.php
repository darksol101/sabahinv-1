<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showSalesList();
	})
});
</script>

<table style="width:100%;" class="tblgrid">
    	<col width="1%" />
        <col width="" />
        <col width="10%" />
       <col width="15%" />
       <col width="5%" />
        <col width="5%" />
        <thead>
        <tr>
            <th>S.No.</th>
            <th>Sales Number</th>
            <th style="text-align:center">Sales Date</th>
           <th>Store</th>
           <th> Status </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        
        <?php
		$i=1;		
		foreach($sales as $sale){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
	
            <tr <?php echo $trstyle;?>>
                <td><?php echo $i+$page['start'];?></td>
                <td ><?php echo $sale->sales_number;?></td>
                <td style="text-align:center"><?php echo $sale->sales_date;?></td>
                
                 <td><?php echo $sale->sc_name?></td>
                 
                <td> <?php echo $this->mdl_mcb_data->getStatusDetails($sale->sales_status,'sales_status');?></td>
                <td><a href="<?php echo site_url();?>sales/sale/<?php echo $sale->sales_id;?>"><?php echo icon('edit','Edit','png')?></a></td>
            </tr>
        <?php $i++; } ?>
        </tbody>
        <tfoot>
		<tr> 
			<td colspan="6">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
    </table>