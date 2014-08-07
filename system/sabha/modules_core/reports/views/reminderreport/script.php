<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	$("#call_id").val(0);
	$("#brand_id").val(0);
	$("#product_id").val(0);
})

function getreminderlist(){
	loading('reminderreportlist');
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var call_uid=$("#call_uid").val();
	var sc_id=$("#sc_id").val();
	var product_id=$("#product_id").val();
	
	var params="&call_uid="+call_uid+"&product_id="+product_id+"&sc_id="+sc_id+"&from_date="+from_date+"&to_date="+to_date+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/reminderreport/getreminderlist",
			data	:	params,
			success	:	function (data){
				$("#reminderreportlist").css({'display':'none'});
				$("#reminderreportlist").html(data);
				$("#reminderreportlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function email_pop(){
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>reports/reminderreport/getemailform', {unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function sendemail(){
	loading("loading1");
	var AoA = $('table#tbldata tr').map(function(){
    return [
        $('td',this).map(function(){
            return $(this).text();
        }).get()
    	];
	}).get();
	var json = JSON.stringify(AoA);	
	var email_to = $("#email_to").val();
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var service_center_name = $("#service_center_name").val();
	var report_dt = $("#report_dt").val();
	var params = 'sendmail=sendmail&email_to='+email_to+'&from_date='+from_date+'&to_date='+to_date+'&report_dt='+report_dt+'&json='+json+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/reminderreport/sendreminderreport",
			data	:	params,
			success	:	function (data){
				hideloading(data);
				$(document).trigger('close.facebox');
				}								
		});//end  ajax
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
		document.fname.action='<?php echo site_url()?>reports/reminderreport/generateexlreport';
		var json = JSON.stringify(AoA);
		$("#json").val(json);
	    document.fname.submit();
}
function getProductBybrand(brand_id){
	loading('product_box');
	var params = 'brand_ids='+brand_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/reminderreport/getproductsbybrands",
			data	:	params,
			success	:	function (data){
				$("#product_box").html(data);
				}								
		});//end  ajax
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>