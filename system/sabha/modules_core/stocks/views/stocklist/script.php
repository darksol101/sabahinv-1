<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	showstockList();
});
function showstockList(){
	showloading();
	//$("#stocklist").hide();
	var sc_id = $("#sc_id").val();
	var searchtxt = $("#searchtxt").val();
        /*var company = $("#select_company").val('1');
        */
        var company = '1';
		var currentpage=$("#currentpage").val();
		var start = parseInt(currentpage);
	var params="currentpage="+currentpage+'&searchtxt='+searchtxt+'&sc_id='+sc_id+"&start="+start+'&company='+company+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>stocks/showstocklist",
			data	:	params,
			success	:	function (data){
				$("#stocklist").html(data);
				//$("#stocklist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}

function excelDownload(){

	var sc_id = $("#sc_id").val();
	var searchtxt = $("#searchtxt").val();
    //var company = $("#select_company").val();
    var company = '1';

	var params='searchtxt='+searchtxt+'&sc_id='+sc_id+'&company='+company+'&unq='+ajaxunq();
	//alert (params);
	$.ajax({
			type	:	"POST",
			url		:	"<?php echo site_url();?>stocks/excel",
			data	:	params,
			success	:	function (data){
				var fileUrl='<?php echo site_url()?>stocks/create_excel';
				window.location.replace(fileUrl);
				
				}								
		});

function lowStockReport(){


}

	
}
</script>