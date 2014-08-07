<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	showPurchaseList();
});

function showPurchaseList(){
	showloading();
	$("#purchaselist").hide();
	var currentpage=$("#currentpage").val();
	var searchtxt=$("#searchtxt").val();
	var fromdate=$("#fromdate").val();
	var todate=$("#todate").val();
	var vendor_name=$("#vendor_name").val();
	var purchase_status=$("#purchase_status").val();
	var vendor_id = $('#vendor_id').val();
	
	var params='vendor_id='+vendor_id+'&fromdate='+fromdate+'&todate='+todate+'&vendor_name='+vendor_name+'&purchase_status='+purchase_status+"&currentpage="+currentpage+"&searchtxt="+searchtxt+"&unq="+ajaxunq();
	$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>purchase/showpurchaselist",
				data	:	params,
				success	:	function (data){
					hideloading();
					$("#purchaselist").html(data);
					$("#purchaselist").slideDown('slow');
					hideloading();
					}								
			});//end  ajax
}
function deletepurchase(purchase_id){
	if(confirm("Are you sure to delete this Purchase ?")){
		//showloading()
		var params="purchase_id="+purchase_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>purchase/deletepurchase",
				data	:	params,
				success	:	function (data){
					hideloading(data);
					showPurchaseList();
					}								
			});//end  ajax
	}else{
		return false
	}
}

</script>
