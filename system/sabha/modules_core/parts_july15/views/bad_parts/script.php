<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.ui.accordion.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/jquery.ui.accordion.min.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
showloading();
});
$(window).load(function(){
	hideloading();
	showBadPartsList();					
});
	
function showBadPartsList(){
	$("#userlist").css({'display':'none'});
	showloading();
	var currentpage = $("#currentpage").val();
	var searchtxt=$("#searchtxt").val()
	var params="currentpage="+currentpage+"&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/bad_parts/getbadparts",
			data	:	params,
			success	:	function (data){
				$("#badpartlist").css({'display':'none'});
				$("#badpartlist").html(data);
				$("#badpartlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
	}
function showuser(userid){
	cancelAssigns();
	showloading();
	$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
	var params="userid="+userid;
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>users/getuser",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')')
				$("#username").val(dt.username);
				$("#userid").val(dt.user_id);
				$("#email").val(dt.email_address);
				$("#mobile_number").val(dt.mobile_number);
				$("#password").val(dt.password);
				$("#rpassword").val(dt.password);
				$("#status").val(dt.ustatus);
				$("#usergroup").val(dt.usergroup_id);
				$("#sc_id").val(dt.sc_id);
				$("#hdnuserid").val(dt.id);
				$(".formError").remove();
				getbrands();
				hideloading();
				}								
		});//end  ajax
}
function save(){
	showloading();
	var username=$("#username").val();
	var userid=$("#userid").val();
	var email=$("#email").val();
	var mobile_number=$("#mobile_number").val();
	var password=$("#password").val();
	var rpassword=$("#rpassword").val();
	var status=$("#status").val();	
	var usergroup=$("#usergroup").val();
	var sc_id = $("#sc_id").val();
	var hdnuserid=$("#hdnuserid").val();
	var products = calculateChecked('.product');
	var params="username="+username+"&userid="+userid+"&email="+email+"&mobile_number="+mobile_number+"&password="+password+"&status="+status+"&usergroup="+usergroup+"&sc_id="+sc_id+"&hdnuserid="+hdnuserid+'&products='+products+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>users/saveuser",
			data	:	params,
			success	:	function (data){
				showTable();
				cancel();
				cancelAssigns();
				hideloading(data);
				}								
		});//end  ajax
}

function delet(userid){
	
	if(confirm("Are you Sure to delete this User")){
		showloading()
		var params="userid="+userid+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"users/deleteuser",
				data	:	params,
				success	:	function (data){
					showTable()
					hideloading(data)
					}								
			});//end  ajax
	}else{
		return false
	}
}
function changestatus(user_id,ustatus){
	showloading();
	var params="user_id="+user_id+"&ustatus="+ustatus+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"users/chagestatus",
			data	:	params,
			success	:	function (data){
				showTable()
				hideloading(data)
				}								
		});//end  ajax
}
function getbrands(){
	var user_id = $("#hdnuserid").val();
	var params = 'ajaxaction=getbrands&user_id='+user_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	'<?php echo site_url();?>users/getbrands',
			data	:	params,
			success	:	function (data){
						$('#brand_box').html(data);
						var brands = calculateChecked(".brand");
						getProducts(brands);
				}
	});
}
function getProducts(brand_id){
	var is_checked = $('#brand_'+brand_id).is(':checked');
	var checked = $(".brand:checked").length;
	var checkbox = $(".brand").length;
	if(checked == checkbox){
		$("#chkbrand").attr({"checked":"checked"});
	}else{
		$("#chkbrand").removeAttr("checked");
	}
	if(is_checked==true){
		showloading();
		var user_id = $("#hdnuserid").val();
		var params = 'ajaxaction=getproductsbybrand&user_id='+user_id+'&brand_id='+brand_id+'&unq='+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	'<?php echo site_url();?>users/getproductsbybrand',
				data	:	params,
				success	:	function (data){
							$("#box_check").show();
							$('#product_box').append(data);
							var checked_p = $(".product:checked").length;
							var checkbox_p = $(".product").length;
							if(checked_p==checkbox_p){
								$("#chkproduct").attr({"checked":"checked"});
							}else{
								$("#chkproduct").removeAttr('checked');
							}
							$('.product').click(function(){
								var checked = $(".product:checked").length;
								var checkbox = $(".product").length;
								if(checked==checkbox_p){
									$("#chkproduct").attr({"checked":"checked"});
								}else{
									$("#chkproduct").removeAttr('checked');
								}
							});
							hideloading();
					}
		});
	}else{
		$("#product_"+brand_id).remove();
	}
}
function selectAllBrands(){
	checkAll("chkbrand","brand");
	$("#box_check").hide();
	$("#chkproduct").removeAttr("checked");
	var brands = calculateChecked(".brand");
	$("#product_box").html('');
	getProducts(brands);
}
function selectAllProduct(){
	checkAll("chkproduct","product");
}
function checkAll(selector1,selector2)
{
	var checked = $("#"+selector1+"").attr("checked");
	if(checked=='checked'){
		$("."+selector2+"").attr({"checked":"checked"});
	}else{
		$("#"+selector1+"").removeAttr("checked");
		$("."+selector2+"").removeAttr("checked");
	}
}
function calculateChecked(param)
{
	var optionValues = [];
	$(param).each(function() { 
		if($(this).is(':checked')){
			optionValues.push($(this).val()) 
		}
	 });
	return optionValues;
}
function cancelAssigns(){
	$(".brand").removeAttr('checked');
	$("#box_check").hide();
	$("#chkproduct").removeAttr('checked');
	$("#chkbrand").removeAttr('checked');
	$("#product_box").html('');
}
function cancel(){
	var autoincrementId = $('#autoincrementId').val();
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$('#userid').val(autoincrementId);
	$("#username").val("");
	$("#email").val("");
	$("#mobile_number").val('');
	$("#password").val("");
	$("#rpassword").val("");
	$("#status").val(1);	
	$("#usergroup").val(0);
	$("#hdnuserid").val(0);
	$("#groupname").val('');
	$("#description").val('');
	$("#sc_id").val('');
	$("#hdngroupid").val(0);
	$(".formError").remove();
	cancelAssigns();
}
function closeform()
{
	window.location='<?php echo site_url();?>';
}
</script>
