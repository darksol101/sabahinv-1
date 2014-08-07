<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div style="width:450px;">
<div class="toolbar1">
    <form>
        <label style="padding:0px!important;"><?php echo $this->lang->line('muliple_service_centers');?></label>
    </form>
</div>
    <table cellpadding="0" cellspacing="0" class="tblgrid" width="100%">
        <col width="1%" /><col /><col width="30%" />
        <thead><tr><th>S.No.</th><th>Store</th><th>&nbsp;</th></tr></thead>
        <tbody>
        <?php
        $i=1;
        foreach($service_centers as $row){
        $trstyle=$i%2==0?" class='even' ": " class='odd' ";
        ?>
        <tr<?php echo $trstyle;?>>
            <td style="text-align:center"><?php echo $i;?></td>
            <td><label><?php echo $row->sc_name;?></label></td>
            <td><a class="btn" onclick="setServiceCenter('<?php echo $row->sc_id;?>')">Assign to Call</a></td>
        </tr>
        <?php $i++;}?>
        </tbody>
    </table>
</div>