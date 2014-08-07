<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
//showCustomerList();
$(document).ready(function(){

});
function showCustomerList()
{
	showloading();
	var searchtxt=$("#searchtxt").val();
	var zone_id = $("#zone_select").val();
	var district_id = $("#district_select").val();
	var city_id = $("#city_select").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getcustomerlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>customers/getcustomerlist",
			data	:	params,
			success	:	function (data){
				$("#listcustomers").css({'display':'none'});
				$("#listcustomers").html(data);
				$("#listcustomers").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
</script>