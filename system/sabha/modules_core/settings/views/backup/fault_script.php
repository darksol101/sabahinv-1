<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#fault").val('');
	$("#frmfault").validationEngine('hideAll');
	$("#hdnfault_id").val(0);
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savefault()
{
	showloading();
	var fault=$("#fault").val();
	var hdnfault_id=$("#hdnfault_id").val();
	var params="fault_id="+hdnfault_id+"&fault="+fault+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/faults/savefault",
			data	:	params,
			success	:	function (data){
				showfaults();
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}
function showfaults(){
	$("#faultlist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getfaultlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/faults/getfaultlist",
			data	:	params,
			success	:	function (data){
				$("#faultlist").css({'display':'none'});
				$("#faultlist").html(data);
				$("#faultlist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
}
function showFault(fault_id){
	showloading();
	cancel();
	var params='fault_id='+fault_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/faults/getfaultdetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#fault").val(dt.fault);
				$("#hdnfault_id").val(dt.fault_id);
				hideloading();
				}								
		});//end  ajax		
}
function changeStatus(fault_id,status){
	showloading();
		var params="fault_id="+fault_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/faults/changestatus",
				data	:	params,
				success	:	function (data){
					showfaults();
					hideloading(data);
					}								
			});//end  ajax
}
function deletFault(fault_id){
	if(confirm("Are you sure to delete this fault ?")){
		showloading();
		var params="fault_id="+fault_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/faults/deletefault",
				data	:	params,
				success	:	function (data){
					showfaults();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
</script>