<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});
function saveCompany(){
	showloading();
	var company_id=$("#company_id").val();
	var company_name=$("#name").val();
	var company_desc=$("#desc").val();
	var company_phone=$("#phone").val();
	var company_address=$("#address").val();
	//alert (company_desc);
	var params="company_id="+company_id+"&company_name="+company_name+"&company_address="+company_address+"&company_desc="+company_desc+"&company_phone="+company_phone+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>company/savecompany",
			data	:	params,
			success	:	function (data){
				cancelform();
				showCompanyList();
				hideloading(data);
				}								
		});//end  ajax
}
function cancelform()
{
	$("#addCompany").validationEngine('hideAll');
	$("#name").val('');
	$("#address").val('');
	$("#phone").val('');
	$("#desc").val('');
	$("#vender_id").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".message_text").html('');
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}

function showCompanyList(){
	cancelform();
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage = $("#bcurrentpage").val();
	var params="currentpage="+currentpage+"&ajaxaction=getbrandlist&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>company/getcompanylist",
			data	:	params,
			success	:	function (data){
				$("#companylist").html(data);
				$("#companylist").hide();
				$("#companylist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function editcompany(company_id){
	showloading();
	var params='company_id='+company_id+'&unq='+ajaxunq();
	//alert (params);
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>company/getcompanydetail",
			data	:	params,
			success	:	function (data){
				//alert (data);
				var dt=eval('(' + data + ')')
				$("#name").val(dt.company_title);
				$("#phone").val(dt.phone);
				$("#address").val(dt.address);
				$("#desc").val(dt.company_desc);
				$("#company_id").val(dt.company_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax
}

function deletecompany(company_id){	
	if(confirm("Are you Sure to delete this company")){
		showloading()
		var params="company_id="+company_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>company/deletecompany",
				data	:	params,
				success	:	function (data){
					showCompanyList();
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
	
	
	function addcompanypart(company_id)
{
	$.facebox(function() { 
		  $.post('<?php echo site_url();?>company/selectpart', { company_id:company_id,unq:ajaxunq()}, 
			function(data) {
				$.facebox(data);
				//var brands = calculateChecked(".brand");
				//$("#product_box").html('');
				//getProducts(brands);
			});
	    });
}
	
	

</script>
