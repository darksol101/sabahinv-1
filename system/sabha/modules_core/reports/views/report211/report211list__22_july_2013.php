<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		getreport211();
	})
});
</script>



<div style=" margin-bottom:5px; text-align:right"	><?php
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



<table style="width: 100%;" cellpadding="0" cellspacing="0"	class="tblgrid" ">
	<col width="1%" />
    <col width="12%" />
    <col width="10%" />
    <col width="15%" />
    <col width="1%" />
     <col width="1%" />
	<thead>
		<tr>
			<th><?php echo $this->lang->line('s.no');?></th>
			<th><?php echo $this->lang->line('call_id');?></th>
            <th><?php echo $this->lang->line('sc_name');?></th>
            <th><?php echo $this->lang->line('engineer_name');?></th>
            <th><?php echo $this->lang->line('closure_dt');?></th>
			
           <th></th>
			
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach($lists as $list):{
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
			<td><?php echo $list->call_uid ;?></td>
            <td><?php echo $list->sc_name ;?></td>
			<td><?php echo $list->engineer_name ;?></td>
            <td><?php echo $list->closure_dt ;?></td>
            <td> </td>
		</tr>
		<?php
		$i++;
	} endforeach;
	?>
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
