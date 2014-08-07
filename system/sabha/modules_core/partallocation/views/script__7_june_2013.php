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
	$("#ajaxErrorMsg").ajaxError(function(request, settings){
		$("#ajaxErrorMsg").html("Error in requesting page ");
		hideloading();
	});
	$("#msg").ajaxSuccess(function(evt, request, settings){
		$("#ajaxErrorMsg").html("");
	});
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});




function saveAssign(){
	
	var part_number = $('#part_select').val();
	var engineer_id = $('#engineer_select').val();
	var quantity = $('#quantity').val();
	var sc_id = $('#sc_id').val();
	
	var rev = 1;
	if(part_number ==''){
		$('#part_select').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		var rev = 0;
	}
	if(engineer_id==''){
		$('#engineer_select').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		var rev = 0;
	}
	if(quantity==''){
		$('#quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		var rev = 0;
	}
	if(quantity < 1){
		$('#quantity').validationEngine('showPrompt', '* Accocated allocated number must be greater than one', 'error', 'topRight', true);
		var rev = 0;
		
	}
	if (rev == 1){
		
	var params = 'part_number='+part_number+'&engineer_id='+engineer_id+'&quantity='+quantity+'&sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({
		   		
			type 	: "POST",
			url 	: "<?php echo site_url();?>partallocation/saveallocation",
			data 	: params,
			success	: function (data){
				if (data == 1){
					alert ('Not Enough part to assign');
					}
				if (data == 2){	
				alert ('Part Assigned');
				allocationlist();
				}
				}
		   
		   });
	}
}


