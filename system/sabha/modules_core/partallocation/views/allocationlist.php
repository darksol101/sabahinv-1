<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		allocationlist();
	})
});
</script>


<div style="float: right; margin-top: -28px;"><?php
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
<input type="button" name="button" class="button" value="Download"
	onclick="excelDownload();" /> <?php /*?><input type="button" name="button"
	class="button" value="Email" onclick="email_pop();" /><?php */?></div>
    
    
    
<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="1%" /><col width="20%"/><col width="20%" /><col width="20%" /><col width="20%" /><col width="15%"/>
	<thead>
    	<tr>
        	<th><?php echo $this->lang->line('sn');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('engineer_name');?></th>
             <th style="text-align:center"><?php echo $this->lang->line('service_center');?></th>
             <th><?php echo $this->lang->line('part_number');?></th>
             <th><?php echo $this->lang->line('part_desc');?> </th>
             <th style="text-align:center"><?php echo $this->lang->line('allocated_quantity');?></th>
             <th></th>
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
                <td style="text-align:center"><?php echo $list->engineer_name;?></td>
                 <td style="text-align:center"><?php echo $list->sc_name;?></td>
                 <td><a href="<?php echo site_url();?>partallocation/allocationdetails/<?php echo $list->engineer_id?>/<?php echo $list->part_number?>/<?php echo $list->company_id?>/<?php echo $list->sc_id?>"><?php echo $list->part_number;?></td>
               <td><?php echo $list->part_desc?></td>
                 <td style="text-align:center"><?php echo $list->allocated_quantity;?></td>
                 <td><a  title="Print Report" target="_self" href="<?php echo site_url();?>partallocation/allocationreport/<?php echo $list->sc_id;?>/<?php echo $list->engineer_id;?>/1/<?php echo $list->part_number;?>"><?php echo icon('print','Print','ico')?></a> </td>
                 <td><a href="<?php echo site_url();?>partallocation/allocationdetails/<?php echo $list->engineer_id?>/<?php echo $list->part_number?>/<?php echo $list->company_id?>/<?php echo $list->sc_id?>"><?php echo $this->lang->line('details');?> </a></td>
               	 
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