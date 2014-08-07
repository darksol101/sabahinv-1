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
	$("#hdnpending_id").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>'
}
function savereason()
{
	showloading();
	var reason=$("#reason").val();
	var hdnpending_id=$("#hdnpending_id").val();
	var params="pending_id="+hdnpending_id+"&reason="+reason+"&unq="+ajaxunq();
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
function showReason(pending_id){
	showloading();
	cancel();
	var params='pending_id='+pending_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/reasons/getreasondetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#reason").val(dt.pending);
				$("#hdnpending_id").val(dt.pending_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				
				hideloading();
				}								
		});//end  ajax		
}	
function changeStatus(pending_id,status){
	showloading();
		var params="pending_id="+pending_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/reasons/changestatus",
				data	:	params,
				success	:	function (data){
					showreasons();
					hideloading(data);
					}								
			});//end  ajax
}
function deletReason(pending_id){
	if(confirm("Are you sure to delete this reason ?")){
		showloading()
		var params="pending_id="+pending_id+"&unq="+ajaxunq();
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
