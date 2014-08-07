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
		$("#cancel_part").show();
		$(this).parent().parent().parent().find('tr').removeClass('editactive');
		$(this).parent().parent().addClass('editactive');
		var order_part_id = ele.find('td:first').next().find('input:first').val();
		$("#hdnorder_part_id").val(order_part_id);
		var part_number = ele.find('td:first').next().find('input:last').val();
		var part_description = ele.find('td:first').next().next().find('input').val();
		var part_quantity = ele.find('td:first').next().next().next().find('input').val();
		var company = ele.find('td:first').next().next().next().next().find('input').val();
		//alert (company);
		//alert(part_number+'--'+part_quantity+'--'+part_rate) 
// 
		$("#part_number").val(part_number);
		$("#part_description").val(part_description);
		$("#part_quantity").val(part_quantity);
		$("#hdnorder_part_id").val(order_part_id);
		$("#company_name").val(company);
		
	});
	$('a.deletepart').live('click',function(event) {
		if(confirm("Do you want to delete this Part ?")){
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
	
	
	
	$('a.searchpart').live('click',function(event) {
		
			event.preventDefault();
			$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/getorderpartbymodel', 
			function(data) { $.facebox(data) });
		});
		
	});
	
	
	
	$('a.setorderpart').live('click',function(event) {
			event.preventDefault();
			//alert('m here');
			var ele1 = $(this).parent().parent();
			var part_number =ele1.find('td:first').next().find('a:first').html();
			var part_description =ele1.find('td:first').next().next().html();
			
			
				$('#part_description').val(part_description);		
				$('#part_number').val(part_number);
				$(document).trigger('close.facebox');
			
	});
	
	
	
	
	$('a.confirm_part').live('click',function(event) {
			event.preventDefault();
			var requesting_sc_id = $('#hdn_requesting_sc_id').val();
			var requested_sc_id = $('#hdn_requested_sc_id').val();
			var transit_id =   $('#transit_detail_id').val();
			if (transit_id ==''){alert ('Please Enter Transit Details'); return false;}
			var ele1 = $(this).parent().parent();
			var order_part_id =ele1.find('td:first').next().find('input:first').val();
			var part_number =ele1.find('td:first').next().next().find('input:first').val();
			var company =ele1.find('td:first').next().next().next().find('input:first').val();
			var remaining_quantity =ele1.find('td:first').next().next().next().next().find('input:first').val();
			var quantity =ele1.find('td:first').next().next().next().next().next().next().next().find('input:first').val();
			var transit_detail_id = $('#transit_detail_id').val();

			//alert (company);
			//return false;
			
			if (quantity =='' || isNaN (quantity) == true || quantity < 1 || quantity.toString().indexOf('.') != -1){return false; 
			
			ele1.find('td:first').next().next().next().next().next().next().next().next().find('input:first').val('');
			
			}
			
			
			if ( parseInt (quantity) > parseInt (remaining_quantity))
				{
					alert('excess quantity');
					return false;
				}
			var params="order_part_id="+order_part_id+"&part_number="+part_number+'&quantity='+quantity+'&requested_sc_id='+requested_sc_id+'&company='+company; 
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>orders/checkstock",
					data	:	params,
					success	:	function (data){
						if ( parseInt(quantity) > parseInt(data)){
							alert ('not enough quantity');
							}
							else {//alert ('Item Dispatched successfully');
							ele1.find('td:first').next().next().next().next().next().next().next().find('input:first').attr("disabled",true);
							ele1.find('td:first').next().next().next().next().next().next().next().next().find('a:first').hide();
							savepartialparts(order_part_id,quantity,requested_sc_id,part_number,transit_id,company);
							}
						//hideloading(data);
						}								
				});
				//$(document).trigger('close.facebox');
			
	});
	
	
	
	$('a.receive_partial_part').live('click',function(event) {
			var r=confirm("Receive Part");
			if (r==true)
  			{
  										  
			event.preventDefault();
			var requesting_sc_id = $('#hdn_requesting_sc_id').val();
			var requested_sc_id = $('#hdn_requested_sc_id').val();
			var ele1 = $(this).parent().parent();
			var part_number =ele1.find('td:first').next().next().find('input:first').val();
			var company =ele1.find('td:first').next().next().next().find('input:first').val();
			var order_part_detail_id =ele1.find('td:first').next().next().next().next().next().next().next().find('input:first').val();
			var dispatched_chalan_quantity = ele1.find('td:first').next().next().next().next().next().next().find('input:first').val();
			var dispatched_entered_quantity = ele1.find('td:first').next().next().next().next().next().next().next().next().next().find('input:first').val();
			var order_part_id = ele1.find('td:first').next().find('input:first').val();
		//alert(dispatched_chalan_quantity);
		//alert (company);return false;
			if (dispatched_chalan_quantity =='' || isNaN (dispatched_entered_quantity) == true || dispatched_entered_quantity < 1 || dispatched_entered_quantity.toString().indexOf('.') != -1){return false; 
			
			ele1.find('td:first').next().next().next().next().next().next().next().find('input:first').val('');
			
			}
			
			var params="order_part_detail_id="+order_part_detail_id+"&part_number="+part_number+'&dispatched_chalan_quantity='+dispatched_chalan_quantity+'&requested_sc_id='+requested_sc_id+'&requesting_sc_id='+requesting_sc_id+'&dispatched_entered_quantity='+dispatched_entered_quantity+'&order_part_id='+order_part_id+'&company='+company; 
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>orders/enterpartialdelivery",
					data	:	params,
					success	:	function (data){
						if (data == 1){alert('Entered quantity is more than remaining quantity');}
						else if(data == 2){ alert('Entered Quantity is more than dispatched quantity');} 
						else{
						ele1.find('td:first').next().next().next().next().next().next().next().next().next().find('input:first').attr("disabled",true);
						ele1.find('td:first').next().next().next().next().next().next().next().next().next().next().find('a:first').hide();
						}
						
						}								
				});
		}		//$(document).trigger('close.facebox');
			
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
	$('#company_name').val('');
	$('#vendor_select').children().remove().end().append('<option selected value="">Select Model</option>') ;
	$("#hdnid").val(0);
	$("#purchaseForm").validationEngine('hideAll');
}

