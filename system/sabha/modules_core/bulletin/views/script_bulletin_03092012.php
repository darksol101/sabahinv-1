<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#bulletin_board_name").val('');
	$("#bulletin_board_desc").val('');
	$("#bulletin_board_status").val('');
	$("#frmbulletin").validationEngine('hideAll');
	$("#hdnbulletin_board_id").val(0);
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savebulletin()
{
	showloading();
	var bulletin_board_name=$("#bulletin_board_name").val();
	var bulletin_board_status=$("#bulletin_board_status").val();
	var bulletin_board_desc=$("#bulletin_board_desc").val();
	var hdnbulletin_board_id=$("#hdnbulletin_board_id").val();
	var bulletin_board_start_dt = $("#bulletin_board_start_dt").val();
	var bulletin_board_end_dt = $("#bulletin_board_end_dt").val();
	var params="bulletin_board_id="+hdnbulletin_board_id+"&bulletin_board_name="+bulletin_board_name+"&bulletin_board_desc="+bulletin_board_desc+"&bulletin_board_status="+bulletin_board_status+"&bulletin_board_start_dt="+bulletin_board_start_dt+"&bulletin_board_end_dt="+bulletin_board_end_dt+"&unq="+ajaxunq();
	
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>bulletin/savebulletin",
			data	:	params,
			success	:	function (data){
				showbulletinlist();
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}
function showbulletinlist(){
	$("#bulletinlist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getbulletinlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>bulletin/getbulletinlist",
			data	:	params,
			success	:	function (data){
				$("#bulletinlist").css({'display':'none'});
				$("#bulletinlist").html(data);
				$("#bulletinlist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}
function showBulletinDetails(bulletin_board_id){
	showloading();
	cancel();
	var params='bulletin_board_id='+bulletin_board_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>bulletin/getbulletindetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#bulletin_board_name").val(dt.bulletin_board_name);
				$("#bulletin_board_desc").val(dt.bulletin_board_desc);
				$("#hdnbulletin_board_id").val(dt.bulletin_board_id);
				$("#bulletin_board_start_dt").val(dt.bulletin_board_start_dt);
				$("#bulletin_board_end_dt").val(dt.bulletin_board_end_dt);
				hideloading();
				}								
		});//end  ajax		
}	
function changeStatus(bulletin_board_id,status){
	showloading();
		var params="bulletin_board_id="+bulletin_board_id+"&status="+status+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>bulletin/changestatus",
				data	:	params,
				success	:	function (data){
					showbulletinlist();
					hideloading(data);
					}								
			});//end  ajax
}

function deleteBulletin(bulletin_board_id){
	if(confirm("Are you sure to delete this bulletin ?")){
		showloading()
		var params="bulletin_board_id="+bulletin_board_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>bulletin/deletebulletin",
				data	:	params,
				success	:	function (data){
					showbulletinlist();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
</script>