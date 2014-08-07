<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	var autoincrementId = $('#autoincrementId').val();
	$("#engineer_name").val('');
	$("#engineer_desc").val('');
	$("#hdnengineer_id").val(0);
	$("#sc_select").val('');
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}

function showEngineerTable(){
	showloading();
	var searchtxt=$("#searchengtxt").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getengineerlist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>engineers/getengineerlist",
			data	:	params,
			success	:	function (data){
				$("#engineerlist").css({'display':'none'});
				$("#engineerlist").html(data);
				$("#engineerlist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}

function showengineer(engineer_id){
	showloading();
	cancel();
	var params='engineer_id='+engineer_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>engineers/getengineer",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')')
				$("#engineer_name").val(dt.engineer_name);
				$("#engineer_desc").val(dt.engineer_desc);
				$("#frmengineers #status").val(dt.engineer_status);
				$("#sc_select").val(dt.sc_id);
				$("#hdnengineer_id").val(dt.engineer_id);
				hideloading();
				}								
		});//end  ajax
}
function changeEstatus(id,status){
	showloading();
	var params="id="+id+"&status="+status+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>engineers/changeEstatus",
			data	:	params,
			success	:	function (data){
				showEngineerTable();
				hideloading(data);
				}								
	});//end  ajax
	}
	


function saveengineer(){
	showloading()
	var engineer_name=$("#engineer_name").val();
	var engineer_desc=$("#engineer_desc").val();
	var engineer_status=$("#frmengineers #status").val();	
	var engineer_id=$("#hdnengineer_id").val();
	var sc_id = $("#sc_select").val();
	var params="&engineer_id="+engineer_id+"&engineer_name="+engineer_name+"&engineer_desc="+engineer_desc+"&engineer_status="+engineer_status+"&sc_id="+sc_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>engineers/saveengineer",
			data	:	params,
			success	:	function (data){
				showEngineerTable()
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}

function deletEngineer(engineer_id){	
	if(confirm("Are you Sure to delete this Engineer's Name?")){
		showloading()
		var params="engineer_id="+engineer_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>engineers/deleteengineer",
				data	:	params,
				success	:	function (data){
					showEngineerTable();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
function closeform()
{
	window.location='<?php echo site_url();?>';
}
</script>