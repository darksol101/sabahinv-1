<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<table style="width: 100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="1%" />
    <col />
    <col width="10%" />
     <col width="15%" />
    <col width="5%" />
    <col width="20%" />
    <col width="1%" />
    <col width="1%" />
    <col width="1%" />
	<thead>
		<tr>
			<th><?php echo $this->lang->line('s.no');?></th>
			<th><?php echo $this->lang->line('company name');?></th>
            <th><?php echo $this->lang->line('company_id');?></th>
			<th><?php echo $this->lang->line('company desc');?></th>
            <th><?php echo $this->lang->line('phone');?></th>
			<th><?php echo $this->lang->line('company address');?></th>
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
			<td><?php echo $list->company_title ;?></td>
            <td><?php echo $list->company_id ;?></td>
			<td><?php echo $list->company_desc ;?></td>
            
			<td><?php echo $list->phone;?></td>
            <td><?php echo $list->address;?></td>
            <td><a class="btn" onclick="addcompanypart('<?php // echo $list->company_id;?>')"><?php //echo icon('add','add','png')?></a></td>
			<td><a class="btn" onclick="editcompany('<?php echo $list->company_id;?>')"><?php echo icon("edit","Edit","png");?></a></td>
			<td><a class="btn" onclick="deletecompany('<?php echo $list->company_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
		</tr>
		<?php
		$i++;
	} endforeach;
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
			<div class="pagination"><?php //echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>


