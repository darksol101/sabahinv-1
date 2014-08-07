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
	  
	  <td style="text-align:center"> 
      <?php echo $bad_part->sc_name;?></td>
      
      <td style="text-align:center; cursor:pointer"> <a class="edit_populate" title="Click to polupate above" onclick="setRecords('<?php echo $bad_part->sc_id;?>','<?php echo $bad_part->engineer_id;?>','<?php echo $bad_part->part_number;?>');">
      
      <?php echo $bad_part->engineer_name;?></a></td>
      
     <td style="text-align:center;cursor:pointer"><input type="hidden" name="partnum[]" value="<?php echo $bad_part->part_number;?>" class="text-input" /> <a class="edit_populate" title="Click to polupate above" onclick="setRecords('<?php echo $bad_part->sc_id;?>','<?php echo $bad_part->engineer_id;?>','<?php echo $bad_part->part_number;?>');"><span class="lbl"><?php echo $bad_part->part_number;?> </a></td>
      <td style="text-align:center"><input type="hidden" name="pdesc[]" value="<?php echo $bad_part->part_desc;?>" class="text-input" 
      /><span class="lbl"><?php echo $bad_part->part_desc;?></td>
      <td style="text-align:center"><input type="hidden" name="pquantity[]" value="<?php echo $bad_part->part_quantity;?>" class="text-input" /><span class="lbl"><?php echo $bad_part->part_quantity;?></td>
      <td style="text-align:center; display:none "><input type="hidden" name="sc_id[]" value="<?php echo $bad_part->sc_id;?>" class="text-input" /><span class="lbl"><?php echo $bad_part->sc_id;?></td>
      <td style="text-align:center; display:none"><input type="hidden" name="eng_id[]" value="<?php echo $bad_part->engineer_id;?>" class="text-input" /><span class="lbl"><?php echo $bad_part->engineer_id;?></td>
     
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
