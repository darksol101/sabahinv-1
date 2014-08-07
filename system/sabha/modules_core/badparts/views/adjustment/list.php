<?php defined('BASEPATH') or die('Direct access is not allowed');
?>
<table class="tblgrid" style="width: 100%">
    <col width="1%" /><col /><col /><col /><col width="15%" /><col width="15%" /><col width="1%" />
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Item Number</th>
            <th>Store</th>
            <th style="text-align:center">Quantity</th>
            <th style="text-align:center">Action</th>
            <th style="text-align: center">Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i=0;
    foreach ($adjustments as $adjustment){
        $trstyle = ($i%2)?' class="even"':' class="odd"';
        $action= $this->mdl_mcb_data->getStatusDetails($adjustment->action,'badpart_action');
       // $scraped = $this->mdl_mcb_data->getStatusDetails($adjustment->scraped,'status');
    ?>
    <tr<?php echo $trstyle;?>>
        <td><?php echo ($i+1);?></td>
        <td><?php echo $adjustment->part_number;?></td>
        <td><?php echo $adjustment->sc_name;?></td>
        <td style="text-align:center"><?php echo $adjustment->part_quantity;?></td>
        <td style="text-align:center"><?php echo $action;?></td>
        <td style="text-align: center"><?php echo  ($adjustment->approved == 0 ? 'Pending' : 'Approved')?></td>
      
    </tr>
    <?php $i++; }?>
    <tr><td colspan="6" style="text-align:right"> <input type="button" class="button" onclick="approveAction();" value="Approve" /></td></tr>
    
    </tbody>
</table>
    