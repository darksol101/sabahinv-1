<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">


function showSalesMakerList() {
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage = $("#currentpage").val();
	var params="currentpage="+currentpage+"&searchtxt="+searchtxt+"&unq="+ajaxunq();

	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>salesmaker/getsalesmakerlist",
			data	:	params,
			success	:	function (data){
				$("#partlist").html(data);
				$("#partlist").hide();
				$("#partlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}

function getAssignedList(id,name) {
	$.facebox(function() { 
		  $.post('<?php echo site_url();?>salesmaker/getAssignedList', {maker_id:id,name:name,unq:ajaxunq()}, 
			function(data) {
				$.facebox(data);	
			});
	    });
}


function editSaleMaker (id) {
		$("#salesmaker_action").val('2');
		$("#btn_submit").val('Edit');
		$("#sale_id").val(id);
		var ele = $("#"+id);
		var sale_name = ele.find('td:nth-child(2)').html();
		var deduction_type = ele.find('td:nth-child(3)').attr('value');
		var deduction_value = ele.find('td:nth-child(4)').attr('value');
		var sale_status = ele.find('td:nth-child(5)').attr('value');		
		var started_date = ele.find('td:nth-child(6)').html();		
		var end_date = ele.find('td:nth-child(7)').html();
		//alert(end_date);
		$("#sale_name").val(sale_name);
		$("#salestatus").val(sale_status);
		$("#deductiontype").val(deduction_type);
		$("#deduction_value").val(deduction_value);
		$("#issue_date").val(started_date);
		$("#expire_date").val(end_date);
}



function deleteSaleMaker(id) {
	if(confirm("Are you Sure to delete this Item")){
		$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>salesmaker/delete",
			data	:	{id:id},
			success	:	function (data){
					showSalesMakerList();
					hideloading(data);
				}								
		});//end  ajax
	}
}

function cancelform()
{
	$("#salesmakerForm tbody tr input").val('');
	$("#btn_submit").val('Add');
	$("#btn_cancel").val('Cancel');
}

function saveSalesMaker () {	
	var sale_name = $("#sale_name").val();
	//var sale_maker_assign = $("#sale_maker_assign").val();
	//var sale_maker_options = $("#assign_to select").val();
	var salestatus = $("#salestatus").val();
	var deductiontype = $("#deductiontype").val();
	var deduction_value = $("#deduction_value").val();
	if(deductiontype==1){
		if(deduction_value >= 50){
			if(confirm("Are you sure the Discount is more than 50%")){
				return true;
			}
			else{
				$("#deduction_value").val('10');
				return false;
			}
			
		}

		if(deduction_value > 99){
			if(confirm("Discount % is Wrong")){
				return true;
			}
			else{
				$("#deduction_value").val('10');
				return false;
			}
		}
	}

	var issue_date = $("#issue_date").val();
	var expire_date = $("#expire_date").val();
	var salesmaker_action = $("#salesmaker_action").val();
	var sale_id = $("#sale_id").val();

	var params="sale_id="+sale_id+"&salesmaker_action="+salesmaker_action+"&expire_date="+expire_date+"&sale_name="+sale_name+"&sale_name="+sale_name+"&salestatus="+salestatus+"&deductiontype="+deductiontype+"&deduction_value="+deduction_value+"&issue_date="+issue_date+"&unq="+ajaxunq();

	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>salesmaker/save_salesmaker",
			data	:	params,
			success	:	function (data){
				cancelform();
				showSalesMakerList();
				hideloading(data);
				}								
		});//end  ajax
}



function selectAllBrands(){
	checkAll("chkbrand","brand");
	$("#box_check").hide();
	$("#chkproduct").removeAttr("checked");
	var brands = calculateChecked(".brand");
	$("#product_box").html('');
	getProducts(brands);
}

function selectAllProduct(){
	checkAll("chkproduct","product");
	$("#chkmodel").removeAttr("checked");
	var products = calculateChecked(".product");
	$("#model_box").html('');
	getProductmodels(products);
}

function selectAllModel()
{
	checkAll("chkmodel","model");
	$("#chkpart").removeAttr("checked");
	var model = calculateChecked(".model");

	$("#part_box").html('');
	getModelparts(model);
}

function selectAllPart() {
	checkAll('chkpart','part');
}

function calculateChecked(param)
{
	var optionValues = [];
	$(param).each(function() { 
		if($(this).is(':checked')){
			optionValues.push($(this).val()) 
		}
	 });
	return optionValues;
}

function checkAll(selector1,selector2)
{
	var checked = $("#"+selector1+"").attr("checked");
	if(checked=='checked'){
		$("."+selector2+"").attr({"checked":"checked"});
	}else{
		$("#"+selector1+"").removeAttr("checked");
		$("."+selector2+"").removeAttr("checked");
	}
}


