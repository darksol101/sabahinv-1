<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.ui.accordion.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/printThis.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/jquery.ui.accordion.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/magicPop.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$('a[rel*=facebox]').facebox();

	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	$( "#accordion" ).accordion({
		autoHeight: false,
		collapsible: true							
	});

	$("#part_customer_price").blur(function () {
		var price = Number($(this).val());
		$(this).val(price.toFixed(2));
		
	});

});

function closePop(id) {
	$("#"+id).hide();
		$(".bar_img").show();
	
	$(".barcode_list").empty();
}

/*function print_barcode(id,part_number,img) {
	$("#"+id).show();
	$(window).scrollTop();
}
*/


function appendImages(no_of,img,price) {
	if(no_of==0 || no_of==''){
		$(".bar_img").show();
		$("#barcode_list_all").empty();
	}
	else
	{
		$(".bar_img").hide();
		$("#barcode_list_all").empty();


		//var style="width: 242px;height: 44px;margin: 12px;";
		var style="width:91.1mm;height:26.6mm;padding:3mm 4mm";
		//var style1="width: 9.4cm;height: 2cm; margin:0.7cm 0.4cm;";

		/*var style2="width: 9.4cm;height: 2cm; margin:-2mm 3mm 1mm 1mm;padding-top:11px;";
		var style12="width: 9.4cm;height: 2cm; margin:-2mm 0 1mm 1mm;padding-top:11px;";

		var style3="width: 9.4cm;height: 2cm; margin:0 3mm 1mm 1mm;padding-top:3px";
		var style31="width: 9.4cm;height: 2cm; margin:0 3mm 1mm 1mm;padding-top:11px;";*/

		var image='<div style="float:left;"><img style="'+style+'" src="<?php echo site_url('parts/barcodes?text=');?>'+img+'">';
		//var image1='<div style="float:left;"><img style="'+style1+'" src="<?php echo site_url('parts/barcodes?text=');?>'+img+'">';

		/*var image2='<div style="float:left;"><img style="'+style2+'" src="<?php echo site_url('parts/barcodes?text=');?>'+img+'">';
		var image12='<div style="float:left;"><img style="'+style12+'" src="<?php echo site_url('parts/barcodes?text=');?>'+img+'">';

		var image3='<div style="float:left;"><img style="'+style3+'" src="<?php echo site_url('parts/barcodes?text=');?>'+img+'">';
		var image31='<div style="float:left;"><img style="'+style31+'" src="<?php echo site_url('parts/barcodes?text=');?>'+img+'">';
*/
		//var mrp='<p style="text-align:center; margin-top:-7px;">MRP:'+price+'</p></div>';

		for (var i = 0; i < no_of; i++) {
			$('#barcode_list_all').append(image);
			/*if(i <= 2){
				if(i < 2){
					$('#barcode_list_all').append(image3);
				}
				else if(i == 2){
					$('#barcode_list_all').append(image31);	
				}

			}
			else if(i > 2){
			if(i < 2){
				if(i == 0){
					$('#barcode_list_all').append(image2);
				}else if(i == 1){
					$('#barcode_list_all').append(image12);
				}

			}else{
				if(i%2==0){
					$('#barcode_list_all').append(image);
				}
				else{
					$('#barcode_list_all').append(image1);
				}
			}

		}*/
		
		}
	}

}



