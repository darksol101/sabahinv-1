<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showVendorList();
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
<div align="right">
<input type="button" class="button" value="<?php echo $this->lang->line('download');?>" onclick="downloadreport();" />
</div>

<table style="width: 100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="5%" /><col width="15%" /><col width="15%" /><col width="20%" /><col width="3%" /><col width="1%" />
	<thead>
		<tr>
			<th>S.No.</th>
			<th>Vendors Name</th>
			<th>Phone</th>
			<th>Address</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach($lists as $list){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
			<td><?php echo $list->vendor_name ;?></td>
			<td><?php echo $list->vendor_phone ;?></td>
			<td><?php echo $list->vendor_address;?></td>
			<td><a class="btn" onclick="editvendor('<?php echo $list->vendor_id;?>')"><?php echo icon("edit","Edit","png");?></a></td>
			<td><a class="btn" onclick="deletevendor('<?php echo $list->vendor_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
		</tr>
		<?php
		$i++;
	} 
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
			<div class="pagination"><?php echo $navigation;?></div>
             <input type="hidden" id="currentpage" name="currentpage" value="0" />
			</td>
		</tr>
	</tfoot>
</table>