function revokeAssign(){
	
	var part_number = $('#part_select').val();
	var engineer_id = $('#engineer_select').val();
	var quantity = $('#quantity').val();
	var sc_id = $('#sc_id').val();
	
	var rev = 1;
	if(part_number ==''){
		$('#part_select').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		var rev = 0;
	}
	if(engineer_id==''){
		$('#engineer_select').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		var rev = 0;
	}
	if(quantity==''){
		$('#quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		var rev = 0;
	}

if(quantity < 1){
		$('#quantity').validationEngine('showPrompt', '* Allocated quantity must be greater than 1', 'error', 'topRight', true);
		var rev = 0;
		
	}





	var params = 'part_number='+part_number+'&engineer_id='+engineer_id+'&quantity='+quantity+'&sc_id='+sc_id+'&unq='+ajaxunq();
	if (rev == 1){
	$.ajax({
		  type	:	"POST",
		  url	:	"<?php echo base_url();?>partallocation/revoke",
		  data	:	params,
		  success : function (data){
			 if (data == 3){
				 alert ('Part Revoke Successfull');
				  showallocationdetails();
				 }
			 if (data == 2)
			{
				alert ('Parts not assigned');
				}
			if (data == 1) {
				alert (' Not enough allocated quantity to revoke');
				}
			 
			  }
		   
		   });
	}
	
	}

function showstocklist()
{
	cancelform();
	showloading();
	
	$.ajax({
			type	:	"POST",
			url		:	"<?php echo site_url();?>stocks/showstocklistbyscid",
			
			success	:	function (data){
				cancelform();
				$("#stocklist").hide();
				$("#stocklist").html(data);
				$("#stocklist").slideDown();
				hideloading();
			}
	});
}


function allocationlist()
{
	cancelform();
	showloading();
	var searchtxt = $('#searchtxt').val();
	var company = $('#select_company').val();
	var currentpage=$("#currentpage").val();
	var engineer = $("#engineer").val();
	var sc_id = $("#sc_id_search").val();
	//alert (sc_id);
    var start = parseInt(currentpage); // start = currentpage + 1;
	var params= "currentpage="+currentpage+"&sc_id="+sc_id+"&engineer="+engineer+'&company='+company+"&start="+start+"&unq="+ajaxunq();
	$.ajax({
			type	:	"POST",
			url		:	"<?php echo site_url();?>partallocation/allocatedlist",
			data	: params,
			success	:	function (data){
				$("#allocationlist").hide();
				$("#allocationlist").html(data);
				$("#allocationlist").slideDown();
				hideloading();
			}
	});
}

function showallocationdetails(){
	
	showloading();
	var engineer_id = $('#engineer_id').val();
	var part_number = $('#part_number').val();
	var company_id = $('#company_id').val();
	var fromdate = $('#from_date').val();
	var todate = $('#to_date').val();
	var params = "engineer_id="+engineer_id+"&part_number="+part_number+"&company_id="+company_id+"&fromdate="+fromdate+"&todate="+todate+"&unq="+ajaxunq();
	
	$.ajax({
		   
		   type		:"POST",
		   url		: "<?php echo site_url();?>partallocation/partallocationdetails",
		   data		: params,
		   success	: function(data){
			   $("#allocationdetails").hide();
			   $("#allocationdetails").html(data);
			   $("#allocationdetails").slideDown();
			   hideloading();
			   }
		   });
	}





function cancelform(){
	
	$("#part_select").val('');
	$("#engineer_select").val('');
	$("#quantity").val('');
	
}
function closeform(){
	window.location='<?php echo base_url();?>';
}


function closeform1(){
	window.location='<?php echo base_url();?>partallocation';
}



function ajaxunq(){
	var d = new Date();
    var unq = d.getYear()+''+d.getMonth()+''+d.getDay()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds();
	return unq;
	}
function showloading(){
	$(".loading").show();
	}
function hideloading($msg){
	$(".loading").hide();
	if($msg){$('.message_text').show();$(".message_text").html($msg);}
	if($msg)$('.message_text').delay(10000).fadeOut("slow");
	}
	
	
	
function getpartbyscid(sc_id){
	
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
		$.ajax({
			   type		:	"POST",
			   url		: 	"<?php echo base_url();?>partallocation/getpartbyscid",
			   data		:	params,
			   success	:	function(data){
				  			$("#part_select").html(data);
				   
				   }
			   });
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



function getengineer1(sc_id){
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
		$.ajax({
			   type		:	"POST",
			   url		: 	"<?php echo base_url();?>partallocation/getengineer",
			   data		:	params,
			   success	:	function(data){
				 //  alert(data);
				  			$("#engineer").html(data);
				   
				   }
			   });
	}
	
function showAllocationList()
		{
	var searchdate1=$('#searchdate').val();
	var y=searchdate1.split("/");
	var searchdate=(y[2]+"-"+y[1]+"-"+y[0]);
	var engineer = $("#engineer_select").val();
	var sc_id = $("#sc_id_search").val();
	var allco_select=$("#allocation_status").val();
	var part_number = $("#part_num").val();
	
		
	if(sc_id == ''){
		$('#sc_id_search').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		return false;
		
	}
	if(engineer == ''){
		$('#engineer_select').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		return false;
		
	}else{$("#engineer_select").validationEngine('hideAll');}
		
	showloading();
   	var params= "sc_id="+sc_id+"&engineer="+engineer+"&searchdate="+searchdate+"&allco_select="+allco_select+"&part_number="+part_number+"&unq="+ajaxunq();
	$.ajax({
			type	:	"POST",
			url		:	"<?php echo site_url();?>partallocation/showAllocationList",
			data	: params,
			success	:	function (data){
				$("#allocationlist").hide();
				$("#allocationlist").html(data);
				$("#allocationlist").slideDown();
				hideloading();
			}
	});
}

function generatePrintReport()
{
	var part_number = $("#part_num").val();
	var searchdate1=$('#searchdate').val();
	var y=searchdate1.split("/");
	var searchdate=(y[2]+"-"+y[1]+"-"+y[0]);
	var engineer = $("#engineer_select").val();
	var sc_id = $("#sc_id_search").val();
	var allco_select=$("#allocation_status").val();
	$.facebox({ ajax: '<?php echo base_url();?>partallocation/printAllocationList?sc_id='+sc_id+'&engineer='+engineer+'&part_number='+part_number+'&allco_select='+allco_select+'&searchdate='+searchdate+'&unq='+ajaxunq()});
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
			url		:	"<?php echo site_url();?>partallocation/getUnsignedList",
			data	: params,
			success	:	function (data){
				$("#unsignedlist").hide();
				$("#unsignedlist").html(data);
				$("#unsignedlist").slideDown();
				hideloading();
			}
	});
}

function check_checklist(){
	var value="";
	$(".alloc_list").each(function (){
		if (document.getElementById(this.id).checked){
			 value =  (this.id)+','+value;
			 }});

var params = 'value='+value+'&unq='+ajaxunq();;
$.ajax({
	   type :	"POST",
	   url	:	"<?php echo site_url();?>partallocation/savesigned",	
	   data	:	params,
	   success :	function(data){ 
	   showAllocationList();
	   
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
	   url	:	"<?php echo site_url();?>partallocation/excel_ready",	
	   data	:	params,
	   success :	function(data){ 
	  
	   document.fname.action='<?php echo site_url()?>partallocation/create_excel';
    	document.fname.submit();
	   } 
	  });
	
	
}
	

</script>

