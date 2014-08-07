<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})

function getclosedcallreportslist(){
	loading('closedcallreportslist');
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var sc_id=$("#sc_select").val();
	
	var params="ajaxaction=getclosedcallreportslist&from_date="+from_date+"&to_date="+to_date+"&sc_id="+sc_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/closedcallreports/getclosedcallreportslist",
			data	:	params,
			success	:	function (data){
				$("#closedcallreportslist").css({'display':'none'});
				$("#closedcallreportslist").html(data);
				$("#closedcallreportslist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function email_pop(){
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>reports/dailyservicereport/getemailform', {unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function sendemail(){
	loading("loading1");
	var total_closed_calls = $("#total_closed_calls").val();
	var average_closing = $("#average_closing").val();
	var closed_calls = $("#closed_calls").val();
	var closed_calls_between1 = $("#closed_calls_between1").val();
	var closed_calls_between2 = $("#closed_calls_between2").val();
	var closed_calls_greater = $("#closed_calls_greater").val();
	
	var email_to = $("#email_to").val();
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var service_center_name = $("#service_center_name").val();
	var params = 'sendmail=sendmail&email_to='+email_to+'&from_date='+from_date+'&to_date='+to_date+'&service_center_name='+service_center_name+'&total_closed_calls='+total_closed_calls+'&average_closing='+average_closing+'&closed_calls='+closed_calls+'&closed_calls_between1='+closed_calls_between1+'&closed_calls_between2='+closed_calls_between2+'&closed_calls_greater='+closed_calls_greater+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/closedcallreports/sendclosedcallreport",
			data	:	params,
			success	:	function (data){
				hideloading(data);
				$(document).trigger('close.facebox');
				}								
		});//end  ajax
}
function export_exl()
{
	var dt = $("#tblgrid").html();
	var params="dt="+encodeURIComponent(dt)+"&unq="+ajaxunq();
	var fileUrl='<?php echo site_url()?>reports/closedcallreports/generateexlreport?'+params;
	window.location.replace(fileUrl);
	return false;
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>