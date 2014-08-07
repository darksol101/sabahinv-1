<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showSalesMakerList();
	})
});
</script>

<div style="float: right; margin-bottom: 5px;"><?php
$page = $this->input->post('currentpage');
$start = 0;
if($config['total_rows']>0){
	$start = $page+1;
	if($config['total_rows']>($page+$config['per_page'])){
		$end = $page+$config['per_page'];
	}else{
		$end = $config['total_rows'];
	}
	}?>

</div>
<div style="clear: both;"></div>
<table style="width: 100%" class="tblgrid" cellpadding="0" cellspacing="0">
	<thead>
		
		<tr>
			<th>S.No.</th>
			<th><label>Sale Name</label></th>
			
			<th><label>Deduction Type</label></th>
			<th><label>Dedution Value</label></th>
			<th><label>Status</label></th>
			<th><label>Started Date</label></th>
			<th><label>End Date</label></th>
			<th>Action</th>
			
		</tr>
	</thead>
	
	<tbody>
<?php $j=1;?>		
<?php foreach ($makerlist as $maker):?>	
	<tr id="<?php echo $maker->maker_id;?>">
			<td><?php echo $j++;?></td>
			<td><?php echo $maker->sale_name ;?></td>
			
			<td value="<?php echo $maker->sale_deduction_type;?>">
				<?php if($maker->sale_deduction_type==1){
					$value=$maker->sale_deduction_value." %";
					echo "Percentage";
				} else
				{
					$value="Rs. ".$maker->sale_deduction_value; 
					echo "Amount";
				}?>

			</td>

			<td value="<?php echo $maker->sale_deduction_value?>"><?php echo $value;?></td>
			<td value="<?php echo $maker->sale_status;?>"><?php if($maker->sale_status==1) echo 'Active'; elseif($maker->sale_status==2) echo "Inactive"; else echo "Expired"; ?></td>
			
			<td><?php echo $maker->sale_date_start; ?></td>
			<td><?php echo $maker->sale_date_end; ?></td>
			<td>
				<a  style="cursor: pointer;" onClick="getAssignedList('<?php echo $maker->maker_id;?>','<?php echo $maker->sale_name;?>')"><?php echo icon('add','add','png')?></a> &nbsp;
				<a class="info" style="cursor: pointer;" onClick="editSaleMaker('<?php echo $maker->maker_id;?>')"><?php echo icon('edit','edit','png');?></a>&nbsp;
				<a  style="cursor: pointer;"class="btn" onClick="deleteSaleMaker('<?php echo $maker->maker_id;?>')"><?php echo icon("delete","Delete","png");?></a>&nbsp;
			</td>

	</tr>	
<?php endforeach;?>	
	</tbody>
	<tfoot>
		<tr>
			<td align="center" colspan="5">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>
