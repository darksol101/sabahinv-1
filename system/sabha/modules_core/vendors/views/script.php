<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});
function saveVendor(){
	showloading();
	var vendor_id=$("#vendor_id").val();
	var vendor_name=$("#name").val();
	var vendor_address=$("#address").val();
	var vendor_phone=$("#phone").val();
	var vendor_contact=$("#contact").val();
	
	var params="vendor_id="+vendor_id+"&vendor_name="+vendor_name+"&vendor_address="+vendor_address+"&vendor_contact="+vendor_contact+"&vendor_phone="+vendor_phone+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>vendors/savevendor",
			data	:	params,
			success	:	function (data){
				cancelform();
				showVendorList();
				hideloading(data);
				}								
		});//end  ajax
}
function cancelform()
{
	$("#addVendor").validationEngine('hideAll');
	$("#name").val('');
	$("#address").val('');
	$("#phone").val('');
	$("#contact").val('');
	$("#vender_id").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".message_text").html('');
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}

function showVendorList(){
	cancelform();
	showloading();
	var namesearch=$("#namesearch").val();
	var currentpage = $("#currentpage").val();
	var phnsearch = $("#phnsearch").val();
	
	var params="currentpage="+currentpage+"&namesearch="+namesearch+"&phnsearch="+phnsearch+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>vendors/getvendorlist",
			data	:	params,
			success	:	function (data){
				$("#vendorlist").html(data);
				$("#vendorlist").hide();
				$("#vendorlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function editvendor(vendor_id){
	showloading();
	var params='vendor_id='+vendor_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>vendors/getvendordetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')')
				$("#name").val(dt.vendor_name);
				$("#phone").val(dt.vendor_phone);
				$("#address").val(dt.vendor_address);
				$("#contact").val(dt.vendor_contact_number);
				$("#vendor_id").val(dt.vendor_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax
}

function deletevendor(vendor_id){	
	if(confirm("Are you Sure to delete this vendor")){
		showloading()
		var params="vendor_id="+vendor_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>vendors/deletevendor",
				data	:	params,
				success	:	function (data){
					showVendorList();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
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

function downloadreport(){
	var phnsearch=$("#phnsearch").val();
	var namesearch=$("#namesearch").val();
	
	var params = "phnsearch="+phnsearch+"&namesearch="+namesearch+'&unq='+ajaxunq();
	$.ajax({
		type : "POST",
		url : "<?php echo base_url();?>vendors/generateexcel",
		data : params,
		
		success : function(data){
				
	document.fname.action='<?php echo site_url();?>vendors/create_excel';
    document.fname.submit();

	}
	});
}

</script>
