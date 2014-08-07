<script type="text/javascript">
function showusedcalllist()
{
	
	var sc_id = $('#sc_id_search').val();
	var engineer_id = $('#engineer_select').val();
	var fromdate1= $('#fromdate').val();
	var todate1= $('#todate').val();
	if(sc_id == '')
					{
			$('#sc_id_search').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
			return false;
					}
			
			else
				{
				$("#sc_id_search").validationEngine('hideAll');
				}
	
	var x = fromdate1.split("/");
	var fromdate = x[2]+"-"+x[1]+"-"+x[0];
	var y = todate1.split("/");
	var todate = y[2]+"-"+y[1]+"-"+y[0];
	
	if(fromdate > todate)
	{
		alert("From date should be lesser than To date");
		return false;
	}
	
	var params = 'sc_id='+sc_id+'&engineer_id='+engineer_id+'&fromdate='+fromdate+'&todate='+todate+'&unq='+ajaxunq();
	$.ajax({
	   type		:		"POST",
	   url		:		'<?php echo base_url();?>reports/usedcallreport/generateReport',
	   data		:		params,
	   success	:		function(data){
		   						$('#usedcalllist').hide();
								$('#usedcalllist').html(data); 
								$('#usedcalllist').slideDown(500);
								 	 }
	   	  });
}

function getengineer(sc_id)
{
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
	
			$.ajax({
			   type		:	"POST",
			   url		: 	"<?php echo base_url();?>reports/usedcallreport/getengineer",
			   data		:	params,
			   success	:	function(data){
				  			$("#engineer_select").html(data);
											   
				   							}
			   });
		
	}
	
function downloadUsedCallReport()
{
	var sc_id = $('#sc_id_search').val();
	var engineer_id = $('#engineer_select').val();
	var fromdate1= $('#fromdate').val();
	var x = fromdate1.split("/");
	var fromdate = x[2]+"-"+x[1]+"-"+x[0];
	var todate1= $('#todate').val();
	var y = todate1.split("/");
	var todate = y[2]+"-"+y[1]+"-"+y[0];
		
	var params = 'sc_id='+sc_id+'&engineer_id='+engineer_id+'&fromdate='+fromdate+'&todate='+todate+'&unq='+ajaxunq();
	$.ajax({
	   type		:		"POST",
	   url		:		'<?php echo base_url();?>reports/usedcallreport/excel_ready',
	   data		:		params,
	   success	:		function(data){
		   
	    document.fname.action='<?php echo site_url()?>reports/usedcallreport/create_excel';
    	document.fname.submit();
		   						
								}
	   	  });
	
}
	

</script>