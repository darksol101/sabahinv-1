<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');?>


<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showCompanyList();
	})
});
</script>

<table style="width: 100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="1%" />
    <col />
   <col width="25%" />
    <col width="25%" />
    <col width="20%" />
  
	<thead>
		<tr>
			<th><?php echo $this->lang->line('s.no');?></th>
			<th><?php echo $this->lang->line('company_name');?></th>
			<th><?php echo $this->lang->line('company_desc');?></th>
            <th><?php echo $this->lang->line('phone');?></th>
			<th><?php echo $this->lang->line('company_address');?></th>
			
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
			<td><a class="setcompany" style="cursor:pointer;"><?php echo $list->company_title ;?></a></td>
            <td><?php echo $list->company_desc ;?></td>
            <td><?php echo $list->phone;?></td>
            <td><?php echo $list->address;?></td>
       
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


