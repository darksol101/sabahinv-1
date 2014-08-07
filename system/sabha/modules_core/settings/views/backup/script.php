<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#reason").val('');
	$("#frmreason").validationEngine('hideAll');
	$("#hdnreason_id").val(0);
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savereason()
{
	showloading();
	var reason=$("#reason").val();
	var hdnreason_id=$("#hdnreason_id").val();
	var params="reason_id="+hdnreason_id+"&reason="+reason+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/reasons/savereason",
			data	:	params,
			success	:	function (data){
				showreasons();
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}
function showreasons(){
	$("#reasonlist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getreasonlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/reasons/getreasonlist",
			data	:	params,
			success	:	function (data){
				$("#reasonlist").css({'display':'none'});
				$("#reasonlist").html(data);
				$("#reasonlist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}
function showReason(reason_id){
	showloading();
	cancel();
	var params='reason_id='+reason_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/reasons/getreasondetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#reason").val(dt.reason);
				$("#hdnreason_id").val(dt.reason_id);
				hideloading();
				}								
		});//end  ajax		
}	


function deletReason(reason_id){
	if(confirm("Are you sure to delete this reason ?")){
		showloading()
		var params="reason_id="+reason_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/reasons/deletereason",
				data	:	params,
				success	:	function (data){
					showreasons();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
</script>