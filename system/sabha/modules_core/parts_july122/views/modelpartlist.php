<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		getreportslist();
	})
});
</script>

<div style="float: right;  margin-top: -30px;;">
<?php
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
	onclick="excelDownload_modelassociation();" />
  </div>

<div >
<table cellspacing="0" cellpadding="0" width="100%" class="tblgrid">
      <col width="1%" />
      <col width="10%" />
      <col width="14%" />
      <col width="14%" />
      <col width="14%" />
      <col width="20%" />
	  <col width="15%" />
     <col width="15%" />
	  <col width="15%" />
  <tr>
      <th scope="col">S.No</th>
      <th scope="col">Brand</th>
      <th scope="col">Product</th>
      <th scope="col">Model</th>
      <th scope="col">Item Number</th>
      <th scope="col">Description</th>
      <th scope="col">Landing Price</th>
	    <th scope="col">Store Price</th>
	    <th scope="col">Customer Price</th>
   </tr>
  <?php $i=0;?>
  <?php foreach ($parts['list'] as $part){
  $trclass=$i%2==0?" class='even' ": " class='odd' ";?>
  <tr <?php echo $trclass;?>>
			<td><?php echo $start+$i;?></td>
            <td style="text-align: left;"><?php echo $part->brand_name?></td>
            <td style="text-align: left;"><?php echo $part->product_name?></td>
            <td style="text-align: left;"><?php echo $part->model_number?></td>
            <td style="text-align: left;"><?php echo $part->part_number?></td>
            <td style="text-align: left;"><?php echo $part->part_desc;?></td>
            <td style="text-align: left;"><?php echo $part->part_landing_price;?></td>
			      <td style="text-align: left;"><?php echo $part->part_sc_price;?></td>
		      	<td style="text-align: left;"><?php echo $part->part_customer_price;?></td>
  </tr>
 
  <?php $i++?>
  <?php  }?>
   <tr>
			<td align="center" colspan="5">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
</table>


</div>