<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showbadstockList();
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


<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="5%" />
    <col width="30%"  />
    <col width="25%"  />
    <col width="20%"  />
    <col  />
 	<thead>
    	<th>S.No </th>
        <th>Store </th>
        <th>Item Number </th>
        <th>Item description </th>
        <th>Quantity </th>
    </thead>
    <tbody>
	<?php
	//print_r($stocklist);
	$i=0;
	foreach($stocklist as $stock){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
			<tr<?php echo $trstyle;?>>
            	<td> <?php echo $start+ $i;?> </td>
                <td><a href='<?php echo site_url();?>badparts/badpartdetails/<?php echo $stock->sc_id ;?>/<?php echo $stock->part_number;?>'><?php echo $stock->sc_name;?></a> </td>
                <td><?php echo $stock->part_number;?> </td>
                <td><?php echo $stock->part_desc;?> </td>
                <td><?php echo $stock->part_quantity;?> </td>
            </tr>
	<?php $i++; }?>
    </tbody>
     <tfoot>
		<tr>
			<td colspan="8">
			<div class="pagination"><?php echo $navigation;?></div>
            <input type="hidden" id="currentpage" name="currentpage" value="0" />
			</td>
		</tr>
	</tfoot>
</table>