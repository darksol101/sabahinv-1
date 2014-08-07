<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#symptom_code").val('');
	$("#symptom_description").val('');
	$("#symptom_status").val('');
	$("#symptom_code").val('');
	$("#brand_select").val('');
	$("#product_select").val('');
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$("#frmsymptom").validationEngine('hideAll');
	$("#hdnsymptom_id").val(0);
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savesymptom()
{
	showloading();
	var symptom_code=$("#symptom_code").val();
	var symptom_status=$("#symptom_status").val();
	var product_id=$("#product_select").val();
	var symptom_description=$("#symptom_description").val();
	var hdnsymptom_id=$("#hdnsymptom_id").val();
	var params="symptom_id="+hdnsymptom_id+"&symptom_code="+symptom_code+"&symptom_description="+symptom_description+"&symptom_status="+symptom_status+"&product_id="+product_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/symptom/savesymptom",
			data	:	params,
			success	:	function (data){
				showsymptomlist();
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}
function showsymptomlist(){
	cancel();
	showloading();
	var brand_id = $('#brand_search').val();
	var product_id = $('#product_search').val();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="product_id="+product_id+"&brand_id="+brand_id+"&ajaxaction=getsymptomlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/symptom/getsymptomlist",
			data	:	params,
			success	:	function (data){
				$("#symptomlist").css({'display':'none'});
				$("#symptomlist").html(data);
				$("#symptomlist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}
function showSymptomDetails(symptom_id){
	showloading();
	cancel();
	var params='symptom_id='+symptom_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>faultsettings/symptom/getsymptomdetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#symptom_code").val(dt.symptom_code);
				$("#symptom_description").val(dt.symptom_description);
				$("#symptom_status").val(dt.symptom_status);
				$("#hdnsymptom_id").val(dt.symptom_id);
				$('#brand_select').val(dt.brand_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				getProductBybrand(dt.brand_id,dt.product_id);
				hideloading();
				}								
		});//end  ajax		
}	
function changeStatus(symptom_id,status){
	showloading();
		var params="symptom_id="+symptom_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>faultsettings/symptom/changestatus",
				data	:	params,
				success	:	function (data){
					showsymptomlist();
					hideloading(data);
					}								
			});//end  ajax
}

function deleteSymptom(symptom_id){
	if(confirm("Are you sure to delete this reason ?")){
		showloading()
		var params="symptom_id="+symptom_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>faultsettings/symptom/deletesymptom",
				data	:	params,
				success	:	function (data){
					showsymptomlist();
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
			url		:	"<?php echo base_url();?>faultsettings/symptom/getproductsbybrand",
			data	:	params,
			success	:	function (data){
				$("#select_product_box").html(data);
				hideloading();
				}								
		});
}
function getProductBybrandSearch(brand_id,active){
	loading("product_box_search");
	var params = 'brand_id='+brand_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>faultsettings/symptom/getproductsbybrandsearch",
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