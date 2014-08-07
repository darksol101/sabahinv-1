<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showeventslist();
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function showeventslist(){
	$("#bulletinlist").slideUp('slow');
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getbulletinlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>events/geteventslist",
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
</script>