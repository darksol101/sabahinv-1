<?php if( !defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		getLedgerAssignList();
	})
});
</script>
<table class="tblgrid" width="100%">
<col width="1%"  /><col  /><col  /><col  /><col  /><col width="1%" /><col width="1%" />
<thead>
    <tr>
    	<th>S.No.</th>
        <th>Ledger Name</th>
        <th>Billing Head</th>
        <th>Type</th>
        <th>Service Center</th>
        <th></th>
        <th></th>
    </tr>
  </thead>
  <tbody>    
    <?php
    $i=1;
    foreach($assigns as $assign){
	$trstyle = ($i%2==0)?' class="even"':' class="odd"';    
	?>
    <tr<?php echo $trstyle;?>>
    	<td><?php echo $i;?></th>
        <td><?php echo $assign->name;?></td>
        <td><?php echo $assign->ledger_assign_name;?></td>
        <td><?php echo ($assign->ledger_assign_type)?$assign->ledger_assign_type.'r':'';?></td>
         <td><?php echo $assign->sc_name;?></td>
        <td><a class="btn" onclick="editLedgerAssgn('<?php echo $assign->ledger_assign_id;?>')"><?php echo icon('edit','Delete','png');?></a></td>        
        <td><a class="btn" onclick="deleteLedgerAssgn('<?php echo $assign->ledger_assign_id;?>')"><?php echo icon('delete_1','Delete','png');?></a></td>
    </tr>
    <?php $i++; }?>
  </tbody>
  <tfoot>
  	<tr><td colspan="6" style="text-align:center"><div class="pagination"><?php echo $navigation;?></div></td></tr>
  </tfoot>
</table>
