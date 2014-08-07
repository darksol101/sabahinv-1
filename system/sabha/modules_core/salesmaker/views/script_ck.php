<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">

function showSalesMakerList() {
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage = $("#currentpage").val();
	var params="currentpage="+currentpage+"&searchtxt="+searchtxt+"&unq="+ajaxunq();

	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>salesmaker/getsalesmakerlist",
			data	:	params,
			success	:	function (data){
			
				$("#partlist").html(data);
				$("#partlist").hide();
				$("#partlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}

function getassignedlist(val) {
	alert(val);
}

function saveSalesMaker () {	
	var sale_name = $("#sale_name").val();
	var sale_maker_assign = $("#sale_maker_assign").val();
	var sale_maker_options = $("#assign_to select").val();
	var salestatus = $("#salestatus").val();
	var deductiontype = $("#deductiontype").val();
	var deduction_value = $("#deduction_value").val();
	var issue_date = $("#issue_date").val();
	var expire_date = $("#expire_date").val();
	var salesmaker_action = $("#salesmaker_action").val();


	var params="salesmaker_action="+salesmaker_action+"&expire_date="+expire_date+"&sale_name="+sale_name+"&sale_name="+sale_name+"&sale_maker_assign="+sale_maker_assign+"&sale_maker_options="+sale_maker_options+"&salestatus="+salestatus+"&deductiontype="+deductiontype+"&deduction_value="+deduction_value+"&issue_date="+issue_date+"&unq="+ajaxunq();

	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>salesmaker/save_salesmaker",
			data	:	params,
			success	:	function (data){
				showSalesMakerList();
				hideloading(data);
				}								
		});//end  ajax
}




</script>