function getProducts(brand_id){
	var maker_id= $("#maker_id").val();
	var part_number = $("#hdnpart_number").val();
	var part_id = $("#hdnpart_id").val();
	var is_checked = $('#brand_'+brand_id).is(':checked');
	var checked = $(".brand:checked").length;
	var checkbox = $(".brand").length;
	
	if(checked == checkbox){
		$("#chkbrand").attr({"checked":"checked"});
	}else{
		$("#chkbrand").removeAttr("checked");
	}
	
	if(is_checked==true){
		showloading();
		var user_id = $("#hdnuserid").val();
		var params = 'maker_id='+maker_id+'&part_number='+part_number+'&part_id='+part_id+'&user_id='+user_id+'&brand_id='+brand_id+'&unq='+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	'<?php echo site_url();?>salesmaker/getproductsbybrand',
				data	:	params,
				success	:	function (data){
							$("#box_check").show();
							$('#product_box').append(data);
							var checked_p = $(".product:checked").length;
							var checkbox_p = $(".product").length;
							if(checked_p==checkbox_p){
								$("#chkproduct").attr({"checked":"checked"});
							}else{
								$("#chkproduct").removeAttr('checked');
							}
							$('.product').click(function(){
								var checked = $(".product:checked").length;
								var checkbox = $(".product").length;
								if(checked==checkbox_p){
									$("#chkproduct").attr({"checked":"checked"});
								}else{
									$("#chkproduct").removeAttr('checked');
								}
							});

							var products = calculateChecked(".product");
							$("#model_box").html('');
							getProductmodels(products);
							hideloading();
				
					}
		});
	}else{
		$("#product_"+brand_id).remove();
	}
}

function getProductmodels(product_id){
	var maker_id= $("#maker_id").val();
	var part_number = $("#hdnpart_number").val();
	var part_id = $("#hdnpart_id").val();
	var is_checked = $('#product_'+product_id).is(':checked');
	var checked = $(".product:checked").length;
	var checkbox = $(".product").length;

	if(checked == checkbox){
		$("#chkproduct").attr({"checked":"checked"});
	}else{
		$("#chkproduct").removeAttr("checked");
	}
	
	var params ='maker_id='+maker_id+'&part_number='+part_number+'&part_id='+part_id+'&product_id='+product_id+'&unq='+ajaxunq();

	if(is_checked==true){
		$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>salesmaker/getmodelsbyproducts",
			data	:	params,
			success	:	function (data){
							$("#box_check_model").show();
							$("#model_box").append(data);
							var checked_p = $(".model:checked").length;
							var checkbox_p = $(".model").length;
							if(checked_p==checkbox_p){
								$("#chkmodel").attr({"checked":"checked"});
							}else{
								$("#chkmodel").removeAttr('checked');
							}
							$('.model').click(function(){
								var checked = $(".mdoel:checked").length;
								var checkbox = $(".model").length;
								if(checked==checkbox_p){
									$("#chkmodel").attr({"checked":"checked"});
								}else{
									$("#chkmodel").removeAttr('checked');
								}
							});
							var model = calculateChecked(".model");
							$("#part_box").html('');
							getModelparts(model);
							hideloading();
						
					
						}								
		});//end  ajax
	}else{
		$("#model_"+product_id).remove();
	}
}

function getModelparts(model_id) {
	var maker_id= $("#maker_id").val();
	var is_checked = $('#submodel'+model_id).is(':checked');
	var checked = $(".model:checked").length;
	var checkbox = $(".model").length;
	
	if(checked == checkbox){
		$("#chkmodel").attr({"checked":"checked"});
	}else{
		$("#chkmodel").removeAttr("checked");
	}
	
	if(is_checked==true){
		showloading();
		var params = 'maker_id='+maker_id+'&model_id='+model_id+'&unq='+ajaxunq();
		
		$.ajax({
			type	:	"POST",
			url		:	"<?php echo site_url();?>salesmaker/getpartbymodel",
			data	:	params,
			success: function(data) {
							$("#box_check_part").show();
							$("#part_box").append(data);
							var checked_p = $(".part:checked").length;
							var checkbox_p = $(".part").length;
							if(checked_p==checkbox_p){
								$("#chkpart").attr({"checked":"checked"});
							}else{
								$("#chkpart").removeAttr('checked');
							}
							$('.part').click(function(){
								var checked = $(".part:checked").length;
								var checkbox = $(".part").length;
								if(checked==checkbox_p){
									$("#chkpart").attr({"checked":"checked"});
								}else{
									$("#chkpart").removeAttr('checked');
								}
							});
			}

		});
	}else{
		$("#part_"+model_id).remove();
	}

}



function saveModelPart(){
	var maker_id = $("#maker_id").val();
	var str = '';
	

	$(".part").each(function() { 
		if($(this).is(':checked')){
			str+='&part[]='+$(this).val(); 
		}
	 });
	if(str==''){
		alert('No Item Selected. Please select atleast one'); return false;
	}
var params="maker_id="+maker_id+""+str+"&unq="+ajaxunq();

	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>salesmaker/saveModelPart",
			data	:	params,
			success	:	function (data){
				$(document).trigger('close.facebox');
				$("#result").html(data);
				hideloading(data);
				}								
		});
	//end  ajax
}

function resetForm() {
	$("#salesmakerForm tbody tr input").empty();
  	$("#salesmaker_action").val(1);
}


</script>
