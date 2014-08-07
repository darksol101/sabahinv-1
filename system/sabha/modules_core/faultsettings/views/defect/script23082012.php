<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});
function cancel(){
	$("#defect_code").val('');
	$("#defect_description").val('');
	$("#defect_status").val('');
	$("#symptom_code").val('');
	$("#brand_select").val('');
	$("#product_select").val('');
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$("#product_select").html('<option value="">Select Product</option>');
	$("#symptom_code").html('<option value="">Select Symptom</option>');
	$("#frmdefect").validationEngine('hideAll');
	$("#hdndefect_id").val(0);
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savedefect()
{
	showloading();
	var defect_code=$("#defect_code").val();
	var searchtxt=$("#searchtxt").val();
	var symptom_id=$("#symptom_code").val();
	var defect_description=$("#defect_description").val();
	var defect_status=$("#defect_status").val();
	var hdndefect_id=$("#hdndefect_id").val();
	var params="defect_id="+hdndefect_id+"&defect_code="+defect_code+"&symptom_id="+symptom_id+"&defect_description="+defect_description+"&defect_status="+defect_status+"&searchtxt="+searchtxt+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/defect/savedefect",
			data	:	params,
			success	:	function (data){
				showdefectlist();
				cancel();
				hideloading(data);
				}								
		});//end  ajax
}
function showdefectlist(){
	cancel();
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var product_id = $("#product_search").val();
	var brand_id = $("#brand_search").val();
	var params="ajaxaction=getdefectlist&searchtxt="+searchtxt+'&product_id='+product_id+'&brand_id='+brand_id+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/defect/getdefectlist",
			data	:	params,
			success	:	function (data){
				$("#defectlist").css({'display':'none'});
				$("#defectlist").html(data);
				$("#defectlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function showDefectDetails(defect_id){
	showloading();
	cancel();
	var params='defect_id='+defect_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/defect/getdefectdetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#defect_code").val(dt.defect_code);
				$("#symptom_code").val(dt.symptom_id);
				$("#defect_description").val(dt.defect_description);
				$("#hdndefect_id").val(dt.defect_id);
				$("#brand_select").val(dt.brand_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				getProductBybrand(dt.brand_id,dt.product_id);
				getSymptomsByProduct(dt.product_id,dt.symptom_id);
				hideloading();
				}								
		});//end  ajax		
}	
function changeStatus(defect_id,status){
	showloading();
		var params="defect_id="+defect_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>faultsettings/defect/changestatus",
				data	:	params,
				success	:	function (data){
					showdefectlist();
					hideloading(data);
					}								
			});//end  ajax
}

function deleteDefect(defect_id){
	if(confirm("Are you sure to delete this reason ?")){
		showloading()
		var params="defect_id="+defect_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>faultsettings/defect/deletedefect",
				data	:	params,
				success	:	function (data){
					showdefectlist();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
function searchSymptom(){
	$("#defectlist").css({'display':'none'});
	showloading();
	var searchsymptomtxt=$("#searchsymptomtxt").val()
	var params="ajaxaction=getdefectlist&searchsymptomtxt="+searchsymptomtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>defect/getsymptomsearch",
			data	:	params,
			success	:	function (data){
				$("#symptomlist").css({'display':'none'});
				$("#symptomlist").html(data);
				$("#symptomlist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
}
function getProductBybrand(brand_id,active){
	loading("select_product_box");
	var params = 'brand_id='+brand_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>faultsettings/defect/getproductsbybrand",
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
			url		:	"<?php echo base_url();?>faultsettings/defect/getsymptomsbyproduct",
			data	:	params,
			success	:	function (data){
				$("#select_symptom_box").html(data);
				hideloading();
				}								
		});
}
function getProductBybrandSearch(brand_id,active){
	loading("product_box_search");
	var params = 'brand_id='+brand_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>faultsettings/defect/getproductsbybrandsearch",
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