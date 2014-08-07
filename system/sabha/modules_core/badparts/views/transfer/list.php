<?php defined('BASEPATH') or die('Direct access is not allowed');
?>
<table class="tblgrid" style="width: 100%">
    <col width="1%" /><col /><col /><col width="2%" /><col width="1%" />
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Store</th>
            <th style="text-align: center">Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i=0;
    foreach ($parts_order_list as $parts_orders){
        $trstyle = ($i%2)?' class="even"':' class="odd"';
    ?>
    <tr<?php echo $trstyle;?>>
        <td><?php echo ($i+1);?></td>
        <td><?php echo $parts_orders->frm_sc_name;?></td>
        <td style="text-align: center"><?php echo $this->mdl_mcb_data->getStatusDetails($parts_orders->return_sc_status,'return_order_status');?></td>
        <td><a class="btn" href="<?php echo site_url();?>badparts/transfer/edit/<?php echo $parts_orders->return_sc_id;?>"><?php echo icon('edit','Edit','png');?></a></td>
    </tr>
    <?php $i++; }?>
    
    </tbody>
</table>
    