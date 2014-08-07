<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#partpending").val('');
	$("#frmpartpending").validationEngine('hideAll');
	$("#hdnpartpending_id").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savepartpending()
{
	showloading();
	var partpending=$("#partpending").val();
	var hdnpartpending_id=$("#hdnpartpending_id").val();
	var params="partpending_id="+hdnpartpending_id+"&partpending="+partpending+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/partpending/savepartpending",
			data	:	params,
			success	:	function (data){
				showPartpending();
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}
function showPartpending(){
	$("#partpendinglist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getpartpendinglist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/partpending/getpartpendinglist",
			data	:	params,
			success	:	function (data){
				$("#partpendinglist").css({'display':'none'});
				$("#partpendinglist").html(data);
				$("#partpendinglist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}
function showPartpendingDetails(partpending_id){
	showloading();
	cancel();
	var params='partpending_id='+partpending_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/partpending/getpartpendingdetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#partpending").val(dt.partpending);
				$("#hdnpartpending_id").val(dt.partpending_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax		
}	
function changeStatus(partpending_id,status){
	showloading();
		var params="partpending_id="+partpending_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/partpending/changestatus",
				data	:	params,
				success	:	function (data){
					showPartpending();
					hideloading(data);
					}								
			});//end  ajax
}
function deletePartpending(partpending_id){
	if(confirm("Are you sure to delete this reason ?")){
		showloading()
		var params="partpending_id="+partpending_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/partpending/deletepartpending",
				data	:	params,
				success	:	function (data){
					showpartpending();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
</script>
