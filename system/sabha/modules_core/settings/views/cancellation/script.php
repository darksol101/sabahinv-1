<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#cancellation").val('');
	$("#frmcancellation").validationEngine('hideAll');
	$("#hdncancellation_id").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savecancellation()
{
	showloading();
	var cancellation=$("#cancellation").val();
	var hdncancellation_id=$("#hdncancellation_id").val();
	var params="cancellation_id="+hdncancellation_id+"&cancellation="+cancellation+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/cancellation/savecancellation",
			data	:	params,
			success	:	function (data){
				showcancellationlist();
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}
function showcancellationlist(){
	$("#cancellationlist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getcancellationlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/cancellation/getcancellationlist",
			data	:	params,
			success	:	function (data){
				$("#cancellationlist").css({'display':'none'});
				$("#cancellationlist").html(data);
				$("#cancellationlist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
}
function showCancellationDetails(cancellation_id){
	showloading();
	cancel();
	var params='cancellation_id='+cancellation_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/cancellation/getcancellationdetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#cancellation").val(dt.cancellation);
				$("#hdncancellation_id").val(dt.cancellation_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax		
}
function changeStatus(cancellation_id,status){
	showloading();
		var params="cancellation_id="+cancellation_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/cancellation/changestatus",
				data	:	params,
				success	:	function (data){
					showcancellationlist();
					hideloading(data);
					}								
			});//end  ajax
}
function deleteCancellation(cancellation_id){
	if(confirm("Are you sure to delete this fault ?")){
		showloading();
		var params="cancellation_id="+cancellation_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/cancellation/deletecancellation",
				data	:	params,
				success	:	function (data){
					showcancellationlist();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
</script>
