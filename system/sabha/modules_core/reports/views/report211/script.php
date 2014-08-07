<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});



function getEngineerBySvc(sc_id){
	//loading("engineer_box");
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>reports/report211/getengineersbysc",
		data	:	params,
		success	:	function (data){
						$('#engineer_box').html(data);
						hideloading();
				}								
		});
}



function getreport211(){
	
	var sc_id = $('#sc_id').val();
	var engineer_id= $('#engineer_id').val();
	var fromdate = $('#fromdate').val();
	var todate= $('#todate').val();
	var currentpage = $("#currentpage").val();
	var start = parseInt(currentpage);
	
	var params = 'sc_id='+sc_id+'&engineer_id='+engineer_id+'&fromdate='+fromdate+'&todate='+todate+'&currentpage='+currentpage+'&start='+start;
	
	//alert (params);
	
	$.ajax({
		   			
		type	:	"POST",
		url		:	"<?php echo base_url();?>callcenter/report211",
		data	:	params,
		success	:	function (data){
						$('#report211list').html(data);
						$("#report211list").hide();
						$("#report211list").slideDown('slow');
						//hideloading();
				}								
		 });
	}
	
	
	function excelDownload(){
	var sc_id = $('#sc_id').val();
	var engineer_id= $('#engineer_id').val();
	var fromdate = $('#fromdate').val();
	var todate= $('#todate').val();
		
	var params = 'sc_id='+sc_id+'&engineer_id='+engineer_id+'&fromdate='+fromdate+'&todate='+todate;
	
	//alert (params);
	
	$.ajax({
		   			
		type	:	"POST",
		url		:	"<?php echo base_url();?>reports/report211/exceldownload",
		data	:	params,
		success	:	function (data){
				var fileUrl='<?php echo site_url()?>reports/report211/create_excel';
				window.location.replace(fileUrl);
						//hideloading();
				}								
		 });
		
		}

</script>