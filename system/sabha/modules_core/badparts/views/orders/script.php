<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
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
		var part_desc = ele.find('td:first').next().next().find('input').val();
		var part_quantity = ele.find('td:first').next().next().next().find('input').val();
		
		//alert (company);
		//alert(part_number+'--'+part_quantity+'--'+part_rate) 
// 
		$("#part_number").val(part_number);
		$("#part_desc").val(part_desc);
		$("#part_quantity").val(part_quantity);
		$("#hdnorder_part_id").val(order_part_id);
		
		
	});
	$('a.deletepart').live('click',function(event) {
		if(confirm("Do you want to delete this Part ?")){
			event.preventDefault();
			var ele = $(this).parent().parent();
			var order_part_id = ele.find('td:first').next().find('input:first').val();
			var params="order_part_id="+order_part_id+"&unq="+ajaxunq();
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>badparts/badparts_order/deleteparts",
					data	:	params,
					success	:	function (data){
						$(ele).remove();
						hideloading(data);
						}								
				});//end  ajax
		}
		
	});
	
		$('a.searchpart').live('click',function(event){
		var from_sc_id = $('#badparts_from_sc_id').val();
		$.facebox(function() { 
				$.post('<?php echo site_url();?>badparts/badparts_order/badpartlist',{from_sc_id:from_sc_id}, 
				function(data) { $.facebox(data) });
			})
		});
		
		
		
		
		$('a.setorderpart').live('click',function(){
		var ele = $(this).parent().parent();
		//console.log(ele.find('td:first').next().html());
		var part_number = ele.find('td:first').next().html();
		var part_desc = ele.find('td:first').next().next().html();
		var part_quantity = ele.find('td:first').next().next().next().html();
		
		sn = $("#rowdata tr:last td.sn_td").html();
	sn++
	var html = '';
		var trclass = (sn%2==0)?'even':'odd';
		html+= '<td class="sn_td">'+sn+'</td>';
		html+='<td><input type="hidden" value="" id="" name="badparts_order_part_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
	html+='<td style="text-align: left;"><input type="hidden" name="pdesc[]" value="'+part_desc+'" class="text-input" /><span class="lbl">'+part_desc+'</span></td>';
		html+='<td style="text-align: center;"><input type="hidden" name="pqty[]" value="'+part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
		html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
		html='<tr class="'+trclass+'">'+html+'</tr>';
		$("#rowdata").append(html);
		 $(this).parent().parent().remove();
												  
		});
	
	
	
	
	
	$('a.confirm_part').live('click',function(event){
			event.preventDefault();
			
			var from_sc_id = $('#hdn_from_sc_id').val();
			var to_sc_id = $('#hdn_to_sc_id').val();
			var badparts_order_id = $('#hdn_badparts_order_id').val();
			var transit_id =   $('#transit_detail_id').val();
			if (transit_id ==''){alert ('Please Enter Transit Details'); return false;}
			var ele1 = $(this).parent().parent();
			var badparts_order_part_id =ele1.find('td:first').next().find('input:first').val();
			var part_number =ele1.find('td:first').next().next().find('input:first').val();
			var remaining_quantity =ele1.find('td:first').next().next().next().find('input:first').val();
			
			var quantity = ele1.find('td:first').next().next().next().find('span').html();
			var entered_quantity =  ele1.find('td:first').next().next().next().next().next().next().find('input').val();
			var transit_detail_id = $('#transit_detail_id').val();
			
			//console.log(entered_quantity);
			//alert (remaining_quantity);
			//return false;
			
			
			
			
			if (entered_quantity =='' || isNaN (entered_quantity) == true || entered_quantity < 1 || entered_quantity.toString().indexOf('.') != -1){return false; 
			
			ele1.find('td:first').next().next().next().next().next().next().next().find('input:first').val('');
			
			}
			
			
			if ( parseInt (entered_quantity) > parseInt (remaining_quantity))
				{
					alert('excess quantity');
					return false;
				}
			var params="badparts_order_part_id="+badparts_order_part_id+"&part_number="+part_number+'&quantity='+quantity+'&from_sc_id='+from_sc_id; 
			//alert (params);
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>badparts/badparts_order/checkstock",
					data	:	params,
					success	:	function (data){
						if ( parseInt(entered_quantity) > parseInt(data)){
							alert ('not enough quantity');
							}
							else {//alert ('Part Dispatched successfully');
							ele1.find('td:first').next().next().next().next().next().next().find('input:first').attr("disabled",true);
							ele1.find('td:first').next().next().next().next().next().next().next().find('a:first').hide();
							savepartialparts(badparts_order_part_id,entered_quantity,part_number,transit_id,from_sc_id,to_sc_id);
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
		var from_sc_id = $('#hdn_requesting_sc_id').val();
		var to_sc_id = $('#hdn_requested_sc_id').val();
		var ele1 = $(this).parent().parent();
		var part_number =ele1.find('td:first').next().next().find('input:first').val();
		var badparts_order_part_detail_id =ele1.find('td:first').next().next().next().next().next().next().find('input:first').val();
		var dispatched_chalan_quantity = ele1.find('td:first').next().next().next().next().next().find('input:first').val();
		var dispatched_entered_quantity = ele1.find('td:first').next().next().next().next().next().next().next().next().find('input:first').val();
		var badparts_order_part_id = ele1.find('td:first').next().find('input:first').val();
	//alert(dispatched_chalan_quantity);
		if (dispatched_chalan_quantity =='' || isNaN (dispatched_entered_quantity) == true || dispatched_entered_quantity < 1 || dispatched_entered_quantity.toString().indexOf('.') != -1){return false; 
		
		ele1.find('td:first').next().next().next().next().next().next().next().find('input:first').val('');
		
		}
		
		var params='from_sc_id='+from_sc_id+'&to_sc_id='+to_sc_id+'&part_number='+part_number+'&badparts_order_part_detail_id='+badparts_order_part_detail_id+'&dispatched_chalan_quantity='+dispatched_chalan_quantity+'&dispatched_entered_quantity='+dispatched_entered_quantity+'&badparts_order_part_id='+badparts_order_part_id; 

		//alert (params);
		//return false;
		
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>badparts/badparts_order/receivePartialPart",
				data	:	params,
				success	:	function (data){
					if (data == 1){alert('Entered quantity is more than remaining quantity');}
					else if(data == 2){ alert('Entered Quantity is more than dispatched quantity');} 
					else{
					ele1.find('td:first').next().next().next().next().next().next().next().next().find('input:first').attr("disabled",true);
					ele1.find('td:first').next().next().next().next().next().next().next().next().next().find('a:first').hide();
					}
					
					}								
			});
	}		//$(document).trigger('close.facebox');
		
});





	 
	
	
});
function checkpart(){
   var part_number = $("#part_number").val();
	var part_quantity = $("#part_quantity").val();
	var part_desc = $("#part_desc").val();
	var order_part_id = $("#hdnorder_part_id").val();
	$("#orderForm").validationEngine('hideAll');
	var validate =1;
	
	
	if(part_number==''){
		$('#part_number').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		validate =0;
	}
	if(part_quantity==''){
		$('#part_quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		validate =0;
	}
	
	sn = $("#rowdata tr:last td.sn_td").html();
	sn++
	var html = '';
		var trclass = (sn%2==0)?'even':'odd';
		html+= '<td class="sn_td">'+sn+'</td>';
		html+='<td><input type="hidden" value="" id="" name="badparts_order_part_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
	html+='<td style="text-align: left;"><input type="hidden" name="pdesc[]" value="'+part_desc+'" class="text-input" /><span class="lbl">'+part_desc+'</span></td>';
		html+='<td style="text-align: center;"><input type="hidden" name="pqty[]" value="'+part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
		html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
		html='<tr class="'+trclass+'">'+html+'</tr>';
		
		
		
		
		
		if(order_part_id==0){
			//add case
			$("#rowdata tr").each(function(index) {
				 var ptn = $(this).find('td:first').next().find('input:last').val();
				 if(ptn == part_number){
					 if(confirm('Item Number '+part_number+' already exists. Do you want to update it?')){
						$(this).find('td:first').next().next().next().find('input').val(part_quantity);
						$(this).find('td:first').next().next().next().find('span.lbl').html(part_quantity);
						$(this).find('td:first').next().next().find('input').val(part_desc);
						$(this).find('td:first').next().next().find('span.lbl').html(part_desc);
						 }
					 validate = 0;
				 }
			});
			
		}else{
			var ele = $(".editactive");
			//alert(company);
			ele.find('td:first').next().next().next().find('input').val(part_quantity);
			ele.find('td:first').next().next().next().find('span.lbl').html(part_quantity);
			ele.find('td:first').next().next().find('input').val(part_desc);
			ele.find('td:first').next().next().find('span.lbl').html(part_desc);
			//$(this).find('td:first').next().next().next().next().find('input').val(company);
			//$(this).find('td:first').next().next().next().next().find('span.lbl').html(company);
			//alert(company);
			$(ele).parent().find('tr').removeClass('editactive');
		}
		
		
		
		
		if (validate == 1){
		$("#rowdata").append(html);
		
		
		$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_desc").val('');
		$("#hdnorder_part_id").val('');
		}
		
		
}

function cancelrow(){
		$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_desc").val('');
		$("#hdnorder_part_id").val('');
	}

function checkpart1(){
	
	if(part_number==''){
		$('#part_number').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		validate =0;
	}
	if(part_quantity==''){
		$('#part_quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		validate =0;
	}
	
	
	}
	
	
	
	function createchalan(badparts_order_id){
			var from_sc_id = $('#badparts_from_sc_id').val();
			var to_sc_id = $('#badparts_to_sc_id').val();
			
			
			$.facebox(function() { 
				$.post('<?php echo site_url();?>badparts/badparts_order/createchalan',{badparts_order_id:badparts_order_id,from_sc_id:from_sc_id,to_sc_id:to_sc_id}, 
				function(data) { $.facebox(data) });
			})
		}
		
	
	
	function confirmtransit(){
	 
	 var badparts_order_id = $("#hdn_badparts_order_id").val();
	var transit_detail_id=$("#transit_detail_id").val();
	var courior_no=$("#courior_number").val();
	var num_box=$('#box_number').val();
	var vehicle_num= $("#vehicle_number").val();
	var transit_num=$("#transit_number").val();
	var courior_date = $("#couriordate").val();
    var x=courior_date.split("/");
    var fromdate_new=(x[2]+"-"+x[1]+"-"+x[0]);
	var from_sc_id = $('#hdn_from_sc_id').val();
    var params = 'courior_number='+courior_no+"&num_box="+num_box+"&vehicle_num="+vehicle_num+"&transit_num="+transit_num+"&courior_date="+fromdate_new+"&badparts_order_id="+badparts_order_id+"&transit_detail_id="+transit_detail_id+"&from_sc_id="+from_sc_id+"&ajaxunq="+ajaxunq();
	// alert(from_sc_id);
	 //return false;
	$.ajax({
		   
		
		   type		:	"POST",
		   url		:	"<?php echo site_url();?>badparts/badparts_order/savetransit",
		   data		:	params,
		   success	:	function(data){
			  // alert(data);
			   $('#transit_detail_id').val(data);
			 	// $(document).trigger('close.facebox');
			   	 alert ('Transit Detail Saved');
			   }
		   
		   });
	
	}
		
function savepartialparts(badparts_order_part_id,entered_quantity,part_number,transit_id,from_sc_id,to_sc_id){
	
	var params = 'badparts_order_part_id='+badparts_order_part_id+'&entered_quantity='+entered_quantity+'&part_number='+part_number+'&transit_id='+transit_id+'&from_sc_id='+from_sc_id+'&to_sc_id='+to_sc_id;
	$.ajax({
		    type		:	"POST",
		   url		:	"<?php echo site_url();?>badparts/badparts_order/savePartialParts",
		   data		:	params,
		   success	:	function(data){
			 
			   }
		   
		   });
	
	}	
	
function showchalans(order_id){
	$.facebox(function() { 
		$.post('<?php echo site_url();?>badparts/badparts_order/showchalan', {order_id:order_id},
		function(data) { $.facebox(data) });
	});
	
}

function chalandetail(badparts_transit_detail_id,badparts_order_id){
   //alert(badparts_order_id);
	$.facebox(function(){
		$.post('<?php echo site_url(); ?>badparts/badparts_order/receivechalan',{badparts_transit_detail_id:badparts_transit_detail_id,badparts_order_id:badparts_order_id},
				function(data){$.facebox(data)});

			});		
}


function partialordercard(transit_id,order_id)
{
	
	$.facebox(function() { 
			$.post('<?php echo site_url();?>badparts/badparts_order/partialordercard', {order_id:order_id,transit_id:transit_id},
			function(data) { $.facebox(data) });
		});
}

function closeform(){


	window.location='<?php echo base_url();?>badparts/badparts_order';
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






</script>