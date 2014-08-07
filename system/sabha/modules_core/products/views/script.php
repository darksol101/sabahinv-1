<script language="javascript">
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
function editProduct(product_id)
{
	showloading();
	cancelProductform();
	var params='product_id='+product_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>products/getproductdetails",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#product_name").val(dt.product_name);
				$("#brand_select").val(dt.brand_id);
				$("#category_select").val(dt.prod_category_id);
				$("#product_description").val(dt.product_desc);
				$("#product_status").val(dt.product_status);
				$("#hdnproductid").val(dt.product_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax	
}
function showProductList()
{
	cancelProductform();
	showloading();
	var brand_id = '<?php echo $this->input->get('b');?>';
	var searchtxt=$("#psearchtxt").val();
	var currentpage = $("#pcurrentpage").val();
	var params = 'currentpage='+currentpage+'&ajaxaction=getproductlist&searchtxt='+searchtxt+'&brand_id='+brand_id+'&unq='+ajaxunq();
	$.ajax({
			type	:	"POST",
			url		:	"<?php echo site_url();?>products/getproductlist",
			data	:	params,
			success	:	function (data){
				cancelProductform();
				$("#productlist").hide();
				$("#productlist").html(data);
				$("#productlist").slideDown();
				hideloading();
			}
	});
}
function saveProduct(){
	showloading();
	var product_name = $("#product_name").val();
	var brand_id = $("#brand_select").val();
	var prod_category_id = $("#category_select").val();
	var product_desc = $("#product_description").val();
	var product_status = $("#product_status").val();
	
	var product_id = $("#hdnproductid").val();
	var params = 'product_id='+product_id+'&product_name='+product_name+'&prod_category_id='+prod_category_id+'&brand_id='+brand_id+'&product_desc='+product_desc+'&product_status='+product_status+'&unq='+ajaxunq();
	$.ajax({
			type	:	"POST",
			url		:	"<?php echo site_url();?>products/saveproduct",
			data	:	{product_id:product_id,product_name:product_name,prod_category_id:prod_category_id,brand_id:brand_id,product_desc:product_desc,product_status:product_status,unq:ajaxunq()},
			success	:	function (data){
				cancelProductform();
				showProductList();
				hideloading(data);
			}
	});	
}
function changeProductstatus(id,status){
	showloading();
	var params="id="+id+"&status="+status+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>products/changestatus",
			data	:	params,
			success	:	function (data){
				showProductList();
				hideloading(data);
				}								
	});//end  ajax
}
function deleteProduct(product_id)
{
	if(confirm('Are you sure you want to delete this product')){
		showloading();
		var params = 'product_id='+product_id+'&unq='+ajaxunq();
		$.ajax({
				type	:	"POST",
				url		:	"<?php echo site_url();?>products/deleteproduct",
				data	:	params,
				success	:	function (data){
					showProductList();
					hideloading(data);
				}
		});	
	}
}

function cancelProductform(){
	$("#hdnproductid").val(0);
	$("#product_name").val('');
	$("#brand_select").val('');
	$("#category_select").val('');
	$("#product_warranty").val('');
	$("#product_description").val('');
	$("#product_status").val('');
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
}
function closeform(){
	window.location='<?php echo base_url();?>';
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

</script>