function savePart(){
	showloading();
	var part_id=$("#hdnid").val();
	var part_number=$("#part_number").val();
	var part_init_no=$("#part_init_no").val();
	var part_landing_price = $("#part_landing_price").val();
	var part_sc_price = $("#part_sc_price").val();
	var part_customer_price = $("#part_customer_price").val();
	var part_size = $("#part_size").val();
	var part_color = $("#part_color").val();
	var description = $("#description").val();
	var order_level = $("#order_level").val();
    var order_level_max = $("#order_level_max").val();
	var unit = $("#unit").val();

    if(order_level > (order_level_max - 50) ){
        alert('Min Order Level Cannot Be greater then Max Order level and should be difference of alteast 50');
        $("#order_level_max").css('background-color','yellow').focus();
        return false;

    }
    $("#order_level_max").css('background-color','');

	var params="unit="+unit+"&order_level_max="+order_level_max+"&order_level="+order_level+"&part_init_no="+part_init_no+"&part_size="+part_size+"&part_color="+part_color+"&part_id="+part_id+"&part_number="+part_number+"&part_landing_price="+part_landing_price+"&part_sc_price="+part_sc_price+"&part_customer_price="+part_customer_price+"&description="+description+"&unq="+ajaxunq();
	//alert(params); 
    console.log(params);

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
	$("#part_number").val('');

	$("#unit").val(1);
	$("#order_level").val('');
    $("#order_level_max").val('');
	$("#current_num").html('');
	$("#new_num").html('');
	$("#part_init_no").val('');
	$("#part_customer_price").val('');
	$("#part_sc_price").val('');
	$("#description").val('');
	$("#part_size").val('');
	$("#part_color").val('');
	$('#part_landing_price').val('') ;
	$('#model_select').children().remove().end().append('<option selected value="">Select Model</option>') ;
	$("#hdnid").val(0);
	$("#partForm").validationEngine('hideAll');
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
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
                   // console.log(dt);
					$("#part_number").val(dt.part_number);
					$("#part_init_no").val(dt.part_initial_no);
					$("#part_size").val(dt.part_size);
					$("#part_color").val(dt.part_color);
					$("#part_customer_price").val(dt.part_customer_price);
					$("#part_sc_price").val(dt.part_sc_price);
					$("#part_landing_price").val(dt.part_landing_price);
					$("#description").val(dt.part_desc);
					$("#hdnid").val(dt.part_id);
					$("#order_level").val(dt.order_level);
                    $("#order_level_max").val(dt.order_level_max);
					$(".label_show").show();
					$("#unit").val(dt.unit);
					$("#current_num").html(dt.part_number);
					$("#new_num").html(dt.part_number);
					$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
                 	hideloading();
                    $(window).scrollTop(0);
			}								
	});//end  ajax


}

function showPartList(){
	cancelform();
	showloading();
	var searchtxt=$("#searchtxt").val();
	var currentpage = $("#currentpage").val();
	//var params="currentpage="+currentpage+"&ajaxaction=getpartlist"+"&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/getpartlist",
			data	:	{currentpage:currentpage,ajaxaction:'getpartlist',searchtxt:searchtxt},
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
	if(confirm("Are you Sure to delete this Item")){
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
function addtoproductmodel(part_id,part_number)
{
	$.facebox(function() { 
		  $.post('<?php echo site_url();?>parts/getbrandproductlist', { part_id:part_id,part_number:part_number,unq:ajaxunq()}, 
			function(data) {
				$.facebox(data);
				var brands = calculateChecked(".brand");
				$("#product_box").html('');
				getProducts(brands);
			});
	    });
}

function getProducts(brand_id){
	var part_number = $("#hdnpart_number").val();
	var part_id = $("#hdnpart_id").val();
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
		var params = 'part_number='+part_number+'&part_id='+part_id+'&user_id='+user_id+'&brand_id='+brand_id+'&unq='+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	'<?php echo site_url();?>parts/getproductsbybrand',
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

							var products = calculateChecked(".product");
							$("#model_box").html('');
							getProductmodels(products);
							hideloading();
					}
		});
	}else{
		$("#product_"+brand_id).remove();
	}
}

function getProductmodels(product_id){
	var part_number = $("#hdnpart_number").val();
	var part_id = $("#hdnpart_id").val();
	var is_checked = $('#product_'+product_id).is(':checked');
	var checked = $(".product:checked").length;
	var checkbox = $(".product").length;
	if(checked == checkbox){
		$("#chkproduct").attr({"checked":"checked"});
	}else{
		$("#chkproduct").removeAttr("checked");
	}
	
	var params = 'part_number='+part_number+'&part_id='+part_id+'&product_id='+product_id+'&unq='+ajaxunq();
	if(is_checked==true){
		$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/getmodelsbyproducts",
			data	:	params,
			success	:	function (data){
							$("#box_check_model").show();
							$("#model_box").append(data);
							var checked_p = $(".model:checked").length;
							var checkbox_p = $(".model").length;
							if(checked_p==checkbox_p){
								$("#chkmodel").attr({"checked":"checked"});
							}else{
								$("#chkmodel").removeAttr('checked');
							}
							$('.model').click(function(){
								var checked = $(".mdoel:checked").length;
								var checkbox = $(".model").length;
								if(checked==checkbox_p){
									$("#chkmodel").attr({"checked":"checked"});
								}else{
									$("#chkmodel").removeAttr('checked');
								}
							});
						}								
		});//end  ajax
	}else{
		$("#model_"+product_id).remove();
	}
}

function saveModelPart(){
	//remove this ***
	var part_number = $("#hdnpart_number").val();
	//new one 
	var part_id = $("#hdnpart_id").val();
	var models = calculateChecked(".model");
	var str = '';
	
	$(".model").each(function() { 
		if($(this).is(':checked')){
			str+='&model[]='+$(this).val(); 
		}
	 });
	
	var params="part_id="+part_id+"&part_number="+part_number+""+str+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/savemodelspart",
			data	:	params,
			success	:	function (data){
				$(document).trigger('close.facebox');
				$("#result").html(data);
				hideloading(data);
				}								
		});
	//end  ajax
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
	$("#chkmodel").removeAttr("checked");
	var products = calculateChecked(".product");
	$("#model_box").html('');
	getProductmodels(products);
}

