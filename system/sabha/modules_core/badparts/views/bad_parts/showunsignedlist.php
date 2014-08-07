<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		unsignedlist();
	})
});
</script>



<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="5%" /><col width="20%"/><col width="15%" /><col width="15%" /><col width="25%"/> <col width="10%" /><col width="10%" />
	<thead>
    <tr>
    <td colspan="8" style="text-align:right;"><input type="button" class="button" title="Download Report" value="<?php echo $this->lang->line('download');?>" onclick="downloadUnsignedReport();" /></td>
    </tr>
    <tr>
        	<th><?php echo $this->lang->line('sn');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('service_center');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('engineer_name');?></th>
             <th style="text-align:center"><?php echo $this->lang->line('part_number');?></th>
             <th style="text-align:center"><?php echo $this->lang->line('part_desc');?></th>
             <th style="text-align:center"><?php echo $this->lang->line('quantity');?></th>
             <th style="text-align:center"><?php echo $this->lang->line('date');?></th>
             <th></th>
              
        </tr>
    </thead>
    <tbody>
	<?php
	/*echo '<pre>';
	print_r($lists);
	die();*/
	$i=1;
	foreach($lists['list'] as $list){
		
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
			<tr<?php echo $trstyle;?>>
            	<td><?php echo $i;?></td>
                 <td style="text-align:center"><?php echo $list->sc_name;?></td>
                 <td style="text-align:center"><?php echo $list->engineer_name;?></td>
                 <td style="text-align:center"><?php echo $list->part_number?></td>
                 <td style="text-align:center"><?php echo $list->part_desc?></td>
                 <td style="text-align:center"><?php echo $list->part_quantity;?></td>             
                 <td style="text-align:center"><?php echo $list->returned_date;?></td>
                 <td><a  title="Print Report" target="_self" href="<?php echo site_url();?>badparts/returnlist/<?php echo $list->sc_id;?>/<?php echo $list->engineer_id;?>/<?php echo $list->returned_date;?>/1"><?php echo icon('print','Print','ico')?></a> </td>
           </tr>
	<?php $i++; }?>
    </tbody>
    
    <

     
</table>