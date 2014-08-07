<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');?>


<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showpartbinlist();
	})
});
</script>

<table style="width: 100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="10%" />
    <col width="15%" />
    <col width="5%" />
    <col width="15%" />
    <col width="15%" />
    <col width="1%" />
    <col width="1%" />
    <col width="1%" />
	<thead>
		<tr>
			<th><?php echo $this->lang->line('s.no');?></th>
            <th><?php echo $this->lang->line('service_center');?></th>
			<th><?php echo $this->lang->line('bin_name');?></th>
            <th><?php //echo $this->lang->line('bin_id');?></th>
             
			<th><?php echo $this->lang->line('bin_description');?></th>
			<th></th>
			<th></th>
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
            <td><?php echo $list->sc_name;?></td>
			<td><?php echo $list->partbin_name ;?></td>
            <td><?php //echo $list->partbin_id;?></td>
            <td><?php echo $list->partbin_desc ;?></td>
            <td><a class="btn" onclick="addcompanypart('<?php // echo $list->company_id;?>')"><?php //echo icon('add','add','png')?></a></td>
			<td><a class="btn" onclick="editpartbin('<?php echo $list->partbin_id;?>')"><?php echo icon("edit","Edit","png");?></a></td>
			<td><a class="btn" onclick="deletepartbin('<?php echo $list->partbin_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
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


