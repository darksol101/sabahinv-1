<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#closure").val('');
	$("#frmclosure").validationEngine('hideAll');
	$("#hdnclosure_id").val(0);
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function saveclosure()
{
	showloading();
	var closure=$("#closure").val();
	var hdnclosure_id=$("#hdnclosure_id").val();
	var params="closure_id="+hdnclosure_id+"&closure="+closure+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/closure/saveclosure",
			data	:	params,
			success	:	function (data){
				showclosurelist();
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}
function showclosurelist(){
	$("#closurelist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getclosurelist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/closure/getclosurelist",
			data	:	params,
			success	:	function (data){
				$("#closurelist").css({'display':'none'});
				$("#closurelist").html(data);
				$("#closurelist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}
function showClosureDetails(closure_id){
	showloading();
	cancel();
	var params='closure_id='+closure_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/closure/getclosuredetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#closure").val(dt.closure);
				$("#hdnclosure_id").val(dt.closure_id);
				hideloading();
				}								
		});//end  ajax		
}	
function changeStatusc(closure_id,status){
	showloading();
		var params="closure_id="+closure_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/closure/changestatusc",
				data	:	params,
				success	:	function (data){
					showclosurelist();
					hideloading(data);
					}								
			});//end  ajax
}

function deleteClosure(closure_id){
	if(confirm("Are you sure to delete this reason ?")){
		showloading()
		var params="closure_id="+closure_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/closure/deleteclosure",
				data	:	params,
				success	:	function (data){
					showclosurelist();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
</script>