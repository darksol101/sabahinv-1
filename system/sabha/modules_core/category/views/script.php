<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function loadTab(tab,contentdiv){
	var params="unq="+ajaxunq();
	$.ajax({			
			type	:	"GET",
			url		:	tab,
			data	:	params,
			success	:	function (data){
					$('#'+contentdiv).hide();
					$('#'+contentdiv).html(data);
					$('#'+contentdiv).slideDown('slow');
				}				
	});
}
function saveCategory(){
	showloading();
	var id=$("#hdncid").val();
	var prod_category_name=$("#categoryname").val();
	var prod_category_status=$("#category_status").val();
	var prod_category_desc=$("#category_desc").val();
	$("#categoryForm").validationEngine('attach');	
	if(prod_category_name==''){
		$('#categoryForm').validationEngine('showPrompt', 'Brand Name is required', '');
		return false;
	}
	var params="id="+id+"&prod_category_name="+prod_category_name+"&prod_category_status="+prod_category_status+"&prod_category_desc="+prod_category_desc+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>category/savecategory",
			data	:	params,
			success	:	function (data){
				cancelform();
				showCategoryList();
				hideloading(data);
				}								
		});//end  ajax
}
function cancelform()
{
	$("#categoryForm").validationEngine('hideAll');
	$("#categoryname").val('');
	$("#category_desc").val('');
	$("#category_status").val('');
	$("#hdncid").val(0);
	$(".message_text").html('');
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function editCategory(prod_category_id){
	showloading();
	cancelform();
	var params='prod_category_id='+prod_category_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>category/getcategoryetails",
			data	:	params,
			success	:	function (data){
					var dt=eval('(' + data + ')');
					$("#categoryname").val(dt.prod_category_name);
					$("#category_desc").val(dt.prod_category_desc);
					$("#category_status").val(dt.prod_category_status);
					$("#hdncid").val(dt.prod_category_id);
					$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
					hideloading();
			}								
	});//end  ajax	
}
function showCategoryList(){
	cancelform();
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage = $("#ccurrentpage").val();
	var params="currentpage="+currentpage+"&ajaxaction=getcategorylist&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>category/getcategorylist",
			data	:	params,
			success	:	function (data){
				$("#categorylist").html(data);
				$("#categorylist").hide();
				$("#categorylist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function changestatus(id,status){
	showloading();
	var params="id="+id+"&status="+status+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>category/changestatus",
			data	:	params,
			success	:	function (data){
				showCategoryList();
				hideloading(data);
				}								
	});//end  ajax
}
function deleteCategory(prod_category_id){
	if(confirm("Are you Sure to delete this Category")){
		showloading();
		var params="prod_category_id="+prod_category_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>category/deletecategory",
				data	:	params,
				success	:	function (data){
					showCategoryList();
					hideloading(data);
					}								
			});//end  ajax
	}else{
		return false;
	}
}
</script>
