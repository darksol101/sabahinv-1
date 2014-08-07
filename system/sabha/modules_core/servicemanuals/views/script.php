<script language="javascript">
$(document).ready(function(){
	getProductsByBrand($('#brand_id').val());
})

function getservicemanualslist(){
	loading("servicemanualslist");
	var searchtxt=$("#searchtxt").val();
	var brand_id = $("#brand_id").val();
	var product_id=$("#product_id").val();
	var currentpage = $("#currentpage").val();
	var params="currentpage="+currentpage+"&ajaxaction=getservicemanualslist&brand_id="+brand_id+"&product_id="+product_id+"&searchtxt="+searchtxt+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>servicemanuals/getservicemanualslist",
			data	:	params,
			success	:	function (data){
				$("#servicemanualslist").css({'display':'none'});
				$("#servicemanualslist").html(data);
				$("#servicemanualslist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
	}
function getProductsByBrand(brand_id)
{
	loading("product_box");
	var brand_id = $("#brand_id").val();	
	var params="brand_id="+brand_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>servicemanuals/getproductsbybrand",
			data	:	params,
			success	:	function (data){
				$("#product_box").html(data);
				hideloading();
				}								
		});//end  ajax
}
function downloadManual(model_id){
	
	var fileUrl='<?php echo site_url();?>manual/download_manual?model_id='+model_id;
	window.location.replace(fileUrl);
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>