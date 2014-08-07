<script type="text/javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});						   
	 showOrderList();
});
function showOrderList(){
	showloading();
	var searchtxt=$("#searchtxt").val();
	var fromdate=$("#fromdate").val();
	var todate=$("#todate").val();
	var order_status=$("#order_status").val();
	var currentpage = $("#currentpage").val();
	var sc_id = $("#sc_id").val();
	var params='fromdate='+fromdate+'&todate='+todate+"&sc_id="+sc_id+"&currentpage="+currentpage+"&searchtxt="+searchtxt+"&order_status="+order_status+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>badparts/badparts_order/getorderlist",
			data	:	params,
			success	:	function (data){
				$("#orderlist").html(data);
				$("#orderlist").hide();
				$("#orderlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
</script>