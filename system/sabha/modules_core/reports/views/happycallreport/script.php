<style>
.error_type
{ color:red; border:}
</style>
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
    
<script type="text/javascript">

function showhappycalllist()
{		
	var fromdate = $("#fromdate").val();
	var todate=$("#todate").val();
	var text=$("#searchtxt").val();
	
	//alert(fromdate);
	if(fromdate == '')
	
	{ //$('#error_msg1').text("From date field is empty").addClass('error_type').show().fadeOut(6000);
	alert("From date field is required");
	return false;
	}
	if(todate == '')
	//$('#error_msg2').text("To date field is empty").addClass('error_type').show().fadeOut(6000);
	{ alert("To date field is required");
	return false;
	}
		
	var x=fromdate.split("/");
	var y=todate.split("/");
	
	var fromdate_new=(x[1]+"/"+x[0]+"/"+x[2]);
	var todate_new=(y[1]+"/"+y[0]+"/"+y[2]);
	var date1=new Date(fromdate_new);
	var date2=new Date(todate_new);
	var oneDay=1000*60*60*24;
    var result = (Math.ceil((date2.getTime()-date1.getTime())/oneDay));
	
	if(result<=30 && result>=0) {
	showhappycalllist_result();}
	else{ alert ("date range shouldn't exceed a month");
	return false;}
	
	}
				
function showhappycalllist_result(){
	 
	showloading();
	var fromdate = $("#fromdate").val();
	var todate=$("#todate").val();
	var text=$("#searchtxt").val();
	var sc_id=$("#sc_id").val();
	var product_id=$("#product_id").val();
	var currentpage=$('#currentpage').val();
	var params = 'fromdate='+fromdate+'&todate='+todate+'&text='+text+'&sc_id='+sc_id+'&product_id='+product_id+'&currentpage='+currentpage+'&unq='+ajaxunq();;
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/happycall/happycallreport",
			data	:	params,
			success	:	function (data){
				$("#happycalllist").html(data);
				$("#happycalllist").hide();
				$("#happycalllist").slideDown('slow');
				hideloading(data);
				}							
		});
	}
	
	function getProductBybrand(brand_id){
	//loading('product_box');
	var params = 'brand_ids='+brand_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/happycall/getproductsbybrands",
			data	:	params,
			success	:	function (data)
			{
				$("#product_box").html(data);
				}								
		});
}

function excelDownload()
{
	var fromdate = $("#fromdate").val();
	var todate=$("#todate").val();
	var text=$("#searchtxt").val();
	var sc_id=$("#sc_id").val();
	var product_id=$("#product_id").val();
	var currentpage=$('#currentpage').val();
	var params = 'fromdate='+fromdate+'&todate='+todate+'&text='+text+'&sc_id='+sc_id+'&product_id='+product_id+'&currentpage='+currentpage+'&unq='+ajaxunq();;
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/happycall/create_excel",
			data	:	params,
			success	:	function (data){
				$("#happycalllist_excel").html(data);
				
				$(function(){	   
									   
		var data='<table>'+$("#happycalllist_excel").html().replace(/<a\/?[^>]+>/gi, '')+'</table>';
		$('body').prepend("<form method='post' action='<?php echo base_url();?>reports/happycall/exceldownload' style='display:none' id='happycalllist_excel'><input type='text' 				name='tableData' value='"+data+"' ></form>");
		 $('#happycalllist_excel').submit().remove();
		 return false;


});
				
				
								}							
		});
	}
	



</script>