function closeform()
{
	window.location='<?php echo base_url();?>orders';
}
function addpart(){
	$("#cancel_part").hide();
	var part_number = $("#part_number").val();
	var part_quantity = $("#part_quantity").val();
	var company = $("#company_name").val();
	var company = 'Default';
	var part_description = $("#part_description").val();
	var order_part_id = $("#hdnorder_part_id").val();
	var status = 1;
	
	if(status==1){
		$("#orderForm").validationEngine('hideAll');
		
		sn = $("#rowdata tr:last td.sn_td").html();
		
		sn++;
		var html = '';
		var trclass = (sn%2==0)?'even':'odd';
		html+= '<td class="sn_td">'+sn+'</td>';
		html+='<td><input type="hidden" value="" id="" name="order_part_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
	html+='<td style="text-align: left;"><input type="hidden" name="pdesc[]" value="'+part_description+'" class="text-input" /><span class="lbl">'+part_description+'</span></td>';
		html+='<td style="text-align: left;"><input type="hidden" name="pqty[]" value="'+part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
		
		html+='<td style="text-align: left;display:none;"><input type="hidden" name="comp[]" value="'+company+'" class="text-input" /><span class="lbl">'+company+'</span></td>';
		
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
		html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
		html='<tr class="'+trclass+'">'+html+'</tr>';
		var add=1;
		if(order_part_id==0){
			//add case
			$("#rowdata tr").each(function(index) {
				 var ptn = $(this).find('td:first').next().find('input:last').val();
				 var comp = $(this).find('td:first').next().next().next().next().find('input:last').val();
				 if(ptn == part_number && comp == company){
					 if(confirm('Item Number '+part_number+' already exists. Do you want to update it?')){
						$(this).find('td:first').next().next().next().find('input').val(part_quantity);
						$(this).find('td:first').next().next().next().find('span.lbl').html(part_quantity);
						$(this).find('td:first').next().next().find('input').val(part_description);
						$(this).find('td:first').next().next().find('span.lbl').html(part_description);
						$(this).find('td:first').next().next().next().next().find('input').val(company);
						$(this).find('td:first').next().next().next().next().find('span.lbl').html(company);
											 }
					 add = 0;
				 }
			});
			if(add==1){
				$("#rowdata").append(html);
			}
		}else{
			var ele = $(".editactive");
			//alert(company);
			ele.find('td:first').next().next().next().find('input').val(part_quantity);
			ele.find('td:first').next().next().next().find('span.lbl').html(part_quantity);
			ele.find('td:first').next().next().next().next().find('input').val(company);
			ele.find('td:first').next().next().next().next().find('span.lbl').html(company);
			ele.find('td:first').next().next().find('input').val(part_description);
			ele.find('td:first').next().next().find('span.lbl').html(part_description);
			//$(this).find('td:first').next().next().next().next().find('input').val(company);
			//$(this).find('td:first').next().next().next().next().find('span.lbl').html(company);
			//alert(company);
			$(ele).parent().find('tr').removeClass('editactive');
		}
		$("#hdnorder_part_id").val(0);
		//$("#sno").val('');
		$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_description").val('');
		$("#part_rate").val('');
		$("#company_name").val('');
		$("#add_part").val('Add');
	}
}
function seeDetails(){
	var part_number = new Array();
	var part_quantity = new Array();
	var company_id = new Array();
	var sc_id = $("#requested_sc_id").val();
	var i=0;
	$("#rowdata tr").each(function(index) {
		part_number[i] = $(this).find('td:first').next().find('input:last').val();
		part_quantity[i] = $(this).find('td:first').next().next().next().find('input').val();
		company_id[i] = $(this).find('td:first').next().next().next().next().find('input').val();
		//alert(company_id[i]);
		i++;
	});
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>orders/getstockdeliverydetails', { part_number:part_number,part_quantity:part_quantity,company_id:company_id,sc_id:sc_id,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function cancelrow()
{
	$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_description").val('');
		$("#cancel_part").hide();
		$("#add_part").val('Add');
		$("#company_name").val('');
		
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
	
	if(requesting_sc_id==requested_sc_id){
		alert('You cannot order to same store.');
		return false;
	}
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


function checkpart(){
	var part_number = $("#part_number").val();
	var part_quantity= $("#part_quantity").val();
	var company_name = 'Default';
	
	//alert (company_name);
	//if(company_name ==''){
	//	$('#company_name').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
//	}

	if(part_number==''){
		$('#part_number').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		
	}
	if(part_quantity==''){
		$('#part_quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		
	}
	

	//alert(part_quantity);
	 var params="part_number="+part_number+"&part_quantity="+part_quantity+"&company="+company_name+"&unq="+ajaxunq();
	
	$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>orders/checkpart",
					data	:	params,
					success	:	function (data){
						if (data == 1){
							addpart();
							}
						if (data==3){
							alert ('Invalid Item Number!!');
							}
						if (data ==2){
							alert('Invalid part Quantity');
							}
						if (data ==4){
							alert('Invalid part Quantity & Item Number');
							}
							
						}								
				});
	 
	}
	
	
	
	function getcallpartlist(){
	showloading();
	$("#getcallpartlist").slideUp('slow');
	
	var currentpage = $("#currentpage").val();
	var searchtxt = $("#searchtxt").val();
	
	var params = 'searchtxt='+searchtxt+"&currentpage="+currentpage+"&unq"+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>orders/getcallpartlist",
			data	:	params,
			success	:	function (data){
					hideloading();
					$("#getcallpartlist").html(data);
					$("#getcallpartlist").slideDown('slow');
				}								
	});
}




