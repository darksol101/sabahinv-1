<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');?>




<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showpartbin_details();
		
	})
});
</script>

<table style="width: 100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="2%" />
    <col width="20%" />
    <col width="15%" />
    <col width="20%" />
    <col width="20%" />
  <col width="35%" />
	<thead>
		<tr>
			<th><?php echo $this->lang->line('s.no');?></th>
            <th><?php echo $this->lang->line('service_center');?></th>
			<th><?php echo $this->lang->line('bin_name');?></th> 
             <th><?php echo $this->lang->line('bin_desc');?></th>
            <th><?php echo $this->lang->line('part_number');?></th>
           <th> <?php echo $this->lang->line('part_desc')?></th>
			
		</tr>
	</thead>
	<tbody>
	<?php
	//echo '<pre>';
	//print_r($lists['list']);
	//die();	
	$i=1;
	foreach($lists['list'] as $list):{
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
			<td><?php echo $list->sc_name;?></td>
            <td><?php echo $list->partbin_name;?></td>
            <td><?php echo $list->partbin_desc ;?></td>
            <td><?php echo $list->part_number;?></td>
			<td><?php  echo $list->part_desc;?></td>          
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


