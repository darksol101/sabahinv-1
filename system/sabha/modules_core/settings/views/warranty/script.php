<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#warranty").val('');
	$("#frmwarranty").validationEngine('hideAll');
	$("#hdnwarranty_id").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savewarranty()
{
	showloading();
	var warranty=$("#warranty").val();
	var hdnwarranty_id=$("#hdnwarranty_id").val();
	var params="warranty_id="+hdnwarranty_id+"&warranty="+warranty+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/warranty/savewarranty",
			data	:	params,
			success	:	function (data){
				showwarrantylist();
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}
function showwarrantylist(){
	$("#warrantylist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getwarrantylist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/warranty/getwarrantylist",
			data	:	params,
			success	:	function (data){
				$("#warrantylist").css({'display':'none'});
				$("#warrantylist").html(data);
				$("#warrantylist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}
function showWarrantyDetails(warranty_id){
	showloading();
	cancel();
	var params='warranty_id='+warranty_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>settings/warranty/getwarrantydetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#warranty").val(dt.warranty);
				$("#hdnwarranty_id").val(dt.warranty_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax		
}	
function changeStatus(warranty_id,status){
	showloading();
		var params="warranty_id="+warranty_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/warranty/changestatus",
				data	:	params,
				success	:	function (data){
					showwarrantylist();
					hideloading(data);
					}								
			});//end  ajax
}

function deleteWarranty(warranty_id){
	if(confirm("Are you sure to delete this reason ?")){
		showloading()
		var params="warranty_id="+warranty_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>settings/warranty/deletewarranty",
				data	:	params,
				success	:	function (data){
					showwarrantylist();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
</script>
