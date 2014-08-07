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
  <col width="5%" />
  <col />
  <col width="15%"  />
  <col width="15%" />
  <col width="15%" />
  <col width="15%" />
  <thead>
    <tr>
      <th>S.No.</th>
      <th>Item Number</th>
      <th>Engineer</th>
      <th>Store</th>
      <th style="text-align:center">Status</th>
      <th style="text-align:center">Part 	Qty</th>
    </tr>
  </thead>
  <tbody>
    <?php
	$i=1;
	foreach ($bad_parts as $bad_part){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		$status=$bad_part->bad_parts_status=="1"?icon("tick","Active","png"):icon("cross","Inactive","png");
		?>
    <tr <?php echo $trstyle;?>>
      <td><?php echo $i;?></td>
      <td><a href="<?php echo base_url();?>parts/bad_parts/edit/<?php echo $bad_part->bad_parts_id;?>"><?php echo $bad_part->part_number;?></a></td>
      <td><?php echo $bad_part->engineer_name;?></td>
      <td><?php echo $bad_part->sc_name;?></td>
      <td style="text-align:center">
      <?php
      echo $this->mdl_mcb_data->getStatusDetails($bad_part->bad_parts_status,'bad_parts_status');
	  ?>
      </td>
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