function selectAllModel()
{
	checkAll("chkmodel","model");
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

function downloadtemplate(){
	document.fname.action='<?php echo site_url()?>parts/generatetemplateexl';
    document.fname.submit();
}
function uploadForm(){
	$.facebox(function() { 
		  $.post('<?php echo site_url();?>parts/uploadform', { unq:ajaxunq()}, 
			function(data) {$.facebox(data); $("#save_xldata").show();});
	    });
}

function barcodes(part_number) {

	$.facebox(function () {
		$.post('<?php echo site_url();?>parts/barcodes_print', {part_number:part_number,unq:ajaxunq()}, 
			function(data) {
				$.facebox(data); 
			});
	});
}


function uploadFile(){
	$("#result").html();
	loading("result");
	var url = "<?php echo site_url();?>parts/upload";
	$("#exlForm").ajaxForm({
		url:  url,
		type:'POST',
		target: '#result',
		success:function(){
			$("#excel_file").val('');
			$("#result").slideDown('slow');
			}
	}).submit();
}
function saveXldata(){
	/*$("#result").html("");
	loading("result");
	var url = "<?php echo site_url();?>parts/savep";
	$("#xlForm").ajaxForm({
		url:  url,
		type:'POST',
		target: '#result',
		success:function(data){
				hideloading(data);
				$(document).trigger('close.facebox');
				showPartList();
			}
	}).submit();*/
	
	document.xlForm.action='<?php echo site_url()?>parts/savep';
    document.xlForm.submit();
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}



function excelDownload()
{
	var str = (document.location.href).split("?",2);
	var url='';
	if(str[1]){
		url = '?'+str[1];
	}
	var fileUrl='<?php echo site_url()?>parts/create_excel'+url;
	window.location.replace(fileUrl);
}


function getProductBybrand(brand_id){
	loading('product_box');
	var params = 'brand_ids='+brand_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/parts/getproductsbybrands",
			data	:	params,
			success	:	function (data){
				$("#product_box").html(data);
				}								
		});
	
}
function getModelsByProducts(product_id){
	loading('model_box');
	var params = 'product_ids='+product_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/getmodelbyproduct",
			data	:	params,
			success	:	function (data){
				$("#model_box").html(data);
				}								
		});//end  ajax
}
function getreportslist(){
	$("#reportslist").slideUp('slow');
	loading('loading');
	var currentpage = $("#currentpage").val();
	var model_number =$("#model_number").val();
	var params='currentpage='+currentpage+'&model_number='+model_number+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>parts/parts/getreportslist",
			data	:	params,
			success	:	function (data){
				$("#reportslist").hide();
				$("#reportslist").html(data);
				$("#reportslist").slideDown('slow');
				hideloading();
				}								
		});
}

function excelDownload_modelassociation()
{
	document.fname.action='<?php echo site_url()?>parts/create_excel_modelassociation';
    document.fname.submit();
	return false;
	var test = $("#model_number").val();
	
	//alert(test); return false;
	var fileUrl='<?php echo site_url()?>parts/create_excel_modelassociation'+test;
	window.location.replace(fileUrl);
}
function downloadtemplate_modelassociation(){
	document.fname.action='<?php echo site_url()?>parts/generatetemplateexl_modelassociation';
    document.fname.submit();
}
function uploadForm_modelassociation(){
	$.facebox(function() { 
		  $.post('<?php echo site_url();?>parts/uploadform_modelassociation', { unq:ajaxunq()}, 
			function(data) {$.facebox(data); $("#save_xldata").show();});
	    });
}
function uploadFile_modelassociation(){
	$("#result").html();
	loading("result");
	var url = "<?php echo site_url();?>parts/upload_modelassociation";
	$("#exlForm").ajaxForm({
		url:  url,
		type:'POST',
		target: '#result',
		success:function(){
			$("#excel_file").val('');
			$("#result").slideDown('slow');
			}
	}).submit();
}
function saveXldata_modelassociation(){
	/*$("#result").html("");
	loading("result");
	var url = "<?php echo site_url();?>parts/savep_modelassociation";
	$("#xlForm").ajaxForm({
		url:  url,
		type:'POST',
		target: '#result',
		success:function(data){
				hideloading(data);
				$(document).trigger('close.facebox');
				showPartList();
			}
	}).submit();*/
	document.xlForm.action='<?php echo site_url()?>parts/savep_modelassociation';
    document.xlForm.submit();
}




</script>
