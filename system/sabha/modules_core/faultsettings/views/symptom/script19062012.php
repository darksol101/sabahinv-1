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
	var symptom_description=$("#symptom_description").val();
	var hdnsymptom_id=$("#hdnsymptom_id").val();
	var params="symptom_id="+hdnsymptom_id+"&symptom_code="+symptom_code+"&symptom_description="+symptom_description+"&symptom_status="+symptom_status+"&unq="+ajaxunq();
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
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getsymptomlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
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
				$("#hdnsymptom_id").val(dt.symptom_id);
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
</script>