<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function saveProductModel()
{
	showloading();
	var id = $("#hdnmodelid").val();
	var model_number = $("#modelnumber").val();
	var model_warranty = $("#model_warranty").val();
	var brand_id = $("#brand_select").val();
	var product_id = $("#product_select").val();
	var model_status = $("#model_status").val();
	var model_desc = $("#model_desc").val();
	var params="id="+id+"&model_number="+model_number+"&model_warranty="+model_warranty+"&brand_id="+brand_id+"&product_id="+product_id+"&model_status="+model_status+"&model_desc="+model_desc+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>productmodel/saveproductmodel",
			data	:	{id:id,model_number:model_number,model_warranty:model_warranty,brand_id:brand_id,product_id:product_id,model_status:model_status,model_desc:model_desc,unq:ajaxunq()},
			success	:	function (data){
				showProductmodelList();
				hideloading(data);
				}								
		});//end  ajax
}
function editModel(id)
{
	ClearModelForm();
	showloading();
	var params = 'id='+id+'&unq='+ajaxunq();
	$.ajax({
		   type	:"POST",
		   url	:"productmodel/getmodeldetails",
		   data	: params,
		   success:function(data){
			   var dt = eval("("+data+")");
			   $("#hdnmodelid").val(dt.model_id);
			   $("#modelnumber").val(dt.model_number);
			    $("#model_warranty").val(dt.model_warranty);
			   $("#model_desc").val(dt.model_desc);
			   $("#brand_select").val(dt.brand_id);
			   $("#model_status").val(dt.model_status);
			   getProductBybrand(dt.brand_id,dt.product_id);
			   $("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
		   	}
		   });
}
function deleteModel(id){
	if(confirm("Are you sure you want to delete model")){
		ClearModelForm();
		showloading();
		var params = 'id='+id+'&unq='+ajaxunq();
		$.ajax({
				type	:"POST",
				url	:"<?php echo site_url();?>productmodel/deleteproductmodel",
				data	: params,
				success:function(data){
					showProductmodelList();
					hideloading(data);
				}
		});
	}
}
function showProductmodelList(){
	ClearModelForm();
	showloading();
	var product_id = '<?php echo $this->input->get('p');?>';
	var currentpage = $("#currentpage").val();
	var searchtxt=$("#pmsearchtxt").val();
	var params="currentpage="+currentpage+"&ajaxaction=getproductmodellist&searchtxt="+searchtxt+"&product_id="+product_id+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>productmodel/getproductmodellist",
			data	:	params,
			success	:	function (data){
				$("#productmodellist").hide();
				$("#productmodellist").html(data);
				$("#productmodellist").slideDown();
				hideloading();
				}								
		});//end  ajax
}
function getProductBybrand(brand_id,active){
	showloading();
	var params = 'brand_id='+brand_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>products/getproductsbybrand",
			data	:	params,
			success	:	function (data){
				$("#select_product_box").html(data);
				hideloading();
				}								
		});
}
function checkModelNumber(model_number){
	if(model_number==''){
		return false;
	}
	showloading();
	var id = $("#hdnmodelid").val();
	var brand_id = $("#brand_select").val();
	var product_id = $("#product_select").val();
	var params = 'id='+id+'&model_number='+model_number+'&brand_id='+brand_id+'&product_id='+product_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>productmodel/checkmodelnumer",
			data	:	params,
			success	:	function (data){
						$("#msg_check").html(data);
						hideloading();
				}								
		});
}
function showManualList(model_id)
{
	var params = 'model_id='+model_id+'&ajaxaction=getmanuallist&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>manual/getmanuallist",
			data	:	params,
			success	:	function (data){
						$("#manuallist").html(data);
						hideloading();
				}								
		});
}
function showuploadform(model_id)
{
	$.facebox({ ajax: '<?php echo site_url();?>manual/uploadform?model_id='+model_id+'&unq='+ajaxunq() });
}
function ClearModelForm()
{
	$("#hdnmodelid").val(0);
	$("#modelnumber").val('');
	$("#model_warranty").val(0);
	$("#model_desc").val('');
	$("#brand_select").val('');
	$("#product_select").val('');
	$("#model_status").val('');
	$("body").validationEngine('hideAll');
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
}
function deleteManual(model_id,manual_id,file_name){
	var params = 'manual_id='+manual_id+'&file_name='+file_name+'&ajaxaction=getmanuallist&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>manual/deletemanual",
			data	:	params,
			success	:	function (data){
						showManualList(model_id);
						hideloading();
				}								
		});
}
</script>
