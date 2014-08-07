<script type="text/javascript">

$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showmodellist();
	})
});
</script>



<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="1%" />
	<col />
	<col />
	<col />

	<thead>
		<tr>
			<th width="1%" align="center">S.No.</th>
			<th style="text-align: left;">Brand</th>
			<th style="text-align: center;">Product</th>
			<th style="text-align: center">Model</th>
		
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	$trclass=$i%2==0?" class='even' ": " class='odd' ";
	foreach($models as  $model) {	?>
    
		<tr <?php echo $trclass;?>>
			<td style="text-align: center;"><?php echo $i;?></td>
			<td style="padding: 2px; text-align: center; text-align: left"
				align="left"><a onclick="setModel('<?php echo $model->model_id;?>')"
				class="btn"><?php echo $model->brand_name;?></a></td>
			<td style="text-align: center"><?php echo $model->product_name;?></td>
			<td style="text-align: center"><?php echo $model->model_number;?></td>
			
		</tr>
		<?php $i++; }?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">
			<div class="pagination"><?php echo $navigation;?></div>
             <input type="hidden" id="currentpage" name="currentpage" value="0" />
			</td>
		</tr>
	</tfoot>
</table>
		