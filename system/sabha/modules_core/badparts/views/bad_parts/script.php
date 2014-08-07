<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.ui.accordion.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/jquery.ui.accordion.min.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	showloading();
});
$(window).load(function(){
	hideloading();
	//showBadPartsList();					
});
	
	function setRecords(sc_id,engineer_id,part_number)
	{
	var sc_id = sc_id;
	var engineer_id = engineer_id;
	var part_number = part_number;
	$("#sc_id").val(sc_id);
	getengineerbysc_defective_populate(sc_id,engineer_id);
	getPartsByEngineer_defective_populate(engineer_id,part_number);
	$("#sc_id").val(sc_id);
	
	}
	
	function getengineerbysc_defective_populate(sc_id,engineer_id){
	loading("engineer_box");
	var params = 'sc_id='+sc_id+'&engineer_id='+engineer_id+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>badparts/getengineerbysc_defective_populate",
		data	:	params,
		success	:	function (data){
						$('#engineer_box').html(data);
						hideloading();
				}								
		});
}

function getPartsByEngineer_defective_populate(engineer_id,part_number)
{
	loading("parts_box");
	var params = 'engineer_id='+engineer_id+'&part_number='+part_number+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>badparts/getPartsByEngineer_defective_populate",
		data	:	params,
		success	:	function (data){
						$('#parts_box').html(data);
						hideloading();
				}								
		});
}
	
	
	
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
						if (data == 1){alert ('Not Enough Parts');}
						else{
						alert('Part Returned to SVC.');
						$('#part_quantity').val('');
						$('#part_number').val('');
						//hideloading(data);
						showBadPartsList();		
						}
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
function getEngineerBySvc_search(sc_id){
	loading("engineer_box");
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>badparts/transfer/getengineersbyscsearch",
		data	:	params,
		success	:	function (data){
						$('#engineer_box_search').html(data);
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
	
	function showbadstockList(){
	showloading();
	$("#stocklist").hide();
	var sc_id = $("#sc_id").val();
	var searchtxt = $("#searchtxt").val();
       
		var currentpage=$("#currentpage").val();
		var start = parseInt(currentpage);
	var params="currentpage="+currentpage+'&searchtxt='+searchtxt+'&sc_id='+sc_id+"&start="+start+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>badparts/showDefectivestocklist",
			data	:	params,
			success	:	function (data){
				$("#badstocklist").css({'display':'none'});
				$("#badstocklist").html(data);
				$("#badstocklist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
	
	
	
function showBadPartsList(){
	$("#userlist").css({'display':'none'});
	showloading();
	var sc_id = $("#sc_id_search").val();
	var engineer_id = $("#engineer_id_search").val();
	var currentpage = $("#currentpage").val();
	var searchtxt=$("#searchtxt").val()
	var params="currentpage="+currentpage+"&searchtxt="+searchtxt+"&sc_id="+sc_id+"&engineer_id="+engineer_id+"&unq="+ajaxunq();
	//alert (params);
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>badparts/getbadparts",
			data	:	params,
			success	:	function (data){
				$("#badpartlist").css({'display':'none'});
				$("#badpartlist").html(data);
				$("#badpartlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
	}






function excelDownload(){
	
	var sc_id = $("#sc_id").val();
	var searchtxt = $("#searchtxt").val();
	
	var params='searchtxt='+searchtxt+'&sc_id='+sc_id+'&unq='+ajaxunq();
	//alert (params);
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>badparts/exceldownload",
			data	:	params,
			success	:	function (data){
				var fileUrl='<?php echo site_url()?>badparts/create_excel';
				window.location.replace(fileUrl);
				
				}								
		});

}

function getpartsbyscid(sc_id){

	var params = "sc_id="+sc_id;
	$.ajax({
		   type		:   'POST',
		   url		:   '<?php echo site_url();?>badparts/getPartsByScId',
		   data		:	params,
		   success	: 	function(data){
			   			$('#part_box').html(data);
			   
			   }
		   });
	
	}
function getCompanyByParts(part_number){
	
	var sc_id = $('#sc_id_revoke').val();
	var params = 'part_number='+part_number+'&sc_id='+sc_id;
	
	$.ajax({
		   type		:   'POST',
		   url		:   '<?php echo site_url();?>badparts/getCompanyOptionsByPart',
		   data		:	params,
		   success	: 	function(data){
			   			$('#company_box').html(data);
			   
			   }
		   });
	
	
	}
	
	
	
	
function revokeScPart(){
	
	var sc_id = $('#sc_id_revoke').val();
	var company_id =$('#company_select').val();
	var part_number = $('#part_number').val();
	var quantity = $('#part_quantity').val();
	var reason = $('#badpart_reason').val();
	var params = 'sc_id='+sc_id+"&company="+company_id+"&part_number="+part_number+"&quantity="+quantity+'&reason='+reason;
	//alert (params);
	$.ajax({
		   type		:   'POST',
		   url		:   '<?php echo site_url();?>badparts/revokeGoodPart',
		   data		:	params,
		   success	: 	function(data){
			   if (data == 1){alert ('Insufficient Good quantity.'); }
			   	else {alert('Part moved to badstock.');
							 $('#sc_id_revoke').val('');
							$('#company_select').val('');
							$('#part_number').val('');
							$('#part_quantity').val('');
							$('#badpart_reason').val('');
							showbadstockList();
			  		}
				 }
		   });
	
	
	}
	
 function showbadpartsdetails(){
	showloading();
	 var sc_id = $('#sc_id').val();
	 var part_number = $('#part_number').val();
	 var from_date = $('#from_date').val();
	
	 var to_date = $('#to_date').val();
	 var params = 'sc_id='+sc_id+'&part_number='+part_number+'&from_date='+from_date+'&to_date='+to_date;
	 $.ajax({
				type	:	"POST",
				data	:	params,
				url		:	'<?php echo base_url();?>badparts/badpartdetailslist',
				success	:	function(data){
					    $('#badpartsdetails').hide();
						$('#badpartsdetails').html(data);
						$('#badpartsdetails').slideDown('slow');
						hideloading();
				}});
			
	 
	 
}

function getengineer(sc_id){
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
	//alert(sc_id);
		$.ajax({
			   type		:	"POST",
			   url		: 	"<?php echo base_url();?>partallocation/getengineer",
			   data		:	params,
			   success	:	function(data){
				 //  alert(data);
				  			$("#engineer_select").html(data);
							$('#egr').html(data);
				   
				   }
			   });
		
	}
	
function getretrunlist()
{
	var sc_id = $('#sc_id_search').val();
	var engineer_id = $('#engineer_select').val();
	
	if(sc_id == ''){
		$('#sc_id_search').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		return false;
			}
	if(engineer_id == ''){
		$('#engineer_select').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		return false;
			}
			
	else{$("#sc_id_search").validationEngine('hideAll');
	$('#engineer_select').validationEngine('hideAll');
	}
	
var check_uri = $('#date_get').val();

if(check_uri) { 
var x = check_uri.split("-");
var newdate = x[2]+"/"+x[1]+"/"+x[0];
$('#fromdate').val(newdate);
//$('#todate').val(newdate);
}


var fromdate = $('#fromdate').val();
//var todate = $('#todate').val();
var params = 'sc_id='+sc_id+'&engineer_id='+engineer_id+'&fromdate='+fromdate;
		
showloading();
$.ajax({
	   		type	:		"POST",
			data	:		params,
			url		:		'<?php echo base_url();?>badparts/getretrunlist',
			success	:		function(data){
						$('#showreturnlist').hide();
						$('#showreturnlist').html(data);
						$('#showreturnlist').slideDown('slow');
						hideloading();
			}});

}
function downloadRetunReport()
{
	var sc_id = $('#sc_id_search').val();
	var engineer_id = $('#engineer_select').val();
	var fromdate = $('#fromdate').val();
	//var todate = $('#todate').val();
	var params = 'sc_id='+sc_id+'&engineer_id='+engineer_id+'&fromdate='+fromdate+'&unq='+ajaxunq();
$.ajax({
	   type :	"POST",
	   url	:	"<?php echo site_url();?>badparts/excel_ready",	
	   data	:	params,
	   success :	function(data){ 
	  
	    document.fname.action='<?php echo site_url()?>badparts/create_excel_return_list';
    	document.fname.submit();
	   } 
	  });
	
}
function check_checklist(){
	var value="";
	$(".return_list").each(function (){
		if (document.getElementById(this.id).checked){
			 value =  (this.id)+','+value;
			 }});

var params = 'value='+value+'&unq='+ajaxunq();;
$.ajax({
	   type :	"POST",
	   url	:	"<?php echo site_url();?>badparts/savesigned",	
	   data	:	params,
	   success :	function(data){ 
	   getretrunlist();
	   
	   } 
	  });
	}
	
function generatePrintReport()
{   
	var engineer_id = $("#engineer_select").val();
	var sc_id = $("#sc_id_search").val();
	var fromdate = $('#fromdate').val();
	var todate = $('#todate').val();
	
	if(engineer_id == ''){
		$('#engineer_select').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		return false;
			}
	
	else{$("#engineer_select").validationEngine('hideAll');}
	
	$.facebox({ ajax: '<?php echo base_url();?>badparts/printReturnList?sc_id='+sc_id+'&engineer_id='+engineer_id+'&fromdate='+fromdate+'&todate='+todate+'&unq='+ajaxunq()});
}

function printAllocationReport(){
	var content = document.getElementById("cardContent");
	var pri = document.getElementById("ifmcontentstoprint").contentWindow;
	pri.document.open();
	pri.document.write(content.innerHTML);
	pri.document.close();
	pri.focus();
	pri.print();
	//changecallstatus(call_id);
	$(document).trigger('close.facebox');
}	

function unsignedlist()
{
	
	var engineer = $("#engineer_select").val();
	var sc_id = $("#sc_id_search").val();
	var currentpage=$("#currentpage").val();
	
	if(sc_id == ''){
		$('#sc_id_search').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		return false;
	
		
	}
	
	else{$("#sc_id_search").validationEngine('hideAll');}
	
	var params= "sc_id="+sc_id+"&engineer="+engineer+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({
			type	:	"POST",
			url		:	"<?php echo site_url();?>badparts/getUnsignedList",
			data	: params,
			success	:	function (data){
				$("#unsignedlist").hide();
				$("#unsignedlist").html(data);
				$("#unsignedlist").slideDown();
				hideloading();
			}
	});
}

function downloadUnsignedReport()
{
	var sc_id = $('#sc_id_search').val();
	var engineer = $('#engineer_select').val();
	var params = 'sc_id='+sc_id+'&engineer='+engineer+'&unq='+ajaxunq();
$.ajax({
	   type :	"POST",
	   url	:	"<?php echo site_url();?>badparts/excel_ready_unsigned_badparts",	
	   data	:	params,
	   success :	function(data){ 
	  
	    document.fname.action='<?php echo site_url()?>badparts/create_excel_bad_unsign';
    	document.fname.submit();
	   } 
	  });
}
</script>
