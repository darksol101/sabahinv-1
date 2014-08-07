<script type="text/javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});						   
	 showcallorder();
});
function showcallorder(){
	showloading();
	var fromdate=$("#fromdate").val();
	var todate=$("#todate").val();
	var order_status=$("#order_status").val();
	var currentpage = $("#currentpage").val();
	var sc_id = $("#sc_id").val();
	var params='fromdate='+fromdate+'&todate='+todate+"&sc_id="+sc_id+"&currentpage="+currentpage+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>orders/getcallorderlist",
			data	:	params,
			success	:	function (data){
				$("#callorderlist").html(data);
				$("#callorderlist").hide();
				$("#callorderlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}

function savebulkorder(){
	var value = 0;
		$(".call_order").each(function (){
		if (document.getElementById(this.id).checked){
			 value =  parseInt(this.id)+','+value;
         }
		});
	var params='values='+value+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>orders/savebulkorder",
			data	:	params,
			success	:	function (data){
				
				alert ('Order Generated');
				window.location.href = '<?php echo site_url();?>orders';
				}								
		})
	
	}
</script>