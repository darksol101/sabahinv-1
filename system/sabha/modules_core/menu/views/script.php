<?php defined('BASEPATH') or die('Direct access script is allowed');
?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/tablesorter.js"></script>
<script language="javascript">
$(document).ready(function(){
	showTable();
	$('#uploadForm').submit(function() {
		return true;
	});

/*	$("[class^=validate]").validationEngine({
		success :  function() { 		},
		failure : function() {}
	});
*/})
function showTable(){
	showloading();
	var searchtxt=$("#searchtxt").val()
	var filtercat=$("#filtercategory").val()
	var params="ajaxaction=getuploadlist&searchtxt="+searchtxt+"&filtercat="+filtercat+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"uploadfile/getfilelist",
			data	:	params,
			success	:	function (data){
				$("#filetablelist").html(data)
				$("table").tablesorter({headers: {0: {sorter: false},4: {sorter: false}}}); 
				hideloading()
				}								
		});//end  ajax
	}	

function delet(id){
	
	if(confirm("Are you Sure to delete this File")){
		showloading()
		var params="id="+id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"uploadfile/deletefile",
				data	:	params,
				success	:	function (data){
					showTable();
					showmessage(data)
					}								
			});//end  ajax
	}else{
		return false
	}
}
function showCategory(id)
{
	var params="id="+id+"&task=getcategory&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"uploadfile/getcategory",
				data	:	params,
				success	:	function (data){
					$('#listcategory').html(data);
					}								
			});//end  ajax
}
function changeCategory(id)
{
	var cat_id = $('#change_category').val();
	var params="id="+id+"&cat_id="+cat_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"uploadfile/changeCategory",
				data	:	params,
				success	:	function (data){
					showmessage(data);
					showTable();
					}								
			});//end  ajax
}
function showImage(file)
{
	window.open('<?php echo base_url();?>uploads/Uploadfiles/'+file,'files','toolbar=0,status=0,width=626,height=436');
}
function cancel(){
	$("#username").val("");
	$("#userid").val("");
	$("#email").val("");
	$("#password").val("");
	$("#rpassword").val("");
	$("#status").val(0);	
	$("#usergroup").val(0);
	$("#hdnuserid").val(0);
	$(".formError").remove();
}
function ajaxunq(){
	var d = new Date();
    var unq = d.getYear()+''+d.getMonth()+''+d.getDay()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds();
	return unq;
	}
function showloading(){
	$(".loading").show();
	}
function hideloading(){
	$(".loading").hide();
	}
function showmessage(msg){
	$(".msg").html(msg).show().delay(5000).fadeOut("slow");
	
	}
</script>