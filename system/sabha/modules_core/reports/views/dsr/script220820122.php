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

function getreportslist(){
	$("#reportslist").slideUp('slow');
	loading('loading');
	var sc_id=$("#sc_select").val();
	
	var params="sc_id="+sc_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/dailyservicereport/getreportslist",
			data	:	params,
			success	:	function (data){
				$("#reportslist").hide();
				$("#reportslist").html(data);
				$("#reportslist").slideDown('slow');
				hideloading();
				}								
		});
}
function export_exl()
{
	var AoA = $('table#tbldata tr').map(function(){
    return [
        $('td',this).map(function(){
            return $(this).text();
        }).get()
    	];
	}).get();
	var url='<?php echo site_url()?>reports/dailyservicereport/generateexlreport';
	var json = JSON.stringify(AoA);
	var params = 'json='+json+'&excel_download=excel_download&unq='+ajaxunq();
	var fileUrl='<?php echo site_url()?>reports/dailyservicereport/generateexlreport?'+params;
	window.location.replace(fileUrl);
}
function email_pop(){
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>reports/dailyservicereport/getemailform', {unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function sendemail(){
	loading("loading1");
	var email_to = $("#email_to").val();
	var service_center_name = $("#service_center_name").val();
	var report_dt = $("#report_dt").val();
	var AoA = $('table#tbldata tr').map(function(){
    return [
        $('td',this).map(function(){
            return $(this).text();
        }).get()
    	];
	}).get();
	var url='<?php echo site_url()?>reports/dailyservicereport/generateexlreport';
	var json = JSON.stringify(AoA);
	
	var params = 'service_center_name='+service_center_name+'&report_dt='+report_dt+'&json='+json+'&email_to='+email_to+'&sendmail=sendmail&unq='+ajaxunq();
	$.ajax({			
			type	:	"GET",
			url		:	"<?php echo site_url();?>reports/dailyservicereport/senddailyreport",
			data	:	params,
			success	:	function (data){
				hideloading(data);
				$(document).trigger('close.facebox');
				}								
		});//end  ajax
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>