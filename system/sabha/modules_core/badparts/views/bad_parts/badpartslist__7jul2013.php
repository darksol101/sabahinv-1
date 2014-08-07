<?php defined('BASEPATH') or die('Direct access is not allowed');?>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showBadPartsList();
	})
});
</script>
<table style="width: 100%" class="tblgrid" cellpadding="0" cellspacing="0">
  <col width="1%" />
  <col  width="10%"/>
  <col width="12%"  />
  <col width="12%" />
  <col width="14%"/>
  <col width="8%" />
  <thead>
    <tr>
      <th>S.No.</th>
      <th style="text-align:center">Store</th> 
      <th style="text-align:center">Engineer</th>
      <th style="text-align:center">Item Number</th>
      <th style="text-align:center">Item description </th>
      <th style="text-align:center">Part Qty</th>
    </tr>
  </thead>
  <tbody>
    <?php
	$i=1;
	foreach ($bad_parts as $bad_part){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
    <tr <?php echo $trstyle;?>>
      <td><?php echo $i;?></td> 
      <td style="text-align:center"><?php echo $bad_part->sc_name;?></td>
      <td style="text-align:center"><?php echo $bad_part->engineer_name;?></td>
     <td style="text-align:center"><?php echo $bad_part->part_number;?></td>
      <td style="text-align:center"><?php echo $bad_part->part_desc;?></td>
      <td style="text-align:center"><?php echo $bad_part->part_quantity;?></td>
    </tr>
    <?php
		$i++;
	}
	?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="6" style="vertical-align: middle;"><div class="pagination"><?php echo $navigation;?></div></td>
    </tr>
  </tfoot>
</table>
