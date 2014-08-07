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
	showBills();
});
	
function closeform()
{
	window.location='<?php echo base_url();?>bills';
}

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

</script>
