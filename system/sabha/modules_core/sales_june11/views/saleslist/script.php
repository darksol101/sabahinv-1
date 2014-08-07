<script type="text/javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});						   
	 showSalesList();
});
function showSalesList(){
	showloading();
	var searchtxt=$("#searchtxt").val();
	var fromdate=$("#fromdate").val();
	var todate=$("#todate").val();
	var sales_status=$("#sales_status").val();
	var currentpage = $("#currentpage").val();
	var sc_id = $("#sc_id").val();
	var params='fromdate='+fromdate+'&todate='+todate+"&sc_id="+sc_id+"&currentpage="+currentpage+"&searchtxt="+searchtxt+"&sales_status="+sales_status+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>sales/getSalesList",
			data	:	params,
			success	:	function (data){
				$("#saleslist").html(data);
				$("#saleslist").hide();
				$("#saleslist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
</script>