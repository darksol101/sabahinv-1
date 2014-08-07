<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	showloading();
        getadjustments();
});
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
function saveadjustment(){
    var sc_id = $('#sc_id').val();
    var part_quantity = $('#part_quantity').val();
    var part_number = $('#part_number').val();
    var params = 'sc_id='+sc_id+'&part_quantity='+part_quantity+'&part_number='+part_number+'&unq='+ajaxunq();
    $.ajax({
        type     :   "POST",
        url      :  '<?php echo base_url();?>badparts/adjustment/saveadjustment',
        data     :   params,
        success  :   function(data){
                            $('#part_number').val('');
                            hideloading(data);
                            getadjustments();
                        }
    });
}

function getadjustments(){
    showloading();
    var sc_id = $('#sc_id').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
	
    var params = 'sc_id='+sc_id+'&from_date='+from_date+'&to_date='+to_date+'&unq='+ajaxunq();
    $.ajax({
        type     :   "POST",
        url      :  '<?php echo base_url();?>badparts/adjustment/getadjustments',
        data     :   params,
        success  :   function(data){
                            $('#adjustmentslist').hide();
                            $('#adjustmentslist').html(data);
                            $('#adjustmentslist').slideDown('slow');	
                            hideloading();
                    }
    });
}
function getServiceCenterSelect(from_sc_id){
	loading("from_sc_box");
	var params = 'sc_id='+from_sc_id+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>parts/bad_parts/getservicecenters",
		data	:	params,
		success	:	function (data){
						$('#from_sc_box').html(data);
						hideloading();
				}								
		});
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
function closeform()
{
	window.location='<?php echo base_url();?>badparts/transfer';
}
</script>