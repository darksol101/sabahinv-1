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
		var purchase_details_id = ele.find('td:first').next().find('input:first').val();
		$("#hdnpurchase_details_id").val(purchase_details_id);
		var part_number = ele.find('td:first').next().find('input:last').val();
		var part_description = ele.find('td:first').next().next().find('input').val();
		var part_quantity = ele.find('td:first').next().next().next().find('input').val();
		var company = ele.find('td:first').next().next().next().next().find('input:last').val();
		var ser = ele.find("td.sn_td").html();
		$("#serial").val(ser);
		$("#part_number").val(part_number);
		$("#part_description").val(part_description);
		$("#part_quantity").val(part_quantity);
		$("#select_company").val(company);
		$("#hdnpurchase_details_id").val(purchase_details_id);
		
	});
	$('a.deletepart').live('click',function(event) {
		if(confirm("Are you sure to delete this Part ?")){
			event.preventDefault();
			var ele = $(this).parent().parent();
			var purchase_details_id = ele.find('td:first').next().find('input:first').val();
			var params="purchase_details_id="+purchase_details_id+"&unq="+ajaxunq();
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>purchase/deleteparts",
					data	:	params,
					success	:	function (data){
						//showwarrantylist();
						$(ele).remove();
						hideloading(data);
						}								
				});//end  ajax
		}
		
	});
});
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


