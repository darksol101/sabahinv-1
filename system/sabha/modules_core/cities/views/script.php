<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function cancel(){
	$("#zone_select").val('');
	$("#frmcity").validationEngine('hideAll');
	$("#frmcity #district_select").val('');
	$("#city_name").val('');
	$("#sc_select").val(0);
	$("#hdncity_id").val(0);
	$(".formError").remove();
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function savecity()
{
	showloading()
	var city_name=$("#city_name").val();
	var sc_id=$("#sc_select").val();
	var district_id=$("#frmcity #district_select").val();	
	var hdncity_id=$("#hdncity_id").val();
	var params="city_id="+hdncity_id+"&city_name="+city_name+"&district_id="+district_id+"&sc_id="+sc_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>cities/savecity",
			data	:	params,
			success	:	function (data){
				showCityTable()
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}


function showCityTable(){
	$("#citylist").css({'display':'none'});
	showloading();
	var searchtxt=$("#searchcitytxt").val();
	var zone_id=$("#zone_search").val();
	var district_id=$("#district_search").val();
	var sc_id=$("#sc_search").val();
	var currentpage=$("#currentpage").val();
	var params="ajaxaction=getcitylist&searchtxt="+searchtxt+"&currentpage="+currentpage+"&zone_id="+zone_id+"&district_id="+district_id+"&sc_id="+sc_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>cities/getcitylist",
			data	:	params,
			success	:	function (data){
				$("#citylist").css({'display':'none'});
				$("#citylist").html(data);
				$("#citylist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}
function showcity(city_id){
	showloading();
	cancel();
	var params='city_id='+city_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>cities/getCitydetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')')
				$("#city_name").val(dt.city_name);
				$("#zone_select").val(dt.zone_id);
				$("#sc_select").val(dt.sc_id);
				getdistrictbyzone(dt.zone_id,dt.district_id);
				$("#hdncity_id").val(dt.city_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax		
}	

function searchCity(){
$("#citylist").css({'display':'none'});
	showloading();
	var searchcitytxt=$("#searchcitytxt").val()
	var params="ajaxaction=getcitylist&searchcitytxt="+searchcitytxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>cities/getcitysearch",
			data	:	params,
			success	:	function (data){
				$("#citylist").css({'display':'none'});
				$("#citylist").html(data);
				$("#citylist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
}

function deletCity(city_id){
	if(confirm("Are you sure to delete this city ?")){
		showloading()
		var params="city_id="+city_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>cities/deletecity",
				data	:	params,
				success	:	function (data){
					showCityTable();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false
	}
}
function getdistrictbyzone(zone_id,active)
{
	showloading();
	var params = 'zone_id='+zone_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>cities/getdistrictbyzone",
			data	:	params,
			success	:	function (data){
				$("#select_district_box").html(data);
				hideloading();
				}								
		});
}
function getdistricts(zone_id)
{
	showloading();
	var params = 'zone_id='+zone_id+'&active='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>cities/getdistricts",
			data	:	params,
			success	:	function (data){
				$("#search_district_box").html(data);
				hideloading();
				}								
		});
}
</script>
