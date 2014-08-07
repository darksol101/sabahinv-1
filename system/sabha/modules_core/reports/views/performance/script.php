<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jscharts.js"></script>
<style>
table td img.ui-datepicker-trigger { padding-left:5px;}
#performancereportslist{ position:relative; padding-top:5px;}
#performancereportslist .loading{position:absolute; left:0px; top:0; width:100%; height:30px; margin:0 auto; text-align:center;}
</style>

<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});
function getcallcenter_reportlist(){
	loading('performancereportslist');
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var params="from_date="+from_date+"&to_date="+to_date+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/performance/getperformancereportslist",
			data	:	params,
			success	:	function (data){
				$("#performancereportslist").html(data);
				hideloading();
				}								
		});//end  ajax
}
function getengineers_reportlist(){
	loading('performancereportslist');
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var sc_id = $("#sc_id").val();
	var selected_text = $('#sc_id :selected').text();
	var params="sc_id="+sc_id+"&selected_text="+selected_text+"&from_date="+from_date+"&to_date="+to_date+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/performance/getengineerperformance",
			data	:	params,
			success	:	function (data){
				$("#performancereportslist").html(data);
				hideloading();
				}								
		});//end  ajax
}
function loading(selector)
{
	$("#"+selector).html('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>