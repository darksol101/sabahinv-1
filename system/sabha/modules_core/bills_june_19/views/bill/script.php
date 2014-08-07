<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script language="javascript">


var sn;
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});
	
function showBills(){
	showloading();
	$("#billslist").hide();
	var currentpage=$("#currentpage").val();
	var searchtxt=$("#searchtxt").val();
	var fromdate=$("#fromdate").val();
	var todate=$("#todate").val();
	var sc_id = $("#sc_id").val();
	var bill_status=$("#bill_status").val();
	var bill_type = $("#bill_type").val();
	
	var params='fromdate='+fromdate+'&todate='+todate+'&bill_type='+bill_type+'&sc_id='+sc_id+'&bill_status='+bill_status+"&currentpage="+currentpage+"&searchtxt="+searchtxt+"&unq="+ajaxunq();
	$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>bills/getBillsList",
				data	:	params,
				success	:	function (data){
					hideloading();
					$("#billslist").html(data);
					$("#billslist").slideDown('slow');
					hideloading();
					}								
			});//end  ajax 
}
function printbill(bill_id){
	
	$.facebox(function() { 
		$.post('<?php echo site_url();?>bills/printbill',{bill_id:bill_id}, 
		function(data) { $.facebox(data) });
	})	
}
function printordercard()
{
	var bill_id = $('#bill_id').val();
	var content = document.getElementById("cardContent");
	var pri = document.getElementById("ifmcontentstoprint").contentWindow;
	pri.document.open();
	pri.document.write(content.innerHTML);
	pri.document.close();
	pri.focus();
	pri.print();
	confirmprint(bill_id);
	$(document).trigger('close.facebox');
}
function confirmprint(bill_id){
	var params="bill_id="+bill_id;

	$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>bills/confirmPrint",
					data	:	params,
					success	:	function (data){
											
					}});


	
}
function cancelBill(bill_id){
	var params="bill_id="+bill_id;

	$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>bills/cancelBill",
					data	:	params,
					success	:	function (data){
											
					}});
}
function cancelGeneratedBill(){
		var cancelbill = document.cancelbill;
	if(confirm('Are you sure to cancel this bill?')){
		cancelbill.submit();
	}else{
	}
}
</script>
