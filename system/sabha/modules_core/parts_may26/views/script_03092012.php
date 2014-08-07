<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});
function savePart(){
	showloading();
	var part_id=$("#hdnid").val();
	var part_number_name=$("#partnumber").val();
	var part_serial_number = $("#part_serial_number").val();
	var model_id=$("#model_select").val();
	var description=$("#description").val();
	
	var params="part_id="+part_id+"&part_number_name="+part_number_name+"&part_serial_number="+part_serial_number+"&model_id="+model_id+"&description="+description+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/savepart",
			data	:	params,
			success	:	function (data){
				cancelform();
				showPartList();
				hideloading(data);
				}								
		});//end  ajax
}
function cancelform()
{
	$("#partnumber").val('');
	$("#part_serial_number").val('');
	$("#brand_select").val('');
	$("#description").val('');
	$('#product_select').children().remove().end().append('<option selected value="">Select Product</option>') ;
	$('#model_select').children().remove().end().append('<option selected value="">Select Model</option>') ;
	$("#hdnid").val(0);
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function editPart(id){
	showloading();
	var params='id='+id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"parts/getpartdetails",
			data	:	params,
			success	:	function (data){
					var dt=eval('(' + data + ')');
					getProductBybrand(dt.brand_id,dt.product_id);
					getModelsByProduct(dt.product_id,dt.model_id);
					$("#partnumber").val(dt.part_number_name);
					$("#part_serial_number").val(dt.part_serial_number);
					$("#brand_select").val(dt.brand_id);
					$("#description").val(dt.part_desc);
					$("#hdnid").val(dt.part_id);
					hideloading();
			}								
	});//end  ajax	
}
function showPartList(){
	cancelform();
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage = $("#currentpage").val();
	var params="currentpage="+currentpage+"&ajaxaction=getpartlist&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/getpartlist",
			data	:	params,
			success	:	function (data){
				$("#partlist").html(data);
				$("#partlist").hide();
				$("#partlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function getProductBybrand(brand_id,active){
	$('#model_select').children().remove().end().append('<option selected value="">Select Model</option>') ;
	showloading();
	var params = 'brand_id='+brand_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>parts/getproductsbybrand",
			data	:	params,
			success	:	function (data){
				$("#select_product_box").html(data);
				hideloading();
				}								
		});
}
function getModelsByProduct(product_id,active){
	showloading();
	var params = 'product_id='+product_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>parts/getmodelsbyproduct",
			data	:	params,
			success	:	function (data){
				$("#select_model_box").html(data);
				hideloading();
				}								
		});
}
function changestatus(brand_id,status){
	showloading();
	var params="brand_id="+brand_id+"&status="+status+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>brands/changestatus",
			data	:	params,
			success	:	function (data){
				showBrandList();
				hideloading(data);
				}								
	});//end  ajax
}
function deletePart(part_id){
	if(confirm("Are you Sure to delete this Part")){
		showloading();
		var params="part_id="+part_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>parts/deletepart",
				data	:	params,
				success	:	function (data){
					showPartList();
					hideloading(data);
					}								
			});//end  ajax
	}
}
</script>