function confirmtransit(){
	var order_id = $("#order_id").val();
	var transit_detail_id=$("#transit_detail_id").val();
	var courior_no=$("#courior_number").val();
	var num_box=$('#box_number').val();
	var vehicle_num= $("#vehicle_number").val();
	var transit_num=$("#transit_number").val();
	var courior_date = $("#couriordate").val();
    var x=courior_date.split("/");
    var fromdate_new=(x[2]+"-"+x[1]+"-"+x[0]);
	var requested_sc_id = $('#requested_sc_id').val();
	var requesting_sc_id = $('#requesting_sc_id').val();
    var params = 'courior_number='+courior_no+"&num_box="+num_box+"&vehicle_num="+vehicle_num+"&transit_num="+transit_num+"&courior_date="+fromdate_new+"&order_id="+order_id+"&transit_detail_id="+transit_detail_id+"&requesting_sc_id="+requesting_sc_id+"&ajaxunq="+ajaxunq();
	$.ajax({
		   
		 
		   type		:	"POST",
		   url		:	"<?php echo site_url();?>orders/savetransit",
		   data		:	params,
		   success	:	function(data){
			   $('#transit_detail_id').val(data);
			    	 alert ('Transit Detail Saved');
			 	// $(document).trigger('close.facebox');
			  
			   }
		   
		   });
	
	}
	
	
	
	
	function transitdetail(status){
	var order_id = $("#order_id").val();
	
	if (status == 2){
	
	
	$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/transitdetail', {order_id:order_id},
			function(data) { $.facebox(data) });
		});
		
	
	}
	}
	
	
	
	
	function partialordercard(transit_id,order_id)
{
	
	$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/partialordercard', {order_id:order_id,transit_id:transit_id},
			function(data) { $.facebox(data) });
		});
}


