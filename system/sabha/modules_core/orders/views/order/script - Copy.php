<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
var sn;
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	$('a.editpart').live('click',function(event) {
		event.preventDefault();
		var ele = $(this).parent().parent();
		$("#add_part").val('Edit');
		$(this).parent().parent().parent().find('tr').removeClass('editactive');
		$(this).parent().parent().addClass('editactive');
		var order_part_id = ele.find('td:first').next().find('input:first').val();
		$("#hdnorder_part_id").val(order_part_id);
		var part_number = ele.find('td:first').next().find('input:last').val();
		var part_quantity = ele.find('td:first').next().next().find('input').val();
		$("#part_number").val(part_number);
		$("#part_quantity").val(part_quantity);
		$("#hdnorder_part_id").val(order_part_id);
		
	});
	$('a.deletepart').live('click',function(event) {
		if(confirm("Are you sure to delete this Part ?")){
			event.preventDefault();
			var ele = $(this).parent().parent();
			var order_part_id = ele.find('td:first').next().find('input:first').val();
			var params="order_part_id="+order_part_id+"&unq="+ajaxunq();
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>orders/deleteparts",
					data	:	params,
					success	:	function (data){
						$(ele).remove();
						hideloading(data);
						}								
				});//end  ajax
		}
		
	});
	$('a.partial_delivery').live('click',function(event) {
		event.preventDefault();
		var ele = $(this).parent().parent();
		var order_part_id = ele.find('td:first').next().find('input:first').val();
		var order_id = $("#order_id").val();
		var requesting_sc_id = $("#requesting_sc_id").val();
		var requested_sc_id = $("#requested_sc_id").val();
		
		var part_number = ele.find('td:first').next().find('input:last').val();
		
		$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/getpartialdeliverydetails', { requesting_sc_id:requesting_sc_id, requested_sc_id:requested_sc_id,order_id:order_id, part_number:part_number,order_part_id:order_part_id,unq:ajaxunq()}, 
			function(data) { $.facebox(data) });
		});
	});
});

function cancelform()
{
	$("#purchase_number").val('');
	$("#purchase_notes").val('');
	$("#part_number").val('');
	$("#part_description").val('');
	$('#part_quantity').val('') ;
	$('#part_rate').val('') ;
	$('#vendor_select').children().remove().end().append('<option selected value="">Select Model</option>') ;
	$("#hdnid").val(0);
	$("#purchaseForm").validationEngine('hideAll');
}

function closeform()
{
	window.location='<?php echo base_url();?>orders';
}
function addpart(){
	var part_number = $("#part_number").val();
	var part_quantity = $("#part_quantity").val();
	
	var order_part_id = $("#hdnorder_part_id").val();
	var status = 1;
	
	if(part_number==''){
		$('#part_number').validationEngine('showPrompt','* This field is required','error','topRight',true);
		status = 0;
	}
	if(part_quantity==''){
		$('#part_quantity').validationEngine('showPrompt','* This field is required','error','topRight',true);
		status = 0;
	}
	if(status==1){
		$("#orderForm").validationEngine('hideAll');
		
		sn = $("#rowdata tr:last td.sn_td").html();
		
		sn++;
		var html = '';
		var trclass = (sn%2==0)?'even':'odd';
		html+= '<td class="sn_td">'+sn+'</td>';
		html+='<td><input type="hidden" value="" id="" name="order_part_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
		html+='<td style="text-align: left;"><input type="hidden" name="pqty[]" value="'+part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
		
		html+='<td style="text-align:center"></td>';
		html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
		html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
		html='<tr class="'+trclass+'">'+html+'</tr>';
		var add=1;
		if(order_part_id==0){
			//add case
			$("#rowdata tr").each(function(index) {
				 var ptn = $(this).find('td:first').next().find('input:last').val();
				 if(ptn == part_number){
					 if(confirm('Item Number '+part_number+' already exists,Do you want to update it ?')){
						$(this).find('td:first').next().next().find('input').val(part_quantity);
						$(this).find('td:first').next().next().find('span.lbl').html(part_quantity);
						
					 }
					 add = 0;
				 }
			});
			if(add==1){
				$("#rowdata").append(html);
			}
		}else{
			var ele = $(".editactive");
			ele.find('td:first').next().find('input:last').val(part_number);
			ele.find('td:first').next().find('span.lbl').html(part_number);
			ele.find('td:first').next().next().find('input').val(part_quantity);
			ele.find('td:first').next().next().find('span.lbl').html(part_quantity);
			$(ele).parent().find('tr').removeClass('editactive');
		}
		$("#hdnorder_part_id").val(0);
		//$("#sno").val('');
		$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_description").val('');
		$("#part_rate").val('');
		$("#add_part").val('Add');
	}
}
function seeDetails(){
	var part_number = new Array();
	var part_quantity = new Array();
	var sc_id = $("#requested_sc_id").val();
	var i=0;
	$("#rowdata tr").each(function(index) {
		part_number[i] = $(this).find('td:first').next().find('input:last').val();
		part_quantity[i] = $(this).find('td:first').next().next().find('input').val();
		i++;
	});
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>orders/getstockdeliverydetails', { part_number:part_number,part_quantity:part_quantity,sc_id:sc_id,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function getPartialDelevery(){
	var part_number = new Array();
	var part_quantity = new Array();
	var sc_id = $("#requested_sc_id").val();
	var order_id = $('#order_id').val();
	var requesting_sc_id = $("#requesting_sc_id").val();
	var i=0;
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>orders/getpartialdeliverydetails', { requesting_sc_id:requesting_sc_id, order_id:order_id, part_number:part_number,part_quantity:part_quantity,sc_id:sc_id,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function saveOrderPartsDetails(){
	var order_part_details_id =  new Array();
	var part_request_qty = new Array();
	var order_part_status = new Array();
	var part_number = $("#pp_number").val();
	var request_quantity = new Array();
	var dispatched_qty = new Array();
	var requesting_sc_id = $("#requesting_sc_id").val();
	var requested_sc_id = $("#requested_sc_id").val();
	
	var i =0;
	
	$("#parts_orders .request_quantity").each(function() {
		request_quantity+= 'request_quantity['+i+']='+$(this).val()+'&';
		i++;
	});
	
	i =0;
	var delivered_checkbox = new Array();
	$("#parts_orders .delivered_checkbox").each(function() {
		var isChecked = $(this).attr('checked')?1:0;
		delivered_checkbox+= 'delivered_checkbox['+i+']='+isChecked+'&';
		i++;
	});
	
	i=0;
	$("#parts_orders .order_part_details_id").each(function() {
		var isChecked = $(this).attr('checked')?1:0;
		order_part_details_id+= 'order_part_details_id['+i+']='+$(this).val()+'&';
		i++;
	});
	var i =0;
	
	$("#parts_orders .dispatched_qty").each(function() {
		dispatched_qty+= 'dispatched_qty['+i+']='+$(this).val()+'&';
		i++;
	});
	
	var order_part_id = $("#pp_order_part_id").val();
	var params=order_part_details_id+'&'+delivered_checkbox+'&'+'part_number='+part_number+'&'+request_quantity+'&'+'requested_sc_id='+requested_sc_id+'&requesting_sc_id='+requesting_sc_id+'&order_part_id='+order_part_id+"&unq="+ajaxunq();
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>orders/savedeliverypartdetails",
					data	:	params,
					success	:	function (data){
						$(document).trigger('close.facebox');
						hideloading(data);
						}								
				});//end  ajax
}
</script>