<link rel="stylesheet"
	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script
	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js"
	type="text/javascript"></script>
<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})




function getengineer(sc_id){
	//alert (sc_id);
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/engineerreport/getengineerbysc",
			data	:	params,
			success	:	function (data){
				$("#engineer_id").html(data);
				}								
		});//end  ajax
}
function getreportslist(){
	$("#reportslist").slideUp('slow');
	loading('loading');
	var sc_id=$("#sc_select").val();
	var engineer_id =$("#engineer_id").val();
	var fromdate = $("#fromdate").val();
	var todate = $("#todate").val();
	//alert(engineer_id);
	var params="sc_id="+sc_id+'&engineer_id='+engineer_id+"&todate="+todate+"&fromdate="+fromdate+"&unq="+ajaxunq();
	if(engineer_id !=null){
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/engineerreport/getreportslist",
			data	:	params,
			success	:	function (data){
				$("#reportslist").hide();
				$("#reportslist").html(data);
				$("#reportslist").slideDown('slow');
				hideloading();
				}								
		});}
	hideloading();
}

function alerter(sc_id){
	alert (sc_id);
	var params = 'sc_id='+brand_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/dailyservicereport/getproductsbybrands",
			data	:	params,
			success	:	function (data){
				$("#product_box").html(data);
				}								
		});//end 
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
	document.fname.action='<?php echo site_url()?>reports/dailyservicereport/generateexlreport';
	var json = JSON.stringify(AoA);
	$("#json").val(json);
    document.fname.submit();
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
			type	:	"POST",
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

function  getEngineerByScId(sc_id){
	
	}
</script>
