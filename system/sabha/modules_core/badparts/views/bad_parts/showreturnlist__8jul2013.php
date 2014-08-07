<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		getretrunlist();
	})
});
</script>


<div style="float: right; margin-top: -11px;"><?php
$page = $this->input->post('currentpage');
$start = 0;
if($config['total_rows']>0){
	$start = $page+1;
	if($config['total_rows']>($page+$config['per_page'])){
		$end = $page+$config['per_page'];
	}else{
		$end = $config['total_rows'];
	}
	?> <span><strong><?php echo $start;?> - <?php echo $end?></strong></span>
of <span><strong><?php echo $config['total_rows'];?></strong></span> <?php }?>
</div>
<div style="text-align:right;">
 <input type="button" class="button" title="Print Report" value="<?php echo $this->lang->line('print');?>" onclick="generatePrintReport();" />
 <input type="button" class="button" title="Download Report" value="<?php echo $this->lang->line('download');?>" onclick="downloadRetunReport();" />
 </div>
 <div>&nbsp;</div>
<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="5%" /><col width="20%"/><col width="20%" /><col width="20%" /><col width="5%"/> <col width="15%" /><col width="5%" />
    <thead>
    
   		 <tr>
        	<th><?php echo $this->lang->line('sn');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('service_center');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('engineer_name');?></th>
             <th style="text-align:center"><?php echo $this->lang->line('part_number');?></th>
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
                 <td style="text-align:center"><?php echo $list->part_quantity?></td>
                 <td style="text-align:center"><?php echo $list->returned_date?></td>
                 <td style="text-align:center"><?php if($list->signed == 0){?><input type="checkbox" class="return_list" id="<?php echo $list->return_parts_detail_id; ?>" value="<?php echo $list->return_parts_detail_id;?>"/><?php } 
				 
				 else {echo "Signed" ;}?></td>
           </tr>
	<?php $i++; }?>
    </tbody>
    
    <tfoot>
		<tr>
			<td colspan="5">
			<div class="pagination"><?php echo $navigation;?></div>
            <input type="hidden" id="currentpage" name="currentpage" value="0" />
			</td>
		</tr>
	</tfoot>
    
</table>

<table >
<tr>
<td style="float:left; padding:0px"><input type="button" value="Save" class="button" id="btn_signedcheck" onclick="check_checklist()"/></td>
</tr>
</table>