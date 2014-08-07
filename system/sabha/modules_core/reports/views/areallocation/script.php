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

function getreportslist(){
	$("#reportslist").slideUp('slow');
	loading('loading');
	var sc_id=$("#sc_select").val();
	var params="sc_id="+sc_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/areallocationreport/getreportslist",
			data	:	params,
			success	:	function (data){
				$("#reportslist").hide();
				$("#reportslist").html(data);
				var cls = '';
				$('#reportslist table#tbldata tbody tr').each(function(index) {
				    cls = ($(this).attr('class'));
				    cls=cls.replace("odd","");
				    cls=cls.replace("even","");
				    cls=cls.replace(" ","");
				    var i=0;
				   $('.'+cls).each(function(index) {
						i++;
					});
				   document.getElementById(cls).setAttribute('rowspan',i);
				});
				$("#reportslist").slideDown('slow');
				console.log('Ajax called successfully');
				hideloading();
				}								
		});
}
function converttotable(data){
}
function export_exl()
{
	var data = $('table#tbldata').html();
	var AoA = $('table#tbldata tr').map(function(){
    return [
        $('td',this).map(function(){
            return $(this).text()+'_'+$(this).attr('rowspan');
        }).get()
    	];
	}).get();
   document.fname.action='<?php echo site_url()?>reports/areallocationreport/generateexlreport';
   var json = JSON.stringify(AoA);
   $("#json").val(json);
   document.fname.submit();
}
function email_pop(){
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>reports/areallocationreport/getemailform', {unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function sendemail(){
	loading("loading1");
	var email_to = $("#email_to").val();
	var service_center = $("#service_center").val();
	var report_dt = $("#report_dt").val();
	var AoA = $('table#tbldata tr').map(function(){
    return [
        $('td',this).map(function(){
            return $(this).text();
        }).get()
    	];
	}).get();
	
   var json = JSON.stringify(AoA);
   var params="email_to="+email_to+"&sendmail=sendmail&json="+json+"&service_center="+service_center+"&report_dt="+report_dt+"&unq="+				ajaxunq();
		$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/areallocationreport/sendallocationreport",
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
