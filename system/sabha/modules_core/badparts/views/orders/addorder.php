<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet"	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js"	type="text/javascript"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script type="text/javascript">
	$(function() {
		function log( message ) {
			$( "<div/>" ).text( message ).prependTo( "#log" );
			$( "#log" ).scrollTop( 0 );
		}
		var appendTo = $( ".selector" ).autocomplete( "option" );
		$( "#part_number" ).autocomplete({			 
			source: "<?php echo site_url();?>badparts/transfer/getjsonparts?sc_id="+$("#badparts_from_sc_id").val(),
			minLength: 1,
			delay: 300,
			
			select: function( event, ui ) {
				$('#part_description').val(ui.item.pdesc);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			focus: function(event, ui) {
				$("#part_number").val(ui.item.label);
				$('#part_desc').val(ui.item.pdesc);
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
	});
</script>
<script language="javascript">
$(document).ready(function(){
						   $("#cancel_part").hide() ;
	$("#orderForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){  document.orderForm.submit();}}  
	});
});

</script>

<form action="" method="post" name="orderForm" id="orderForm">

<div style="float:left; width:30%">
 <table width="100%" cellpadding="0" cellspacing="0" class="">
 <?php if (!empty($detailorders->badparts_order_number)){?>
     <tr>
     		<th><label> Order Number </label></th>
            <td><?php echo $detailorders->badparts_order_number;?> </td>
     </tr>
     <?php }?>
     <tr>
         <th> <label>From SVC</label> </th>
         <td> <?php echo $badparts_from_sc_id;?></td>
     </tr>
     <tr>
     	 <th> <label> To SVC</label> </th>
         <td><?php echo $badparts_to_sc_id ;?> </td>
     </tr>
      <tr>
        <th><label> Remarks</label></th>
        <td><textarea name="badparts_order_remarks" id="badparts_order_remarks" class="text-input" rows="4" cols="10"><?php echo $detailorders->badparts_order_remarks;?></textarea></td>
      </tr>
      
     <!-- <tr>
      <th><label>Order Created Date</label></th>
          <td> <label> <?php if ($detailorders->badparts_order_created_ts != '') {echo date('Y-m-d',strtotime($detailorders->badparts_order_created_ts)) ;} ?> </label></td>
      </tr>
     
      <tr>-->
      
      
      
      <?php if($detailorders->badparts_order_id>0){?>
      <tr>
      <th><label> <?php echo $this->lang->line('status')?></label></th>
          <td> <?php echo $order_status;?></td>
      </tr>
      <?php }?>
      <tr> 
        
      
      
      
      
      
      <td colspan="2">  <?php if( $detailorders->badparts_order_status !=4 ){?>  <input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" /> <?php }?>
      
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
        
           <?php if ($detailorders->badparts_from_sc_id == $this->session->userdata('sc_id') && $detailorders->badparts_order_status >=1){?> <input type="button" value="Create Chalan" name="btn_submit" id="btn_close" class="button" onclick="createchalan(<?php echo $detailorders->badparts_order_id;?>);" /><?php }?>
           
             <?php if ($detailorders->badparts_order_status >=1){?> <input type="button" value="Chalans" name="btn_submit" id="btn_close" class="button" onclick="showchalans(<?php echo $detailorders->badparts_order_id;?>);" /><?php }?>
      
      
      </td>
      
      </tr>
 </table> 
 
 </div>
 
 
<div style="float:left; width:65%">
    <?php if($detailorders->badparts_order_status <1){?>
    <table width="100%" cellpadding="0" cellspacing="0" class="tblgrid toolbar1">
      	<col width="20%" />
        <col width="20%" />
        <col width="15%"/>
        <col width="5%" />
      <tr>
        <td><label><?php echo $this->lang->line('part_number');?>:</label></td>
        <td><label><?php echo $this->lang->line('part_desc');?>:</label></td>
        <td><label><?php echo $this->lang->line('quantity');?>:</label></td>
        <td></td>
        <td></td>
        <td></td>
        
      </tr>
      <tr>
        <td><input type="hidden" name="hdnorder_part_id" id="hdnorder_part_id" value="0" class="text-input" />
          <input type="text" name="part_number" id="part_number" value="" class="text-input" /></td>
           <td><input type="text" readonly="readonly" name="part_desc" id="part_desc" value="" class="text-input"  /></td>
        <td><input type="text" name="part_quantity" id="part_quantity" value="" class="text-input" /></td>
            <td><a class="searchpart"><?php echo icon("search","Search","gif","icon");?></a></td>
        <td><input type="button" id="add_part" name="add_part" onclick="checkpart();" value="Add" class="button" /></td>
        <td><input type="button" id="cancel_part" name="cancel_part"  onclick= "cancelrow() " value="cancel" class="button" /></td>
       </tr>
    </table>
    <?php }?>
    <table width="100%" cellpadding="0" cellspacing="0"	class="tblgrid">
     <col width="1%" />
     <col width="15%"/>
     <col width="20%" />
     <col width="10%" />
     <col width="17%" />
     <col width="17%" />
     
	 <?php if($detailorders->badparts_order_status<1){?>
      <col width="7%" />
      <col width="7%" />
      <?php }?>
      <thead>
      <tr>
        	<td colspan="5" style="text-align:right"><!--<input type="button" name="partial_delivery" id="partial_delivery" value="Partial Delivery" class="button" onclick="getPartialDelevery();"  />--></td>
        </tr>
        <tr>
          <th><?php echo $this->lang->line('s.n');?></th>
          <th><?php echo $this->lang->line('part_number');?></th>
           <th><?php echo $this->lang->line('part_desc');?></th>
          <th style="text-align:center"><?php echo $this->lang->line('quantity')?></th>
          <th>Dispatched Quantity </th>
          <th>Delivered Quantity </th>
           <th></th>
           <?php if($detailorders->badparts_order_status<1){?>
          <th style="text-align:center"></th>
          <th style="text-align:center"></th>
          <?php }?>
       
       <th> </th> </tr>
      </thead>
      <tbody id="rowdata">
        <?php
	$i=1;
    foreach($orderparts as $details){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		
		?>
        <tr<?php echo $trstyle;?>>
          <td class="sn_td"><?php echo $i;?></td>
          <td><input type="hidden" id="" name="badparts_order_part_id[]" value="<?php echo $details->badparts_order_part_id;?>" />
            <input type="hidden" id="" name="pnum[]" value="<?php echo $details->part_number;?>" />
            <span class="lbl"><?php echo $details->part_number;?></span></td>
             <td style="text-align: left;"><input type="hidden" name="pdesc[]" value="<?php echo $details->part_desc;?>" class="text-input" /><span class="lbl"><?php echo $details->part_desc;?></span></td>
          <td style="text-align: center;"><input type="hidden" name="pqty[]" value="<?php echo $details->part_quantity;?>" class="text-input" /><span class="lbl"><?php echo $details->part_quantity;?></span></td>
          <td> </td>
          <td> </td>
         <?php if($detailorders->badparts_order_status<1){?>
       
            <td><a  title="Edit" class="editpart"><?php echo icon('edit','edit','png');?></a></td>
            <td><a  title="Delete" class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>
            <?php }?>
        </tr>
        <?php $i++;}?>
      </tbody>
    </table>
  </div>
  <input type="hidden" name="badparts_order_id" value="<?php echo $badpart_orderid;?>" />
</form>
<div style="clear:both"></div>