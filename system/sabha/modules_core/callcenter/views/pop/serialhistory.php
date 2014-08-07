<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
#facebox .footer {
	visibility:visible;
}
</style>
<table cellpadding="0" cellspacing="0" class="tblgrid" width="100%">
	<col width="1%" /><col width="99%" />
    <thead><tr><th>S.No.</th><th>Serial Number</th></tr></thead>
	<tbody>
	<?php
    $i=1;
    foreach($serial_numbers as $row){
    $trstyle=$i%2==0?" class='even' ": " class='odd' ";
    ?>
    <tr<?php echo $trstyle;?>>
        <td style="text-align:center"><?php echo $i;?></td>
        <td><?php echo $row->call_serial_no;?></td>
    </tr>
    <?php $i++;}?>
    </tbody>
    <tfoot><tr><td colspan="2">&nbsp;</td></tr></tfoot>
</table>

