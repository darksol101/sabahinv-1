<script language="javascript">
/*$(document).ready(function(){
	showloading();					   
	$('.content-box ul.content-box-tabs li a').click(function(){$("#moduletitle").html(this.innerHTML);
	var arr = (this.href).split("#",2);
	loadTab(this.id,arr[1]);
	});
});*/
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function loadTab(ajaxurl,contentdiv){
	var params = 'unq='+ajaxunq();
	$.ajax({			
				type	:	"GET",
				url		:	ajaxurl,
				data	:	params,
				success	:	function (data){
					$("#"+contentdiv).html(data);
					}								
			});//end  ajax
}
function saveServiceCenter(){
	showloading();
	var sc_id=$("#hdnsc_id").val();
	var sc_name=$("#sc_name").val();	
	var sc_address=$("#sc_address").val();
	var sc_phone1=$("#sc_phone1").val();
	var sc_phone2=$("#sc_phone2").val();
	var sc_phone3=$("#sc_phone3").val();
	var sc_email=$("#sc_email").val();
	var sc_fax=$("#sc_fax").val();
	var city_id=$("#city_select").val();
	var sc_code=$("#sc_code").val();
	
	

	$("#frm_sc").validationEngine('attach');	
	if(sc_name==''){
		$('#sc_name').validationEngine('showPrompt', 'Store Name is required', '');
		return false;
	}
	var params="sc_code="+sc_code+"&sc_id="+sc_id+"&sc_name="+sc_name+"&sc_address="+sc_address+"&sc_phone1="+sc_phone1+"&sc_phone2="+sc_phone2+"&sc_phone3="+sc_phone3+"&sc_fax="+sc_fax+"&sc_email="+sc_email+"&city_id="+city_id+"&unq="+ajaxunq();

	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>servicecenters/saveservicecenter",
			data	:	params,
			success	:	function (data){
				cancel();
				showServiceCenterList();
				hideloading(data);
				}								
		});//end  ajax
}

function cancel()
{
	$("#frm_sc").validationEngine('hideAll');
	$("#sc_name").val('');
	$("#sc_address").val('');
	$("#sc_code").val('');
	$("#sc_phone1").val('');
	$("#sc_phone2").val('');
	$("#sc_phone3").val('');
	$("#sc_fax").val('');
	$("#sc_email").val('');
	$("#city_select").val('');
	$("#hdnsc_id").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function editServiceCenter(sc_id){
	showloading();
	cancel();
	var params='sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>servicecenters/getServiceCenterdetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')')

					if(dt.sc_phone1 == 0){
						dt.sc_phone1 ="";
					}
					if(dt.sc_phone2 == 0){
						dt.sc_phone2 ="";
					}
					if(dt.sc_phone3 == 0){
						dt.sc_phone3 ="";
					}
					if(dt.sc_fax == 0){
						dt.sc_fax = "";
					}

				$("#sc_name").val(dt.sc_name);
				$("#sc_address").val(dt.sc_address);
				$("#sc_phone1").val(dt.sc_phone1);
				$("#sc_phone2").val(dt.sc_phone2);
				$("#sc_phone3").val(dt.sc_phone3);
				$("#sc_fax").val(dt.sc_fax);
				$("#sc_email").val(dt.sc_email);
				$("#city_select").val(dt.city_id);
				$("#hdnsc_id").val(dt.sc_id);
				$("#sc_code").val(dt.sc_code);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax	
}
function showServiceCenterList(){
	cancel();
	showloading();
	var searchtxt=$("#searchtxt").val();
	var params="ajaxaction=getservicecenterlist&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>servicecenters/getservicecenterlist",
			data	:	params,
			success	:	function (data){
				$("#servicecenterlist").html(data);
				$("#servicecenterlist").css({'display':'none'});
				$("#servicecenterlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function deleteServiceCenter(id){
	if(confirm("Are you Sure to delete this Service")){
		showloading();
		var params="id="+id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>servicecenters/delete_ServiceCenter",
				data	:	params,
				success	:	function (data){
					showServiceCenterList();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false;
	}
}
</script>
