<script language="javascript">
$(document).ready(function(){
	showCityList();
})

function showCityList(){
	loading();
	var searchtxt=$("#searchtxt").val();
	var currentpage=$("#currentpage").val();
	var zone_id = $("#zone_search").val();
	var district_id = $("#district_search").val();
	
	var params="ajaxaction=getsearchcitylist&searchtxt="+searchtxt+"&zone_id="+zone_id+"&district_id="+district_id+"&currentpage="+currentpage+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>cities/search/getsearchcitylist",
			data	:	params,
			success	:	function (data){
				$("#listcities").css({'display':'none'});
				$("#listcities").html(data);
				$("#listcities").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
	}

function getdistricts(zone_id)
{
	loading("ct_district_search");
	var params = 'zone_id='+zone_id+'&active='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>cities/getdistricts",
			data	:	params,
			success	:	function (data){
				$("#ct_district_search").html(data);
				hideloading();
				}								
		});
}
function setCustomerCityInfo(zone_id,district_id,city_id)
{
	$("#zone_select").val(zone_id);
	getdistrictbyzone(zone_id,district_id);
	getcitiesbydistrict(district_id,city_id);
	getServiceCenterByCity(city_id);
	$(document).trigger('close.facebox');
}
</script>