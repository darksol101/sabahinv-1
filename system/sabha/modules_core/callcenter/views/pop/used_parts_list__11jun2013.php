<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
#facebox .footer {visibility: visible;}
</style>
<div style="width:600px;">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col /><col width="20%" /><col width="10%" /><col width="20%" />
	<thead>
		<tr>
			<th style="text-align:center"><?php echo $this->lang->line('sn');?></th>
			<th><?php echo $this->lang->line('part_number');?></th>
			<th style="text-align:center"><?php echo $this->lang->line('description');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('quantity');?></th>
             <th style="text-align:center"><?php echo $this->lang->line('company_name');?></th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; foreach ($available_parts as $part){
			$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
			<tr<?php echo $trstyle;?>>
				<td style="text-align:center"><?php echo $i;?></td>
				<td><a class="setpartused" style="cursor:pointer;"><?php echo $part->part_number;?></a></td>
				<td style="text-align:center"><?php echo $part->part_desc;?></td>
                <td style="text-align:center"><?php echo $part->allocated_quantity;?></td>
                 <td style="text-align:center"><?php echo $part->company_title;?></td>
			</tr>
		<?php $i++; }?>
	</tbody>
</table>
</div>