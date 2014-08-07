<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showPartList();
	})

	

});

function print_no_barcodes (div_id) {
	$("#"+div_id).printThis();
}


</script>

<style>
	.barcode{
	border: 1px solid #49AB3C;
	position:absolute;
	background: #eee;
  	z-index: 100;  
  	top:40%;  
  	left:35%;  
  	margin:-100px 0 0 -100px;
  	padding: 20px;  
 	width:642px;  
  	height:200px;
	min-height: 400px;
  	height: auto;
	border-radius: 5px;
	position:fixed;
  	left: 40%;

	}

	.closePop{
		margin: -29px;
		float: right;

	}
	.barcode_list img{
		width: 210px;
		height: 37px;
		margin: 20px;
	}
	.takein{
		display: block;
		float: left;
		clear: both;
		margin-top: 10px
	}
</style>
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
	?>

	<span><strong><?php echo $start;?> - <?php echo $end?></strong></span>
of <span><strong><?php echo $config['total_rows'];?></strong></span> <?php }?>
<input type="button" name="button" class="button" value="Download"
	onclick="excelDownload();" /> </div>
<div style="clear: both;"></div>
<table style="width: 100%" class="tblgrid" cellpadding="0"
	cellspacing="0">
	<col width="1%" />
	<col width="15%" />
	<col width="5%" />
	<col width="20%" />
    <col width="10%" />
	<col width="10%" />
    <col width="10%" />
    <col width="10%" />
    <col width="10%" />
	
	<thead>
		<tr>
			<th style="text-align: center;">S.No.</th>
			<th><label><?php echo $this->lang->line('partnumber');?></label></th>
			<!-- <th><label><?php echo $this->lang->line('part_init_no');?></label></th> -->
			<th style="text-align: center;"><label><?php echo $this->lang->line('barcode');?></label></th>
			<th style="text-align: center;"><label><?php echo $this->lang->line('description');?></label></th>
			<!-- <th style="text-align: center;"><label><?php echo $this->lang->line('part_size');?></label></th>
			<th style="text-align: center;"><label><?php echo $this->lang->line('part_color');?></label></th> -->
			<th style="text-align: center;"><label><?php echo $this->lang->line('order_level');?></label></th>
            <th style="text-align: center;"><label><?php echo $this->lang->line('order_level_max');?></label></th>
			<th style="text-align: center;display:none;"><label><?php echo $this->lang->line('landing_price');?></label></th>
            <th style="text-align: center;"><label><?php echo $this->lang->line('customer_price');?></label></th>
			<th style="text-align: center;display:none;"><label><?php echo $this->lang->line('service_center_price');?></label></th>
			<th></th>
			<th>&nbsp;</th>
			<th></th>
		</tr>
	</thead>
	
	<tbody>
	<?php
	$i=1;
	$j=1;
	foreach ($parts as $part){

		$barcodelink="uploads/barcodes/".$part->part_id.".png";
		
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $j++;?></td>
			<td><a href="#" onclick="editPart('<?php echo $part->part_id?>')"><?php echo $part->part_number;?></a></td>
			<!-- <td><a><?php echo $part->part_initial_no;?></a></td> -->
			<td style="text-align: center;">
				
					<div>
							<img width="240" height="42" style="margin-top:10px;" src="<?php echo site_url().$barcodelink;?>" alt="<?php echo $part->part_desc;?>" onclick="barcodes('<?php echo $part->part_id;?>','<?php echo $part->part_number;?>','<?php echo $part->part_customer_price;?>')">
						    <p>MRP: <?php echo $part->part_customer_price;?></p>
					</div>
			</td>
			
			<td style="text-align: center;"><?php echo $part->part_desc;?></td>
			

			<!-- <td style="text-align: center;"><?php echo $part->part_size;?></td>
			<td style="text-align: center;"><?php echo $part->part_color;?></td> -->
			<td style="text-align: center;"><?php echo $part->order_level;?></td>
            <td style="text-align: center;"><?php echo $part->order_level_max;?></td>
			<td style="text-align: center;display:none;"><?php echo $part->part_landing_price;?></td>
            <td style="text-align: center;"><?php echo $part->part_customer_price;?></td>
			<td style="text-align: center;display:none;"><?php echo $part->part_sc_price;?></td>
			<td style="text-align:center"><a class="btn" onclick="addtoproductmodel('<?php echo $part->part_id;?>','<?php echo $part->part_number;?>')"><?php echo icon('add','add','png')?></a></td>
			<td style="text-align: center;"><a class="info" style="cursor: pointer;" onclick="editPart('<?php echo $part->part_id?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align: center;"><a class="btn"	onclick="deletePart('<?php echo $part->part_id?>')"><?php echo icon("delete","Delete","png");?></a></td>
		</tr>
		<?php
		$i++;
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td align="center" colspan="5">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>
