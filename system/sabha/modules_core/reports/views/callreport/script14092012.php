<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<link
	rel="stylesheet"
	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script
	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js"
	type="text/javascript"></script>
<script
	src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js"
	type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$( ".datepicker" ).datepicker({
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
		});
});
function generateCallReport()
{
	showloading();
	var currentpage = $("#currentpage").val();
	var cid = $("#call_uid").val();
	var phone = $("#phone").val();
	//var serial_number = $("#serial_number").val();
	var from = $("#from_date").val();
	var to = $("#to_date").val();
	var eg = $("#engineer").val();
	var sc = $("#scenter").val();
	var cn = $("#cust_name").val();
	var ph = $("#phone").val();
	var sn = $("#serial_number").val(); 
	var cs = calculateChecked(".status");
	var products = $("#product_id").val();
	
	
	var params = 'cid='+cid+'&cn='+cn+'&ph='+ph+'&sn='+sn+'&from='+from+'&to='+to+'&eg='+eg+'&sc='+sc+'&cs='+cs+'&products='+products+'&currentpage='+currentpage+'&unq='+ajaxunq();
		$.ajax({			
			type	:	"GET",
			url		:	"<?php echo base_url();?>reports/callreport/generatecallreport",
			data	:	params,
			success	:	function (data){
							hideloading();
							$("#call_list").hide();
							$("#call_list").html(data);
							$("#call_list").slideDown('slow');
						}								
		});
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
function calculateChecked(param){
	var optionValues = [];
	$(param).each(function() { 
		if($(this).is(':checked')){
			optionValues.push($(this).val()) 
		}
	 });
	return optionValues;
}
</script>
