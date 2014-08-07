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
	var currentpage = $("#currentpage").val();
	var params='fromdate='+fromdate+'&todate='+todate+"&currentpage="+currentpage+"&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>orders/getorderlist",
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