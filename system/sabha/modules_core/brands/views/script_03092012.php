<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	/*$('.content-box ul.content-box-tabs li a').click(function(){
		$("#moduletitle").html(this.innerHTML);
		$(".tab-content").html('');
		var arr = (this.href).split("#",2);
		loadTab(this.id,arr[1]);
	});*/
});
function loadTab(tab,contentdiv){
	var params="unq="+ajaxunq();
	$.ajax({			
			type	:	"GET",
			url		:	tab,
			data	:	params,
			success	:	function (data){
					$('#'+contentdiv).hide();
					$('#'+contentdiv).html(data);
					$('#'+contentdiv).slideDown('slow');
				}				
	});
}
function saveBrand(){
	showloading();
	var id=$("#hdnid").val();
	var brandname=$("#brandname").val();
	var status=$("#brand_status").val();
	var description=$("#description").val();
	$("#brandForm").validationEngine('attach');	
	if(brandname==''){
		$('#brandname').validationEngine('showPrompt', 'Brand Name is required', '');
		return false;
	}
	var params="id="+id+"&brandname="+brandname+"&status="+status+"&description="+description+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>brands/savebrand",
			data	:	params,
			success	:	function (data){
				cancelform();
				showBrandList();
				hideloading(data);
				}								
		});//end  ajax
}
function cancelform()
{
	$("#brandForm").validationEngine('hideAll');
	$("#brandname").val('');
	$("#description").val('');
	$("#brand_status").val('');
	$("#hdnid").val(0);
	$(".message_text").html('');
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function editBrand(id){
	showloading();
	cancelform();
	var params='id='+id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"brands/getbrandetails",
			data	:	params,
			success	:	function (data){
					var dt=eval('(' + data + ')');
					$("#brandname").val(dt.brand_name);
					$("#description").val(dt.brand_desc);
					$("#brand_status").val(dt.brand_status);
					$("#hdnid").val(dt.brand_id);
					hideloading();
			}								
	});//end  ajax	
}
function showBrandList(){
	cancelform();
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage = $("#bcurrentpage").val();
	var params="currentpage="+currentpage+"&ajaxaction=getbrandlist&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>brands/getbrandlist",
			data	:	params,
			success	:	function (data){
				$("#brandlist").html(data);
				$("#brandlist").hide();
				$("#brandlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function changestatus(brand_id,status){
	showloading();
	var params="brand_id="+brand_id+"&status="+status+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>brands/changestatus",
			data	:	params,
			success	:	function (data){
				showBrandList();
				hideloading(data);
				}								
	});//end  ajax
}
function deleteBrand(brand_id){
	if(confirm("Are you Sure to delete this Brand")){
		showloading();
		var params="brand_id="+brand_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>brands/deletebrand",
				data	:	params,
				success	:	function (data){
					showBrandList();
					hideloading(data);
					}								
			});//end  ajax
	}
}
function ajaxunq(){
	var d = new Date();
    var unq = d.getYear()+''+d.getMonth()+''+d.getDay()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds();
	return unq;
	}
function showloading(){
	$(".loading").show();
	}
function hideloading($msg){
	$(".loading").hide();
	if($msg){$('.message_text').show();$(".message_text").html($msg);}
	if($msg)$('.message_text').delay(10000).fadeOut("slow");
	}

</script>