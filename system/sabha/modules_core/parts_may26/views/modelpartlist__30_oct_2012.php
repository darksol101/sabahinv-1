<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		getreportslist();
	})
});
</script>
<div style="float: right; margin-bottom: 5px;">
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

  </div>

<div >
<table cellspacing="0" cellpadding="0" width="100%" class="tblgrid">
<col width="10%" />
<col width="50%" />
	<col width="40%" />
  <tr>
  <th scope="col">S.No</th>
    <th scope="col">Model</th>
    <th scope="col">Part</th>
  </tr>
 
 
  <?php $i=1;?>
  <?php foreach ($parts['list'] as $part){
  $trclass=$i%2==0?" class='even' ": " class='odd' ";?>
  <tr <?php echo $trclass;?>>
			<td><?php echo $i;?></td>
  
    <td style="text-align: left;"><?php echo $part->model_number?></td>
    <td style="text-align: left;"><?php echo $part->part_number?></td>
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