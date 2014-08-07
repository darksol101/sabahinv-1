<script language="javascript">
$(document).ready(function(){
});
function returnpart(){
	var sc_id = $('#sc_id').val();
	var engineer_id = $('#engineer_id').val();
	var part_number = $('#part_number').val();
	var part_quantity = $('#part_quantity').val();
	var params = 'sc_id='+sc_id+'&engineer_id='+engineer_id+'&part_number='+part_number+'&part_quantity='+part_quantity+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>badparts/transfer/returnparttosvc",
		data	:	params,
		success	:	function (data){
						hideloading(data);
				}								
		});
}
function getEngineerBySvc(sc_id){
	loading("engineer_box");
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>badparts/transfer/getengineersbysc",
		data	:	params,
		success	:	function (data){
						$('#engineer_box').html(data);
						hideloading();
				}								
		});
}
function getPartsByEngineer(engineer_id){
	loading("parts_box");
	var params = 'engineer_id='+engineer_id+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>badparts/transfer/getpartsbyengineer",
		data	:	params,
		success	:	function (data){
						$('#parts_box').html(data);
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
	window.location='<?php echo base_url();?>parts/bad_parts';
}
function hideloading($msg){
	$(".loading").hide();
	if($msg){$('.message_text').show();$(".message_text").html($msg);}
	if($msg)$('.message_text').delay(10000).fadeOut("slow");
	}
</script>