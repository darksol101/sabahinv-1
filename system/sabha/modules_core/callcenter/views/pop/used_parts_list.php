<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<style type="text/css">
#facebox .footer {visibility: visible;}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		getpartsbysearch();
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
<div style="width:600px;">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="10%" /><col width="20%" /><col width="50%" /><col width="20%" /><col width="15%" />
	<thead>
		<tr>
			<th style="text-align:center"><?php echo $this->lang->line('sn');?></th>
			<th><?php echo $this->lang->line('part_number');?></th>
			<th style="text-align:center"><?php echo $this->lang->line('description');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('quantity');?></th>
             <th style="text-align:center;"><?php echo $this->lang->line('company_name');?></th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; foreach ($lists['list'] as $list){
			$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
			<tr<?php echo $trstyle;?>>
				<td style="text-align:center"><?php echo $i;?></td>
				<td><a class="setpartused" style="cursor:pointer;"><?php echo $list->part_number;?></a></td>
				<td style="text-align:center"><?php echo $list->part_desc;?></td>
                <td style="text-align:center"><?php echo $list->allocated_quantity;?></td>
                <td style="text-align:center;"><?php echo $list->company_title;?>
			</tr>
		<?php $i++; }?>
	</tbody>
    <tfoot>
   		 <tr>
			<td colspan="3">
			<div class="pagination"><?php echo $navigation;?></div>
            <input type="hidden" id="currentpage" name="currentpage" value="0" />
			</td>
		</tr>
    </tfoot>
    
   </table>
</div>