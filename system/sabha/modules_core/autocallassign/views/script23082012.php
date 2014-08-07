<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	getZones();
	getBrands();
	cancelAssigns();
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});
function calculateChecked(param){
	var optionValues = [];
	$(param).each(function() { 
		if($(this).is(':checked')){
			optionValues.push($(this).val()) 
		}
	 });
	return optionValues;
}
function selectAllZone(){
	$('#districts').html('');
	checkAll('chkzone','zone');
	var zones = calculateChecked(".zone");
	getDistricts(zones);
}
function selectAllDistrict(){
	$('#citiesbox').html('');
	checkAll('chkdistrict','district')
	var districts = calculateChecked(".district");
	getCities(districts);
}
function selectAllCity(){
	checkAll('chkcity','city');
}
function selectAllBrand(){
	$('#products').html('');
	checkAll('chkbrand','brand');
	var brands = calculateChecked(".brand");
	getProducts(brands);
}
function selectAllProduct(){
	checkAll('chkproduct','product');
}
function checkAll(selector1,selector2){
	var checked = $("#"+selector1+"").attr("checked");
	if(checked=='checked'){
		$("."+selector2+"").attr({"checked":"checked"});
	}else{
		$("#"+selector1+"").removeAttr("checked");
		$("."+selector2+"").removeAttr("checked");
	}
}
function selectDistrict(selector1,selector2){
	checkAll(selector1,selector2);
	//getCities();
}
function getZones(){
	var sc_id = $("#sc_id").val();
	var params = 'ajaxaction=getzones&sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	'<?php echo site_url();?>autocallassign/getzones',
			data	:	params,
			success	:	function (data){
						$('#zone_box').html(data);
						var zones = calculateChecked(".zone");
						getDistricts(zones);
				}
	});
}
function getDistricts(zone_id){
	var is_checked = $('#zone_'+zone_id).is(':checked');
	var sc_id = $('#sc_id').val();
	var checked = $(".zone:checked").length;
	var checkbox = $(".zone").length;
	
	if(checked >0){
		$("#district_chkbox").show();
	}else{
		$("#district_chkbox").hide();
	}
	if(checked == checkbox){
		$("#chkzone").attr({"checked":"checked"});
	}else{
		$("#chkzone").removeAttr("checked");
	}
	if(is_checked){
		showloading();
		var params='ajaxaction=getdistricts&sc_id='+sc_id+'&zones='+zone_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	'<?php echo site_url();?>autocallassign/getdistricts',
				data	:	params,
				success	:	function (data){
							hideloading();
							$('#districts').append(data);
							var districts = calculateChecked(".district");
							getCities(districts);
					}				
		});
	}else{
		$("#zonebox_"+zone_id).remove();
		$("#district_chkbox").hide();
	}
}
function getCities(district_id){
	var sc_id = $('#sc_id').val();
	var is_checked = $('#district_'+district_id).is(':checked');
	var checked = $(".district:checked").length;
	var checkbox = $(".district").length;
	if(checked==0){
		$("citiesbox").html('Please select district first');
		$("#city_chkbox").hide();
	}else{
		$("#city_chkbox").show();
	}
	if(checked == checkbox){
		$("#chkdistrict").attr({"checked":"checked"});
	}else{
		$("#chkdistrict").removeAttr("checked");
	}
	if(is_checked==true){
		showloading();
		var params='sc_id='+sc_id+'&ajaxaction=getcities&districts='+district_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	'<?php echo site_url();?>autocallassign/getcities',
				data	:	params,
				success	:	function (data){
							hideloading();
							$('#citiesbox').append(data);
					}				
		});
	}else{
		$("#districtbox_"+district_id).remove();
		$("#city_chkbox").hide();
		$("#chkcity").removeAttr('checked');
	}
}
function getBrands(){
	var sc_id = $("#sc_id").val();
	var params = 'ajaxaction=getbrands&sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	'<?php echo site_url();?>autocallassign/getbrands',
			data	:	params,
			success	:	function (data){
						$('#brand_box').html(data);
						var brands = calculateChecked(".brand");
						getProducts(brands);
				}
	});
}
function getProducts(brand_id){
	var sc_id = $('#sc_id').val();
	var is_checked = $('#brand_'+brand_id).is(':checked');
	var checked = $(".brand:checked").length;
	var checkbox = $(".brand").length;
	if(checked==0){
		$("#product_chkbox").hide();
		$("#chkproduct").removeAttr('checked');
		$("#products").html('Please select brand first');
	}else{
		$("#product_chkbox").show();
	}
	if(checked == checkbox){
		$("#chkbrand").attr({"checked":"checked"});
	}else{
		$("#chkbrand").removeAttr("checked");
	}
	if(is_checked){
		showloading();
		var params='sc_id='+sc_id+'&ajaxaction=getproducts&brands='+brand_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	'<?php echo site_url();?>autocallassign/getproducts',
				data	:	params,
				success	:	function (data){
							hideloading();
							$('#products').append(data);
							$("#chkproduct").removeAttr('checked');
							if(data==''){
								$('#products').html('No products found');
							}
					}
		});
	}else{
		$("#brandbox_"+brand_id).remove();
	}
}
function saveAssignment(){
	var sc_id = $('#sc_id').val();
	var products = calculateChecked('.product');
	var cities = calculateChecked('.city');
	
	var params = 'sc_id='+sc_id+'&cities='+cities+'&products='+products;
	if(sc_id>0){
		showloading();
		$.ajax({			
				type	:	"POST",
				url		:	'<?php echo site_url();?>autocallassign/saveassignment',
				data	:	params,
				success	:	function (data){
							hideloading(data);
							cancelAssigns();
							$("h3#zones_heading").click();
							$("#chkzone").removeAttr("checked");
							$(".zone").removeAttr("checked");
							$(".brand").removeAttr("checked");
							$("#chkbrand").removeAttr("checked");
							$("#district_chkbox").hide();
							$("#city_chkbox").hide();
							$("#product_chkbox").hide();
					}
		});
	}else{
		alert('Please select Store ');
	}
}
function getCallassigns(sc_id){
	if(sc_id>0){
		$("#citiesbox").html('');
		$("#products").html('');
		getZones();
		getBrands();
	}else{
		showloading();
		cancelAssigns();
		$("#chkzone").removeAttr("checked");
		$(".zone").removeAttr("checked");
		$(".brand").removeAttr("checked");
		$("#chkbrand").removeAttr("checked");
		$("#city_chkbox").hide();
		hideloading();
	}
}
function cancelAssigns(){
	$("#sc_id").val(0);
	$("#districts").html('');
	$("#citiesbox").html('');
	$("#products").html('');
}
function ajaxunq(){
	var d = new Date();
    var unq = d.getYear()+''+d.getMonth()+''+d.getDay()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds();
	return unq;
}
function scrollLock()
{
      var scrollPosition = [
        self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
        self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
      ];
      var html = jQuery('html'); // it would make more sense to apply this to body, but IE7 won't have that
      html.data('scroll-position', scrollPosition);
      html.data('previous-overflow', html.css('overflow'));
      html.css('overflow', 'hidden');
      window.scrollTo(scrollPosition[0], scrollPosition[1]);

}
function scrollUnlock(){	
      var html = jQuery('html');
      var scrollPosition = html.data('scroll-position');
      html.css('overflow', 'visible');
	  window.scrollTo(scrollPosition[0], scrollPosition[1])
}
function showloading(){
	var windowWidth = document.body.clientWidth;
    var windowHeight = document.body.clientHeight;
    var screenHeight =  document.documentElement.clientHeight;
    var screenWidth =  document.documentElement.clientWidth;
	var scrollheight = $(document).scrollTop();
	$('#spn').addClass('spinner-img');
	$('#loading').css({"display": "block","height":windowHeight+"px","width":windowWidth+"px","z-index": "999999999"});
    $('.spinner-img').css({
        "top": (screenHeight/2-33+scrollheight)+"px",
        "left": (screenWidth/2-33)+"px",
		
     });
	scrollLock();
	
}
function hideloading($msg){
	$('#loading').css({"display":"none"});
	$('#spn').removeClass('spinner-img');
	if($msg){$('.message_text').show();$(".message_text").html($msg);}
	if($msg)$('.message_text').delay(10000).fadeOut("slow");
	scrollUnlock();
}
</script>