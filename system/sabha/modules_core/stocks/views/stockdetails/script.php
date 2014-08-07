<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script language="javascript">
$(function() {
	$( ".datepicker" ).datepicker({
		buttonText:'English Calendar',
		changeMonth: true,
		changeYear: true,
		showOn: "button",
		buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
	});
});
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	showstockDetails();
});
function showstockDetails(){
	showloading();
	$("#stocklistdetail").hide();
	var sc_id = $('#sc_id').val();
	
	var company_id= $("#company_id").val();
	var part_number = $('#part_number').val();
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var params='from_date='+from_date+'&to_date='+to_date+'&sc_id='+sc_id+'&part_number='+part_number+'&company_id='+company_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>stocks/showstockdetails",
			data	:	params,
			success	:	function (data){
				$("#stocklistdetail").html(data);
				$("#stocklistdetail").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
</script>