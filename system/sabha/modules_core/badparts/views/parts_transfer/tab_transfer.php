<?php $this->load->view('dashboard/system_messages');?>
<style type="text/css">
#from_sc_box, #parts_box {
	position: relative;
}
#from_sc_box .loading, #parts_box .loading {
	position: absolute;
	left: 0px;
	top: 0;
	width: 100%;
	height: 30px;
	margin: 0 auto;
	text-align: center;
}
table td img.ui-datepicker-trigger {
	padding-left: 5px;
}
.ui-autocomplete {
	max-height: 200px;
	overflow-y: auto;
	overflow-x: hidden;
	padding-right: 20px;
}
* html .ui-autocomplete {
	height: 200px;
}
.editpart, .deletepart, infobtn {
	cursor:pointer;
}
.editactive {
	background: none repeat scroll 0 0 #FFF5E7;
}
a.infobtn, a.infobtn:hover {
	cursor: pointer;
	color:#F00
}
a.close {
	visibility:visible;
}
</style>
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
<script type="text/javascript">
	$(function() {
		function log( message ) {
			$( "<div/>" ).text( message ).prependTo( "#log" );
			$( "#log" ).scrollTop( 0 );
		}
		var appendTo = $( ".selector" ).autocomplete( "option" );
		$( "#part_number" ).autocomplete({			 
			source: "<?php echo site_url();?>badparts/transfer/getjsonparts?sc_id="+$("#from_sc_id").val(),
			minLength: 1,
			delay: 300,
			
			select: function( event, ui ) {
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			focus: function(event, ui) {
				$("#part_number").val(ui.item.label);
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
	});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $("#orderForm").validationEngine('attach', {
      onValidationComplete: function(form, status){ if(status==true){ document.orderForm.submit();}}  
    });
})
</script>
<form method="post" name="orderForm" id="orderForm">
  <div style="width:30%;  float:left">
    <table width="100%" cellpadding="0" cellspacing="0" class="">
      <col width="24%" /><col />
      <tr>
        <th><label><?php echo $this->lang->line('from_svc');?></label></th>
        <td><?php echo $servicecenter_select_from;?></td>
      </tr>
      <tr>
        <th><label><?php echo $this->lang->line('to_svc');?></label></th>
        <td><span id="from_sc_box"><?php echo $servicecenter_select_to;?></span></td>
      </tr>
      <tr>
        <th><label><?php echo $this->lang->line('status');?></label></th>
        <td><span id="from_sc_box"><?php echo $status_select;?></span></td>
      </tr>
      <tr>
        <td colspan="2">
        	<input type="submit" value="<?php echo $this->lang->line('transfer'); ?>" name="btn_submit" id="btn_submit" class="button" />
          <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
          <input type="button" value="Create Chalan" name="btn_create_chalan" id="btn_create_chalan" class="button" onclick="createChalan();"/>
          </td>
      </tr>
    </table>
  </div>
  <div style="width:63%; float:left">
    <table width="70%" class="toolbar1">
    <col /><col width="20%" /><col width="10%" />
      <thead>
        <tr>
          <td><label>Item Number</label></td>
          <td><label>Quantity</label></td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td><input type="text" class="text-input part_number" value="" id="part_number" name="part_number"></td>
            <td><input type="text" class="text-input part_quantity" value="" id="part_quantity" name="part_quantity"></td>
          <td><input type="button" name="add" value="Add"  class="button" onclick="checkParts();" /></td>
        </tr>
      </tbody>
    </table>
    <table class="tblgrid" style="width:100%">
        <col /><col width="20%" />
      <thead>
      <tr>
          <th>Item Number</th>
          <th style="text-align: center">Part Quantity</th>
          </tr>
      </thead>
      <tbody id="tbdata">
      	<?php
		$i=1;
        foreach($return_sc_details_list as $parts){
			$trstyle=$i%2==0?" class='even' ": " class='odd' ";
			?>
        	<tr<?php echo $trstyle;?>>
            	<td><input type="hidden" name="return_sc_detail_id[]" value="<?php echo $parts->return_sc_detail_id;?>" /><input type="hidden" name="part_numbers[]" value="<?php echo $parts->part_number;?>" class="part_number" /><?php echo $parts->part_number;?></td>
                <td style="text-align:center"><?php echo $parts->part_quantity;?><input type="hidden" name="part_quantities[]" value="<?php echo $parts->part_quantity;?>" class="part_quantity" /></td>
            </tr>
        <?php $i++; }?>
      </tbody>        
    </table>
  </div>
<input type="hidden" name="return_sc_id" id="return_sc_id" value="<?php echo $return_sc_details->return_sc_id;?>" />  
</form>
<span class="message"><span class="message_text"></span></span><span style="display: none" class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
<div></div>
<div style="clear:both"></div>
