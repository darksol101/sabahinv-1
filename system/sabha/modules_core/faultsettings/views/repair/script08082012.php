<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#repair_code").val('');
	$("#repair_description").val('');
	$("#symptom_code").val('');
	$("#defect_code").val('');
	$("#brand_select").val(0);
	$("#product_select").html('<option value="">Select Product</option>');
	$("#symptom_code").html('<option value="">Select Symptom</option>');
	$("#defect_code").html('<option value="">Select Defect</option>');
	$("#repair_status").val('');
	$("#frmrepair").validationEngine('hideAll');
	$("#hdnrepair_id").val(0);
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function saverepair()
{
	showloading();
	var repair_code=$("#repair_code").val();
	var searchtxt=$("#searchtxt").val();
	var symptom_id=$("#symptom_code").val();
	var defect_id=$("#defect_code").val();
	var repair_description=$("#repair_description").val();
	var repair_status=$("#repair_status").val();
	var hdnrepair_id=$("#hdnrepair_id").val();
	var params="repair_id="+hdnrepair_id+"&repair_code="+repair_code+"&symptom_id="+symptom_id+"&defect_id="+defect_id+"&repair_description="+repair_description+"&repair_status="+repair_status+"&searchtxt="+searchtxt+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/repair/saverepair",
			data	:	params,
			success	:	function (data){
				showrepairlist();
				cancel();
				hideloading(data);
				}								
		});//end  ajax
}
function sortdata(sort_field,sort_type){
	$("#sort_field").val(sort_field);
	$("#sort_type").val(sort_type);
	showrepairlist();
}
function showrepairlist(){
	$("#repairlist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var sort_field=$("#sort_field").val();
	var sort_type=$("#sort_type").val();
	var brand_id = $("#brand_search").val();
	var product_id = $("#product_search").val();
	
	var params="ajaxaction=getrepairlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&sort_field="+sort_field+"&sort_type="+sort_type+"&brand_id="+brand_id+"&product_id="+product_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/repair/getrepairlist",
			data	:	params,
			success	:	function (data){
				$("#repairlist").css({'display':'none'});
				$("#repairlist").html(data);
				$("#repairlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
	}
function showRepairDetails(repair_id){
	showloading();
	cancel();
	var params='repair_id='+repair_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/repair/getrepairdetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#repair_code").val(dt.repair_code);
				$("#symptom_code").val(dt.symptom_id);
				$("#defect_code").val(dt.defect_id);
				$("#repair_description").val(dt.repair_description);
				$("#hdnrepair_id").val(dt.repair_id);
				$("#brand_select").val(dt.brand_id);
				getDefectCodeBySymptom(dt.symptom_id,dt.defect_id);
				getProductBybrand(dt.brand_id,dt.product_id);
				getSymptomsByProduct(dt.product_id,dt.symptom_id);
				hideloading();
				}								
		});//end  ajax		
}	
function changeStatus(repair_id,status){
	showloading();
	var params="repair_id="+repair_id+"&status="+status+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/repair/changestatus",
			data	:	params,
			success	:	function (data){
				showrepairlist();
				hideloading(data);
				}								
		});//end  ajax
}

function deleteRepair(repair_id){
	if(confirm("Are you sure to delete this reason ?")){
		showloading()
		var params="repair_id="+repair_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>faultsettings/repair/deleterepair",
				data	:	params,
				success	:	function (data){
					showrepairlist();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
function getProductBybrand(brand_id,active){
	loading("select_product_box");
	var params = 'brand_id='+brand_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>faultsettings/repair/getproductsbybrand",
			data	:	params,
			success	:	function (data){
				$("#select_product_box").html(data);
				hideloading();
				}								
		});
}
function getSymptomsByProduct(product_id,active){
	loading("select_symptom_box");
	var params = 'product_id='+product_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>faultsettings/repair/getsymptomsbyproduct",
			data	:	params,
			success	:	function (data){
				$("#select_symptom_box").html(data);
				hideloading();
				}								
		});
}
function getDefectCodeBySymptom(symptom_id,active){
	loading("select_defect_box");
	var params="symptom_id="+symptom_id+"&active="+active+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/repair/getdefectcodebysymptom",
			data	:	params,
			success	:	function (data){
				$("#select_defect_box").html(data);
				hideloading();
				}								
		});//end  ajax	
}
function getProductBybrandSearch(brand_id,active){
	loading("product_box_search");
	var params = 'brand_id='+brand_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>faultsettings/repair/getproductsbybrandsearch",
			data	:	params,
			success	:	function (data){
				$("#product_box_search").html(data);
				hideloading();
				}								
		});
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>