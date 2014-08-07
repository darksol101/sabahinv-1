<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
table td img.ui-datepicker-trigger {padding-left: 5px;}
.ui-autocomplete {max-height: 200px;overflow-y: auto;overflow-x: hidden;padding-right: 20px;}
* html .ui-autocomplete {height: 200px;}
.editpart,.deletepart,infobtn {	cursor:pointer;}
.editactive {background: none repeat scroll 0 0 #FFF5E7;}
a.infobtn,a.infobtn:hover{ cursor: pointer; color:#F00}
a.close{ visibility:visible;}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet"	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js"	type="text/javascript"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js"	type="text/javascript"></script>
<script type="text/javascript">

$(function() {
		$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
		});
	});


	$(function() {
		function log( message ) {
			$( "<div/>" ).text( message ).prependTo( "#log" );
			$( "#log" ).scrollTop( 0 );
		}
		//var appendTo = $( ".selector" ).autocomplete( "option" );
		$( "#part_number" ).autocomplete({			 
			source: "<?php echo site_url();?>purchase/getjsonparts?pnum="+$("#part_number").val(),
			minLength: 2,
			delay: 300,
			
			select: function( event, ui ) {
				$("#model_number").val(ui.item.model_number);
				$("#part_number").val(ui.item.label);
				$('#part_description').val(ui.item.pdesc);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			focus: function(event, ui) {
				$("#part_number").val(ui.item.label);
				$('#part_description').val(ui.item.pdesc);
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});


		$( "#model_number" ).autocomplete({			 
			source: "<?php echo site_url();?>purchase/getjsonpartsmodel?pnum="+$("#model_number").val(),
			minLength: 2,
			delay: 300,
			
			select: function( event, ui ) {
				//$("#model_number").val(ui.item.model_number);
				$("#part_number").val(ui.item.value1);
				$('#part_description').val(ui.item.pdesc);
				$("#model_number").val(ui.item.model_number);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			focus: function(event, ui) {
				$("#part_number").val(ui.item.label);
				$('#part_description').val(ui.item.pdesc);
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});




		
		
	});
</script>
<script language="javascript">
$(document).ready(function(){
   $('#part_number').keyup(function(e) {
    e.preventDefault();
        this.value = this.value.replace(' ','');
        this.value = this.value.toLocaleUpperCase();
    });


	$("#cancel_part").hide() ;
	$("#orderForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){
      var val = checkStore();
      if(val==1) {
        var checkEmpty = $("#rowdata tr td:nth-child(2) input:nth-child(2)").val();
       

        if(checkEmpty){
          document.orderForm.submit();
        }
        else{
          alert("No Item Selected to be Orderd");
          return false;
        }
      }

      }}  
	});
});


function checkStore () {
      var var1=$("#requested_sc_id").val();
      var var2=$("#requesting_sc_id").val();
      var toAdd=1;
      if(var1==var2){
        $("#requesting_sc_id").validationEngine('showPrompt', '*cannot order to same store', 'error', 'topRight', true);
        toAdd=0;
      }
      return toAdd;
}
</script>
<?php $this->load->view('dashboard/system_messages');?>
<div>

<form action="" method="post" name="orderForm" id="orderForm" onchange="checkStore()">
  <div >
    <table width="75%" cellpadding="0" cellspacing="0" class="">
      
      <?php if (!empty($result->order_number)) {?>
      <tr>
      		<th > <label> <?php echo $this->lang->line('order_number');?> </label> </th>
            <td > <?php echo $result->order_number; ?></td>
            <th> &nbsp;</th>
            <td> &nbsp; </td>
     </tr>
      <?php }?>
      <tr>
        <th><label> <?php echo $this->lang->line('from_svc')?></label></th>
        <td><?php echo $requested_sc_id ;?></td>
        
        
        <th><label> <?php echo $this->lang->line('type')?></label></th>
          <td> <?php if ($result->order_status == 0 ){ echo $order_type; }else{ echo $this->mdl_mcb_data->getStatusDetails($result->order_type,'order_type');?><input type="hidden" name="order_type" value="<?php echo $result->order_type;?>" /><?php }?></td>
        
        
      </tr>
      <tr>
        <th><label> <?php echo $this->lang->line('to_svc')?></label></th>
        <td><?php echo $requesting_sc_id ;?></td>
          <th><label> <?php echo $this->lang->line('ordered_date')?></label></th>
          <td> <label> <?php if ($result->order_created_ts != '') {echo date('Y-m-d',strtotime($result->order_created_ts)) ;} ?> </label></td>
        
      </tr>
     
           <tr>
        <th><label> <?php echo $this->lang->line('remarks')?></label></th>
        <td><textarea name="order_remarks" id="order_remarks" class="text-input" rows="3" cols="10"><?php echo $result->order_remarks;?></textarea></td>
        
          <?php if($result->order_id>0){?>
      
      <th><label> <?php echo $this->lang->line('status')?></label></th>
        <?php /*?><td><input type="checkbox" value="1" name="status" <?php if( $result->order_status==1){?> checked="checked" <?php }?> class="status"/>
          Delivered </td><?php */?>
          <td> <?php echo $order_status;?></td>
      <?php }?>
        
      </tr>
      
     
     
      <tr>
        <td colspan="2">
        <?php if($result->order_status != 5 && $result->order_status !=4 && $result->order_status !=3 ){?> 
        <input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
        <?php }?>
          <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
          <?php /*if ($result->order_status >= 2){?>
           <input type="button" value="<?php echo $this->lang->line('Print'); ?>" name="btn_submit" id="btn_close" class="button" onclick="printorder(<?php $result->order_id;?>);" />
           <?php }*/?>
           
          <?php if ($result->requested_sc_id == $this->session->userdata('sc_id') && $result->order_status >=1){?> <input type="button" value="<?php echo $this->lang->line('create_chalan'); ?>" name="btn_submit" id="btn_close" class="button" onclick="createchalan(<?php echo $result->order_id;?>);" /><?php }?>
           
             <?php if ($result->order_status >=1){?> <input type="button" value="Chalans" name="btn_submit" id="btn_close" class="button" onclick="showchalans(<?php echo $result->order_id;?>);" /><?php }?>
             
             <?php  if ($result->requested_sc_id == $this->session->userdata('sc_id')){?>
             <input type="button" id="picking_list" class="button" onclick="getPickingList(<?php  echo $this->uri->segment(3);?>);" value="Picking List" /> 
			 <?php }?>
          </td>
      </tr>
    </table>
  </div>
  <div>
    <?php if($result->order_status <1){?>
    <table width="100%" cellpadding="0" cellspacing="0" class="tblgrid toolbar1">
      <col width="20%" />	<col width="20%" /><col width="20%" /><col width="5%" /><col width="15%"/><col width="5%" /><col width="5%" />
      <tr>
       <td><label>Model Number:</label></td>
        <td><label><?php echo $this->lang->line('partnumber');?>:</label></td>
        <td><label><?php echo $this->lang->line('part_description');?>:</label></td>
        <td><label><?php echo $this->lang->line('quantity');?>:</label></td>
        <td style="display:none;"><label><?php echo $this->lang->line('company');?>:</label></td>
        
        <td></td>
        <td></td>
        
      </tr>
      <tr>
      <td> <input type="text" name="model_number" id="model_number" value="" class="text-input" /></td> </td>
        <td><input type="hidden" name="hdnorder_part_id" id="hdnorder_part_id" value="0" class="text-input" />
          <input type="text" name="part_number" id="part_number" value="" class="text-input" /></td>
           <td><input type="text" readonly="readonly" name="part_description" id="part_description" value="" class="text-input"  /></td>
        <td><input type="text" name="part_quantity" id="part_quantity" value="" class="text-input" /></td>
        <!-- <td><?php //echo $company_name ;?></td> -->
         <td><a class="searchpart"><?php echo icon("search","Search","gif","icon");?></a></td>
        <td><input type="button" id="add_part" name="add_part" onclick="checkpart();" value="Add" class="button" /></td>
        <td><input type="button" id="cancel_part" name="cancel_part"  onclick= "cancelrow() " value="cancel" class="button" /></td>
       <td></td>
       </tr>
    </table>
    <?php }?>
    <table width="100%" cellpadding="0" cellspacing="0"	class="tblgrid">
     <col width="1%" /><col width="15%"/><col width="20%" /><col width="10%" /><col width="15%"/><col width="20%" />
       <?php if($result->order_status<1){?>
     
      <col width="7%" />
      <col width="7%" />
      
      <?php }?>
      <thead>
      <tr>
        	<td colspan="5" style="text-align:right"><!--<input type="button" name="partial_delivery" id="partial_delivery" value="Partial Delivery" class="button" onclick="getPartialDelevery();"  />--></td>
        </tr>
        <tr>
          <th><?php echo $this->lang->line('s.n');?></th>
          <th><?php echo $this->lang->line('partnumber');?></th>
          <th><?php echo $this->lang->line('part_description');?></th>
          <th><?php echo $this->lang->line('quantity')?></th>
          <th style="text-align:center;display:none; "><?php echo $this->lang->line('company_name')?></th>
          <!--  <th style="text-align:center"><?php echo $this->lang->line('call_uids')?></th> -->
           <th>Dispatched quantity</th>
           <th>Delivered quantity</th>
           <th>Backordered Quantity</th>
           <th></th>
           <?php if($result->order_status<1){?>
          
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
		$dispatched_quantity = $this->mdl_order_parts_details->getDispatchedQuantity($details->order_part_id);
		$delivered_quantity = $this->mdl_order_parts_details->getDeliveredQuantity($details->order_part_id);
		?>
          <tr<?php echo $trstyle;?>>

          <td class="sn_td"><?php echo $i;?></td>
          
          <td><input type="hidden" id="" name="order_part_id[]" value="<?php echo $details->order_part_id;?>" />
            <input type="hidden" id="" name="pnum[]" value="<?php echo $details->part_number;?>" />
            <span class="lbl"><?php echo $details->part_number;?></span>
          </td>
          
          <td style="text-align: left;"><input type="hidden" name="pdesc[]" value="<?php echo $details->part_description;?>" class="text-input" /><span class="lbl"><?php echo $details->part_description;?></span>
          </td>
       
          <td style="text-align: left;"><input type="hidden" name="pqty[]" value="<?php echo $details->part_quantity;?>" class="text-input" /><span class="lbl"><?php echo $details->part_quantity;?></span>
          </td>
          
        <td style="text-align: center; display:none; "><input type="hidden" name="comp[]" value="<?php echo $details->company_title;?>" class="text-input" /><span class="lbl"><?php echo $details->company_title;?></span></td>
          
           <!-- <td style="text-align: center;"><input type="hidden" name="call[]" value="<?php echo $details->call_uid;?>" class="text-input" /><span class="lbl"><?php echo $details->call_uid;?></span>
           </td> -->
           
           <td><?php echo $dispatched_quantity->dispatched_quantity; ?> </td>
           
           <td><?php echo $delivered_quantity->delivered_quantity;?> </td>
           
           <td><?php if ( $dispatched_quantity->dispatched_quantity - $delivered_quantity->delivered_quantity > 0){echo  $dispatched_quantity->dispatched_quantity - $delivered_quantity->delivered_quantity;}?>

           </td>
            <?php if($result->order_status<1){?>
            <?php /*?>   <td><a title="Partial Delivery" class="btn partial_delivery">Partial Delivery</a></td> <?php */?>
            <td><a  title="Edit" class="editpart"><?php echo icon('edit','edit','png');?></a></td>
            <td><a  title="Delete" class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>
            <?php }?>
        </tr>
        <?php $i++;}?>
      </tbody>
    </table>
  </div>
  <input type="hidden" name="order_id" id="order_id" value="<?php echo $result->order_id;?>" />
 
</form>
<div style="clear:both"></div>
</div>