function printordercard()
{
	
	var content = document.getElementById("cardContent");
	var pri = document.getElementById("ifmcontentstoprint").contentWindow;
	pri.document.open();
	pri.document.write(content.innerHTML);
	pri.document.close();
	pri.focus();
	pri.print();
	//changecallstatus(call_id);
	$(document).trigger('close.facebox');
}


function showPopOrderList(){
	
	showloading();
	var fromdate=$("#fromdate").val();
	var todate=$("#todate").val();
	var order_status=$("#order_status").val();
	var currentpage = $("#currentpage").val();
	var sc_id = $("#sc_id").val();
	var params='fromdate='+fromdate+'&todate='+todate+"&sc_id="+sc_id+"&currentpage="+currentpage+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>orders/getcallorderlist",
			data	:	params,
			success	:	function (data){
				$("#popcallorderlist").html(data);
				$("#popcallorderlist").hide();
				$("#popcallorderlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
	
	}
function addcallpart(){
	
	var value = 0;
		$(".call_order").each(function (){
		if (document.getElementById(this.id).checked){
			 value =  parseInt(this.id)+','+value;
         }
		});
		
	var params='values='+value+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>orders/addorderedpart",
			data	:	params,
			success	:	function (data){
				
				alert ('Parts Added');
				$(document).trigger('close.facebox')
				window.location.href = '<?php echo site_url();?>orders/editorder/<?php echo $this->session->userdata('orid');?>';
				//showcallorder();
				}								
		})
	}
	
	function importformcall(){
		
		//event.preventDefault();
			$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/getcallorderpart', 
			function(data) { $.facebox(data) });
		})
	}
	
	
	function createchalan(order_id){
		var requested_sc_id = $('#requested_sc_id').val();
		var requesting_sc_id = $('#requesting_sc_id').val();
		
		$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/partialdeliverychalan',{order_id:order_id,requesting_sc_id:requesting_sc_id,requested_sc_id:requested_sc_id}, 
			function(data) { $.facebox(data) });
		})
	}
	
	
	function savepartialparts(order_part_id,quantity,requested_sc_id,part_number,transit_id,company){
		
		var params="order_part_id="+order_part_id+'&quantity='+quantity+'&requested_sc_id='+requested_sc_id+'&part_number='+part_number+'&transit_id='+transit_id+'&company='+company; 
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>orders/savepartialparts",
					data	:	params,
					success	:	function (data){
						alert('Item Dispatched');
						}								
				});
		
		
		}
		
	function showchalans(order_id){
		
		$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/showchalans',{order_id:order_id}, 
			function(data) { $.facebox(data) });
		})
		
		}
		
	function chalandetail(transit_detail_id){
		$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/receivepartialparts',{transit_detail_id:transit_detail_id}, 
			function(data) { $.facebox(data) });
		})
		
		}
		
		
		
		
		
		function getPickingList(order_id){
			//alert(order_id);
			
			$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/showPickingList',{order_id:order_id}, 
			function(data) { $.facebox(data) });
		})
			
			}
		
		
		
		
</script>