function checkpart(){

	var part_number = $("#part_number").val();
	var part_quantity= $("#part_quantity").val();
	var company =$("#select_company").val();
	var purchase_details_id = $("#hdnpurchase_details_id").val();
	var status = 1;
	if(company==''){
		$('#select_company').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		status = 0;
	}
	if(part_number==''){
		$('#part_number').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		status = 0;
	}
	if(part_quantity==''){
		$('#part_quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		status = 0;
	}
	if(part_quantity<= 0){
		$('#part_quantity').validationEngine('showPrompt', '* Invalid quantity', 'error', 'topRight', true);
		status = 0;
	}
	
	
	
    var params="part_number="+part_number+"&part_quantity="+part_quantity+"&company="+company+"&unq="+ajaxunq();
	if (status == 1){
	$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>purchase/checkpart",
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
	}



function saveXldata() {
$(".datas").each(function() {	
		var part_number = $(this).find('td:nth-child(2) ii').html();
		var part_description = $(this).find('td:nth-child(3) ii').html();
		var company = $(this).find('td:nth-child(4) ii').html();
		var part_quantity = $(this).find('td:nth-child(5) ii').html();
		
		var add= $(this).find('td:nth-child(7) select').val();


		/*var params=part_number+" "+part_desc+" "+company+" "+quantity;

		console.log(params); */
		sn = $("#rowdata tr:last td.sn_td").html();
		
		sn++;
		var html = '';
		var trclass = (sn%2==0)?'even':'odd';
		html+= '<td class="sn_td">'+sn+'</td>';
		html+='<td><input type="hidden" value="" id="" name="purchase_details_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
		
		html+='<td style="text-align: left;"><input type="hidden" name="pdesc[]" value="'+part_description+'" class="text-input" /><span class="lbl">'+part_description+'</span></td>';
		
		html+='<td style="text-align: left;"><input type="hidden" name="pqty[]" value="'+part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
		
		html+='<td style="text-align: left;"><input type="hidden" name="comp[]" value="'+company+'" class="text-input" /><span class="lbl">'+company+'</span></td>';
		
		html+='<td> </td>';
		html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
		
		html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
		html='<tr class="'+trclass+'">'+html+'</tr>';

		add=1;

		$("#rowdata tr").each(function(index) {
				var ptn = $(this).find('td:first').next().find('input:last').val();
 				var cmp =  $(this).find('td:first').next().next().next().next().find('input:last').val();
				
				if(ptn == part_number && cmp == company ){
						if(confirm('Item Number '+part_number+' already exists,Do you want to update it ?')){
							$(this).find("td:nth-child(4) input").val(part_quantity);
							$(this).find("td:nth-child(4) span.lbl").html(part_quantity);
							$("#facebox").empty();
							add = 0;
							return;				
						}
						else{	
							add =0;						   		
							return;					
						}
				}
		});

		

		if(add==1){
				$("#rowdata").append(html);
				$("#serial").val('');

			}


	});

$("#facebox").hide();

}

function addpart(){
	$("#cancel_part").hide();
	var part_number = $("#part_number").val();
	var part_quantity = $("#part_quantity").val();
	var company =$("#select_company").val();
	var part_description = $("#part_description").val();
    var purchase_details_id = $("#hdnpurchase_details_id").val();
	var serial = $("#serial").val();
	//alert(serial);
	$("#purchaseForm").validationEngine('hideAll');
		sn = $("#rowdata tr:last td.sn_td").html();
		
		sn++;
		var html = '';
		var trclass = (sn%2==0)?'even':'odd';
		html+= '<td class="sn_td">'+sn+'</td>';
		html+='<td><input type="hidden" value="" id="" name="purchase_details_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
		
		html+='<td style="text-align: left;"><input type="hidden" name="pdesc[]" value="'+part_description+'" class="text-input" /><span class="lbl">'+part_description+'</span></td>';
		
		html+='<td style="text-align: left;"><input type="hidden" name="pqty[]" value="'+part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
		
		html+='<td style="text-align: left;"><input type="hidden" name="comp[]" value="'+company+'" class="text-input" /><span class="lbl">'+company+'</span></td>';
		
		html+='<td> </td>';
		html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
		
		html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
		html='<tr class="'+trclass+'">'+html+'</tr>';
		var add=1;

		if(purchase_details_id==0){
			//add case 
			$("#rowdata tr").each(function(index) {
				 var ptn = $(this).find('td:first').next().find('input:last').val();
 				 var cmp =  $(this).find('td:first').next().next().next().next().find('input:last').val();
				  var sn = $(this).find("td.sn_td").html();				  
				 // alert('loop'+sn+'----------'+serial);
				  	if (serial == sn){
							
								 if(ptn == part_number && cmp == company ){
									 
									  if(confirm('Item Number '+part_number+' already exists,Do you want to update it ?')){
												$(this).find('td:first').next().next().next().next().find('input').val(company);
												$(this).find('td:first').next().next().next().next().find('span.lbl').html(company);
												$(this).find('td:first').next().next().next().find('input').val(part_quantity);
												$(this).find('td:first').next().next().next().find('span.lbl').html(part_quantity);
												$(this).find('td:first').next().next().find('input').val(part_description);
												$(this).find('td:first').next().next().find('span.lbl').html(part_description);
												$(this).find('td:first').next().find('input').val(part_number);
												$(this).find('td:first').next().find('span.lbl').html(part_number);									
												 
												 //$("#serial").val('');
												 add = 0;
											}
											else{	
													   		
												add = 0;	
												$("#serial").val('');
												return;		
											}
									 }else{	
											 if(confirm('Do you Want to Update this this row ?')){
												$(this).find('td:first').next().next().next().next().find('input').val(company);
												$(this).find('td:first').next().next().next().next().find('span.lbl').html(company);
												$(this).find('td:first').next().next().next().find('input').val(part_quantity);
												$(this).find('td:first').next().next().next().find('span.lbl').html(part_quantity);
												$(this).find('td:first').next().next().find('input').val(part_description);
												$(this).find('td:first').next().next().find('span.lbl').html(part_description);
												$(this).find('td:first').next().find('input').val(part_number);
												$(this).find('td:first').next().find('span.lbl').html(part_number);									
												 
												 //$("#serial").val('');
												 add = 0;
											}
											else{	
													   		
												add = 0;	
												$("#serial").val('');
												return;		
											}	
										}
					    }
									
						else{ if(ptn == part_number && cmp == company ){
									 if(confirm('Item Number '+part_number+' already exists,Do you want to update it ?')){
												$(this).find('td:first').next().next().next().next().find('input').val(company);
												$(this).find('td:first').next().next().next().next().find('span.lbl').html(company);
												$(this).find('td:first').next().next().next().find('input').val(part_quantity);
												$(this).find('td:first').next().next().next().find('span.lbl').html(part_quantity);
												$(this).find('td:first').next().next().find('input').val(part_description);
												$(this).find('td:first').next().next().find('span.lbl').html(part_description);
												$(this).find('td:first').next().find('input').val(part_number);
												$(this).find('td:first').next().find('span.lbl').html(part_number);									
												
												 //$("#serial").val('');
												 add = 0;
									}
									else
										add = 0;
										return true;
									}}
									
									
		});
			
			
	           if(add==1){
				$("#rowdata").append(html);
				$("#serial").val('');
			}
		
	
		}else{
			var ele = $(".editactive");
			 $(this).find('td:first').next().next().next().next().find('input').val(company);
			$(this).find('td:first').next().next().next().next().find('span.lbl').html(company);
			ele.find('td:first').next().next().next().find('input').val(part_quantity);
			ele.find('td:first').next().next().next().find('span.lbl').html(part_quantity);
			ele.find('td:first').next().next().find('input').val(part_description);
			ele.find('td:first').next().next().find('span.lbl').html(part_description);
			$(this).find('td:first').next().find('input').val(part_number);
			$(this).find('td:first').next().find('span.lbl').html(part_number);
			$(ele).parent().find('tr').removeClass('editactive');
		}
		$("#hdnpurchase_details_id").val(0);
		//$("#sno").val('');
		$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_description").val('');
		$("#select_company").val('');
		
		$("#add_part").val('Add');
	
}
function cancelform()
{
	$("#purchase_number").val('');
	$("#purchase_notes").val('');
	$("#part_number").val('');
	$("#part_description").val('');
	$('#part_quantity').val('') ;
	$('#part_rate').val('') ;
	//$('#vendor_select').children().remove().end().append('<option selected value="">Select Vendor</option>') ;
	$("#hdnid").val(0);
	$("#purchaseForm").validationEngine('hideAll');
}
function cancelrow()
{
	$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_description").val('');
		$("#cancel_part").hide();
		$("#select_company").val();
		$("#add_part").val('Add');
		
}
function closeform()
{
	window.location='<?php echo base_url();?>purchase/';
}


function uploadexcel(){
	//alert('m here');
	$.facebox(function() { 
		  $.post('<?php echo site_url();?>purchase/uploadform', { unq:ajaxunq()}, 
			function(data) {$.facebox(data); $("#save_xldata").show();});
	    });
}

function uploadFile(){
	$("#result").html();
	var url = "<?php echo site_url();?>purchase/upload";
	$("#exlForm").ajaxForm({
		url:  url,
		type:'POST',
		target: '#result',
		success:function(){
			$("#excel_file").val('');
			$("#result").slideDown('slow');
			}
	}).submit();
}
function saveXldata_back(){
	document.xlForm.action='<?php echo site_url()?>purchase/savep';
    document.xlForm.submit();
}

function downloadtemplate(){
	
	document.purchaseForm.action='<?php echo site_url()?>purchase/generatetemplateexl';
    document.purchaseForm.submit();
}

</script>
