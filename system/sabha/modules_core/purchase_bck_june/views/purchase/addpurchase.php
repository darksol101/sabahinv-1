<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
table td img.ui-datepicker-trigger { padding-left: 5px;}
.editpart{cursor:pointer;}
.deletepart{cursor:pointer;}
.editactive{background: none repeat scroll 0 0 #FFF5E7;}
.ui-autocomplete {max-height: 200px;overflow-y: auto;overflow-x: hidden;padding-right: 20px;}
* html .ui-autocomplete {height: 200px;}
.toolbar1 input[type="text"] {width:100%!important;}
table.toolbar1 td{ padding:0px 2px;!important;}
form table.toolbar1 label{ padding:0px!important ;}
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
		
		var appendTo = $( ".selector" ).autocomplete( "option", "appendTo" );
		$( "#part_number" ).autocomplete({			 
			source: "<?php echo site_url();?>purchase/getjsonparts",
			minLength: 2,
			delay: 100,
			select: function( event, ui ) {
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
			change: function(event){
				
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
	});
</script>

<script language="javascript">
$(document).ready(function(){
	$("#part_number").val('');
	$('#part_description').val('');
	$("#cancel_part").hide() ;
	$("#purchaseForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){  document.purchaseForm.submit();}}  
	});	
	
	
	
	
});

</script>


<?php $this->load->view('dashboard/system_messages');?>
<div style="clear:both"></div>
<div id="purchaseContent">
<form action="" method="post" name="purchaseForm" id="purchaseForm">
<div style="float:left; width:30%">
<table width="100%">
	<col width="35%" /><col />
    
  <?php if ($purchase->purchase_id != '') {?>
    <tr>
    	<th><label><?php echo $this->lang->line('purchase_number'); ?>: </label></th>
        <td><label><?php echo $purchase->purchase_id;?></label> </td>
    </tr> 
    <?php  }?>
    <tr>

    
    	<th><label><?php echo $this->lang->line('invoice_number'); ?>: </label></th>
        <td><input type="text" value="<?php echo $purchase->invoice_number;?>" id="invoice_number" name="invoice_number" class="validate[required] text-input" /></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('vendor_name'); ?>:</label></th>
          <td><?php echo $vendor_select;?></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('add_to_svc'); ?>:</label></th>
          <td><?php echo $scenters;?></td>
    </tr>
    <tr>
    <th><label><?php echo $this->lang->line('purchase_date');?>:</label></th>
    	<td><input type="text" readonly="readonly" name="purchase_date" id="purchase_date" class="text-input datepicker" value="<?php echo format_date(strtotime($purchase->purchase_date));?>" style="width: 70%" /></td>
    </tr>
    <tr>
		<th><label><?php echo $this->lang->line('purchase_notes'); ?>:</label></th>
		<td><textarea name="purchase_notes" id="purchase_notes" class="" rows="4" cols="10"><?php echo $purchase->purchase_notes;?></textarea></td>
	</tr>
    <tr style="display:none;">
		<th><label>PP number:</label></th>
		<td><input type="text" id="pp_number" name="pp_number" class="validate[required] text-input" value="<?php echo $purchase->pp_number;?>" /></td>
	</tr>
    <tr style="display:none;">
		<th><label>LC Number:</label></th>
		<td><input type="text" name="lc_number" id="lc_number" class="validate[required] text-input" value="<?php echo $purchase->lc_number; ?>"  /></td>
	</tr>
     <tr>
    <th><label>PP Date:</label></th>
    	<td><input type="text" readonly="readonly" name="pp_date" id="pp_date" class="text-input datepicker" value="<?php echo format_date(strtotime($purchase->pp_date));?>" style="width: 70%" /></td>
    </tr>
    <?php if($purchase->purchase_id>0 ){
		
		if ($this->session->userdata('usergroup_id') == 1 or $this->session->userdata('usergroup_id')== 4 or $this->session->userdata('usergroup_id')== 6){?>
		
    
    <tr>
    	<th><label><?php echo $this->lang->line('confirm_delivery'); ?>:</label></th>
        <td><input name="deliver_status" type="checkbox" id="deliver_status"  <?php if($purchase->purchase_status==1){?> checked="checked" <?php }?>/></td>
    </tr>
     <?php }}?>
    <tr>
        <td colspan="2"><?php if($purchase->purchase_status < 1){?><input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
      <?php }?>
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
       
        </td>
    </tr>
</table>
</div>

<div style="float:left; width:65%">
<?php if($purchase->purchase_status<1){?>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid toolbar1">
	<col width="20%" />
    <col width="20%" />
    <col width="5%" />
    <col width="15%" />
    <col width="5%" />
    <col width="1%" />
	<tr>		
		<td valign="top"><label><?php echo $this->lang->line('part_no');?>:</label></td>
        <td><label><?php echo $this->lang->line('part_description');?>:</label></td>
    	<td><label><?php echo $this->lang->line('part_quantity');?>:</label></td>
    		<td><label><?php echo $this->lang->line('company');?>:</label>
        <td></td>
	</tr>
	<tr>
        <td><input type="hidden" name="hdnpurchase_details_id" id="hdnpurchase_details_id" value="0" class="text-input" /><input type="text" name="part_number" id="part_number" value="" class="text-input" /></td>
        
        <td><input type="text" name="part_description" id="part_description" value="" class="text-input"   /></td>
        
        <td><input type="text" name="part_quantity" id="part_quantity" value="" class="text-input validate[custom[onlyNumberSp]]"  /></td>
        
          <td><?php echo $company_options;?></td>
       
        <td><input type="button" id="add_part" name="add_part" onclick="checkpart();" value="Add" class="button" /></td><td><input type="button" id="cancel_part" name="cancel_part"  onclick= "cancelrow() " value="cancel" class="button" />
        </td>
        
	</tr>
    <input type="hidden" name="serial" id="serial" value=""/>
</table>
<?php }?>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="1%" /><col /><col width="20%" /><col width="10%" /><col width="20%" /><?php if($purchase->purchase_status<2){?><col width="5%" /><col width="1%" /><?php }?>
    <thead>
    <tr>
		<th>Sno</th>  
        <th><?php echo $this->lang->line('part_no');?></th>
         <th><?php echo $this->lang->line('part_description');?></th>
       <th><?php echo $this->lang->line('part_quantity');?></th>
       <th><?php echo $this->lang->line('company');?></th>
    	<th></th>
        <?php if($purchase->purchase_status < 2){?>
            <th></th>
        <th></th><?php
        }?>
    </tr>
</thead>
    <tbody id="rowdata">
    <?php 
	
	$i=1;
    foreach($purchasedetails as $details){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr<?php echo $trstyle;?>>
			<td class="sn_td"><?php echo $i;?></td>
			<td><input type="hidden" id="" name="purchase_details_id[]" value="<?php echo $details->purchase_details_id;?>" /><input type="hidden" id="" name="pnum[]" value="<?php echo $details->part_number;?>" /><span class="lbl"><?php echo $details->part_number;?></span></td>
            
            <td style="text-align: left;"><input type="hidden" name="pdesc[]" value="<?php echo $details->part_description;?>" class="text-input" /><span class="lbl"><?php echo $details->part_description;?></span></td>
            
			<td style="text-align: left;"><input type="hidden" name="pqty[]" value="<?php echo $details->part_quantity;?>" class="text-input" /><span class="lbl"><?php echo $details->part_quantity;?></span></td>
            
            <td style="text-align: left;"><input type="hidden" name="comp[]" value="<?php echo $details->company_title;?>" class="text-input" /><span class="lbl"><?php echo $details->company_title;?></span></td>
           <td></td>
            <?php if($purchase->purchase_status<1){?><td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>
            <td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td><?php }?>
           
        </tr>
        <?php $i++;}?>
    </tbody>
    <tfoot> 
        <tr>
       
        	<td colspan="8" style=" padding-top:150px" >
            	 <?php if ($this->session->userdata('usergroup_id') == 1 && $purchase->purchase_status < 1  ){?>
        <input style="float:right" type="button" id="upload" name="upload" onclick="uploadexcel();"  value="Upload" class="button"/>
		<input style="float:right" type="button" id="download" name="download" onclick="downloadtemplate();" value="Template Download" class="button" />
		<?php }?>
            </td>
        </tr>
    </tfoot>
</table>
</div>
<div style="clear:both"></div>

<input type="hidden" name="purchase_id" id="purchase_id" value="<?php echo $purchase->purchase_id;?>" /> 

<div class="clear">&nbsp;</div>
</form>
<?php //onKeyup="isInteger(this.value)" ?>
</div>