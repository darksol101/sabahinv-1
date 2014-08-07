<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');?>


<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		//showpartbinlist();
		getBinSearchList();
	})
});
</script>

<table style="width: 100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="2%" />
    <col width="40%" />
    <col width="40%" />
	<thead>
		<tr>
			<th><?php echo $this->lang->line('s.no');?></th>
             <th><?php echo $this->lang->line('bin_name');?></th>
            <th><?php echo $this->lang->line('bin_desc');?> </th>
          <th></th>
			
		</tr>
	</thead>
	<tbody>
	<?php
	
	$i=1;
	foreach($lists['list'] as $list):{
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
			
          
            <td><a class="setbin" style="cursor:pointer;"><?php echo $list->partbin_name;?></a></td>
			 <td> <?php echo $list->partbin_desc;?></td> 
             <td><input type="hidden" value="<?php echo $list->partbin_id;?>" /></td>      
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


