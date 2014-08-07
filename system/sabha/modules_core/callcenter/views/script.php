<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	$('#cstatuslist').change(function() {
		if($('#cstatuslist').val()==1){
			$("#call_reason_pending").addClass('validate[required]');
			$("#call_engineer_remark").removeClass('validate[required]');
			$("#call_detail_wrk_done").removeClass('validate[required]');
		}else if($('#cstatuslist').val()==3){
			$("#call_engineer_remark").addClass('validate[required]');
			$("#call_detail_wrk_done").addClass('validate[required]');
		}
		else{
			$("#call_engineer_remark").removeClass('validate[required]');
			$("#call_detail_wrk_done").removeClass('validate[required]');
			$("#call_reason_pending").removeClass('validate[required]');
			$("#lblreason").html('');
			$("#reasonlist").html('');
		}
		if($('#cstatuslist').val()==3){
			$("#call_engineer_remark").addClass('validate[required]');
			$("#call_detail_wrk_done").addClass('validate[required]');
			$("#call_reason_pending").removeClass('validate[required]');
			$("#product_serial_number").addClass('validate[required]');
			$("#product_purchase_date").addClass('validate[required]');
			$("#call_visit_dt").addClass('validate[required]');
			$("#call_delivery_dt").addClass('validate[required]');
		}else{
			$("#product_serial_number").removeClass('validate[required]');
			$("#product_purchase_date").removeClass('validate[required]');
			$("#call_visit_dt").removeClass('validate[required]');
			$("#call_delivery_dt").removeClass('validate[required]');
		}
	});
	if($('#cstatuslist').val()==1){
		$("#call_reason_pending").addClass('validate[required]');
	}
	if($('#cstatuslist').val()==3){
			$("#call_engineer_remark").addClass('validate[required]');
			$("#call_detail_wrk_done").addClass('validate[required]');
			$("#call_reason_pending").removeClass('validate[required]');
			$("#product_serial_number").addClass('validate[required]');
			$("#product_purchase_date").addClass('validate[required]');
			$("#call_visit_dt").addClass('validate[required]');
	}
	$("#npcalendar").click(function(){
		popitup('<?php echo site_url()?>calendar');
	});
	//remove parts used from call
	$("a.returnparts").live('click',function(event){
		event.preventDefault();
		if(confirm('This part will be Returned.Do you want to return ? ')){
			$(this).parent().find('a:first').delay(1000).show('slow');
			var ele = $(this).parent().parent();
			var part_number = ele.find('td:first').next().find('input:first').val();
			var parts_used_id = ele.find('td:first').next().find('input:last').val();
			var company =  ele.find('td:first').next().next().next().next().next().next().find('input:first').val();
			var quantity =  ele.find('td:first').next().next().next().next().next().next().next().next().find('input:first').val();
			var eng = '<?php echo $call_details->engineer_id?>';
		
			var sc_id = '<?php echo $call_details->sc_id?>';
			var params = 'sc_id='+sc_id+'&part_number='+part_number+'&eng='+eng+'&quantity='+quantity+'&company='+company+'&parts_used_id='+parts_used_id+'&unq='+ajaxunq();	
			//alert (params);
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo base_url();?>callcenter/pop/removeusedparts",
					data	:	params,
					success	:	function (data){
								hideloading();
								ele.remove();
								alert('Part returned succefully');
							}								
				});
		}
	});
	
	
	$("a.removerow").live('click',function(event){
		event.preventDefault();
		var ele = $(this).parent().parent();
		ele.remove();
	});
	
	
	
	
	
	$("a.editparts").live('click',function(event){
		event.preventDefault();
		var ele = $(this).parent().parent();
		ele.find('td:first').next().find('input:first').val();
		ele.find('td:first').next().next().next().next().next().next().find('input').removeAttr('readonly');
	});
	$("a.deletepartpending").live('click',function(event){
		event.preventDefault();
		var ele = $(this).parent().parent();
		var order_part_id = ele.find('td:first').next().find('input:last').val();
		var order_id = ele.find('td:first').next().next().next().next().next().next().next().next().next().find('input').val();
		var params = 'order_id='+order_id+'&order_part_id='+order_part_id+'&unq='+ajaxunq();
		if(confirm('Are you sure to delete this part from order')){
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo base_url();?>callcenter/pop/removeorderpartscalls",
					data	:	params,
					success	:	function (data){
								hideloading();
								ele.remove();
								alert(data);
							}								
					});
		}
	});
	
	/*
	**for parts in particular Store
	*/
	//for search part
	var ele;
	$('a.searchparstbtn').live('click',function(event) {
		var engineer_id = $('#engineer_select').val();

		event.preventDefault();										
		ele = $(this).parent().parent();
		var sc_id ='<?php echo $call_details->sc_id;?>';
		$.facebox(function() { 
			$.post('<?php echo site_url();?>callcenter/pop/getavaliablepartslist', { engineer_id:engineer_id,sc_id:sc_id,unq:ajaxunq()}, 
			function(data) { $.facebox(data) });
		});
	});
	$('a.setpartused').live('click',function(event) {
			event.preventDefault();
			var ele1 = $(this).parent().parent();
			var part_number = ele1.find('td:first').next().find('a:first').html();
			var part_description = ele1.find('td:first').next().next().html();
			var part_company = ele1.find('td:first').next().next().next().next().html();
			//alert (part_company);
			
			var add = true;
			$('#used_parts_list tbody tr').each(function(index, value){
				var ele3 = $(value);
				var old_used_part_number = ele3.find('td:first').next().find('input:first').val();
				var old_company = ele3.find('td:first').next().next().next().next().next().next().find('input:first').val()
				if(old_used_part_number == part_number && old_company == part_company){
					add = false
				}
			});
			if(add == true){
				ele.find('td:first').next().find('input:first').val(part_number);
				ele.find('td:first').next().next().next().next().find('input:first').val(part_description);
				ele.find('td:first').next().next().next().next().next().next().find('input:first').val(part_company);
				
				$(document).trigger('close.facebox');
			}else{
				alert('This part is already added');
			}
	});
	//ends here
	
	//for search of parts pending
	var ele_pp;
	$('a.searchpendingparstbtn').live('click',function(event) {
		event.preventDefault();
		ele_pp = $(this).parent().parent();
		var sc_id ='<?php echo $call_details->sc_id;?>';
		var call_id = $("#hdncallid").val();
		var order_id = $("#order_id").val();
		var currentpage = $("#currentpage").val();
		var searchtxt = $("#searchtxt").val();
		var model_id = '<?php echo $call_details->model_id;?>';
		$.facebox(function() { 
			$.post('<?php echo site_url();?>callcenter/pop/parts', { sc_id:sc_id,call_id:call_id,order_id:order_id,model_id:model_id,unq:ajaxunq()}, 
			function(data) { $.facebox(data) });
		});
	});
	$('a.setpartpending').live('click',function(event) {
			event.preventDefault();
			var ele1 = $(this).parent().parent();
			var part_number = ele1.find('td:first').next().find('a:first').html();
			var part_description = ele1.find('td:first').next().next().html();
			var add = true;
			$('#pending_parts_list tbody tr').each(function(index, value){
				var ele2 = $(value);
				var old_part_number = ele2.find('td:first').next().find('input:first').val();
				var part_order_id = ele2.find('td:first').next().next().next().next().next().next().next().next().next().find('input:first').val();
				var order_id = $('#order_id').val();
				
				if(old_part_number == part_number && part_order_id==order_id){
					add = false;
				}
			});
			if(add==true){
				ele_pp.find('td:first').next().find('input:first').val(part_number);
				ele_pp.find('td:first').next().next().next().next().find('input:first').val(part_description);
				$(document).trigger('close.facebox');
			}else{
				alert('This part is already added');
			}
	});
	//ends here
	//company search
	var ele_pp;
	$('a.searchcompanybtn').live('click',function(event) {
		event.preventDefault();
		ele_pp = $(this).parent().parent();
		$.facebox(function() { 
			$.post('<?php echo site_url();?>callcenter/pop/company', {unq:ajaxunq()}, 
			function(data) { $.facebox(data) });
		});
	});
	$('a.setcompany').live('click',function(event) {
			event.preventDefault();
			var ele1 = $(this).parent().parent();
			var company =ele1.find('td:first').next().find('a:first').html();
			var add = true;
			if(add==true){
				ele_pp.find('td:first').next().next().next().next().next().next().next().next().find('input:first').val(company);
				$(document).trigger('close.facebox');
			}
	});
});
function setNewSerialNumber()
{
	var call_status = $("#cstatuslist").val();
	$('#cstatuslist').change(function() {
		call_status = $("#cstatuslist").val();
	});
	if(call_status==3){
		$.facebox({ ajax: '<?php echo site_url();?>callcenter/pop/productserialnumber?unq='+ajaxunq() });
	}else{
		return true;
	}
}
function getSerialHistory(call_id)
{
	if(call_id){
		$.facebox({ ajax: '<?php echo site_url();?>callcenter/pop/serialhistory?call_id='+call_id+'&unq='+ajaxunq() });
	}
}
function getServiceCenterByCity(id){
	var city_id = $("#city_select").val();
	var product_id = $("#product_select").val();
	if(product_id!=='' && city_id!=''){
		loading("sc_box");
		var params = 'city_id='+city_id+'&product_id='+product_id+'&unq='+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo base_url();?>autocallassign/getcentersbycity",
				data	:	params,
				success	:	function (data){
					//$("#servicecenter_select").val(data);
					getEngineersBySc(data);
					var dt=eval('(' + data + ')');
					if(dt.total>1){
						getServiceCenterAssign(data);
					}
					if(dt.total==1){
						$("#servicecenter_select").val(dt.centers);
					}
					//$("#sc_box").html(data);
					hideloading();
					}								
			});
	}
	/*var params = 'city_id='+city_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/getcentersbycity",
			data	:	params,
			success	:	function (data){
				$("#servicecenter_select").val(data);
				getEngineersBySc(data);
				//$("#sc_box").html(data);
				hideloading();
				}								
		});*/
}
function getServiceCenterAssign(data){
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>callcenter/pop/getservicecenterlist', { data:data,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function setServiceCenter(sc_id){
	$(document).trigger('close.facebox');
	$("#servicecenter_select").val(sc_id);
}
function getProductBybrand(brand_id,active){
	loading("select_product_box");
	var params = 'brand_id='+brand_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/getproductsbybrand",
			data	:	params,
			success	:	function (data){
				$("#select_product_box").html(data);
				hideloading();
				}								
		});
}
function getdistrictbyzone(zone_id,active)
{
	loading("select_district_box");
	var params = 'zone_id='+zone_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>zones/getdistrictbyzone",
			data	:	params,
			success	:	function (data){
				$("#select_district_box").html(data);
				hideloading();
				}								
		});
}
function getcitiesbydistrict(district_id,active){
	loading("select_city_box");
	var params = 'district_id='+district_id+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/getcitiesbydistrict",
			data	:	params,
			success	:	function (data){
				$("#select_city_box").html(data);
				hideloading();
				}								
		});
}
function getEngineersBySc(sc_id)
{
	loading("engineer_select_box");
	var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/getengineersbysc",
			data	:	params,
			success	:	function (data){
				$("#engineer_select_box").html(data);
				hideloading();
				}								
		});
}
function getDefectCodeBySymptom(symptom_id,active){
	loading("defect_select_box");
	var params="symptom_id="+symptom_id+"&active="+active+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>callcenter/getdefectcodebysymptom",
			data	:	params,
			success	:	function (data){
				$("#defect_select_box").html(data);
				hideloading();
				}								
		});//end  ajax	
}
function getRepairCodeBydefect(defect_id,active)
{
	loading("repair_select_box");
	var params="defect_id="+defect_id+"&active="+active+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>callcenter/getrepaircodebydefect",
			data	:	params,
			success	:	function (data){
				$("#repair_select_box").html(data);
				hideloading();
				}								
		});//end  ajax	
}
function getModels(value){
	getSymptomsByProduct(value);
	loading("select_model_box");
	var params = 'product_id='+value+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/getmodels",
			data	:	params,
			success	:	function (data){
				$("#select_model_box").html(data);
				hideloading();
				}								
		});
}
function setModelNumber(model_id,model_number,category_name)
{
	document.getElementById('model_id').value=model_id;
	document.getElementById('lblmodelnumber').innerHTML=model_number;
	document.getElementById('product_model_number').value=model_number;
	document.getElementById('lblcategory').innerHTML=category_name;
	$(document).trigger('close.facebox');
}
function getreasonlist(call_status,active)
{
	if(call_status>0){
		loading("reasonlist");
		var params = 'call_status='+call_status+'&active='+active+'&unq='+ajaxunq();
		$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/getreasonlist",
			data	:	params,
			success	:	function (data){
						$("#reasonlist").html(data);
						/*$("#call_reason_pending").change(function(){
							if(this.value=='Part Pending'){
								getPartList();
							}
						});*/
						hideloading();
						}								
		});
	}
}
function searchmodel()
{
	
}
function getPartList()
{
	$.facebox(function() { 
      $.post('<?php echo site_url();?>pop/partlist', { ajaxaction: "getpartlist" ,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function getSearchPop()
{
	$.facebox({ ajax: '<?php echo site_url();?>callcenter/getsearchpop?unq='+ajaxunq() });
}
function getCustomerList()
{
	$.facebox({ ajax: '<?php echo site_url();?>customers/getcustomerpop?unq='+ajaxunq() });
}
function getCitySearchList()
{
	$.facebox({ ajax: '<?php echo site_url();?>cities/search/getcitysearchpop?unq='+ajaxunq() });
}

function getmodellist()
{
		$.facebox({ ajax: '<?php echo site_url();?>callcenter/getmodellist?unq='+ajaxunq() });
	
}
function setCustomer(cust_id){
	$("#hdncallcust_id").val(cust_id);
	showloading();
	var params = 'cust_id='+cust_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>customers/getCustomer",
			data	:	params,
			success	:	function (data){
				var dt = eval("("+data+")");
				$("#cust_first_name").val(dt.cust_first_name);
				$("#cust_last_name").val(dt.cust_last_name);
				$("#cust_phone_home").val(dt.cust_phone_home);
				$("#cust_phone_office").val(dt.cust_phone_office);
				$("#cust_phone_mobile").val(dt.cust_phone_mobile);
				$("#cust_landmark").val(dt.cust_landmark);
				$("#zone_select").val(dt.zone_id);
				getdistrictbyzone(dt.zone_id,dt.district_id);
				getcitiesbydistrict(dt.district_id,dt.city_id);
				$(document).trigger('close.facebox');
				hideloading();
			}								
	});
}
function clearForm()
{
	$("#callRegistrationForm").validationEngine('hideAll');
}
function closeForm()
{
	//$("#callForm").validationEngine('hideAll');
	window.location.href='<?php echo site_url();?>callcenter/calls';
	$("#callForm").validationEngine('detach');
}
function showJobCard()
{
	var call_id = '<?php echo $this->uri->segment(3);?>';
	$.facebox({ ajax: '<?php echo site_url();?>callcenter/jobcard?call_id='+call_id+'&unq='+ajaxunq() });
}
function printJobCard()
{
	var call_id = $("#hdncallid").val();
	var content = document.getElementById("cardContent");
	var pri = document.getElementById("ifmcontentstoprint").contentWindow;
	pri.document.open();
	pri.document.write(content.innerHTML);
	pri.document.close();
	pri.focus();
	pri.print();
	changecallstatus(call_id);
	$(document).trigger('close.facebox');
}
function changecallstatus(call_id){
	showloading();
	var call_status = 1;
	var call_print_jobcard = 1;
	var params = 'call_id='+call_id+"&call_status="+call_status+"&call_print_jobcard="+call_print_jobcard+"&unq"+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/changecallstatus",
			data	:	params,
			success	:	function (data){
					$("#cstatuslist").val(1);
					//if(data){  window.document.location.reload(true);}
				}								
	});
}
function getSymptomsByProduct(product_id){
	loading('symptom_select_box');
	var params = 'product_id='+product_id+"&unq"+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>callcenter/getsymptomsbyproduct",
			data	:	params,
			success	:	function (data){
					$("#symptom_select_box").html(data);
				}								
	});
}
function getcallreasonpendinglist(call_id){
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>callcenter/pop/pendingreasons', { call_id:call_id,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function popitup(url) {
	newwindow=window.open(url,'name','width=270,height=220,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');
	if (window.focus) {newwindow.focus()}
	return false;
}
function getWarrantyUpload(call_id){
	$.facebox({ ajax: '<?php echo site_url();?>callcenter/pop/warrantyform?call_id='+call_id+'&unq='+ajaxunq() });
}
function Reminders()
{
	var call_id = $('#hdncallid').val();
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>callcenter/pop/reminder', { ajaxaction: "getreminderlist" ,call_id:call_id,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
function download_manual(model_id){
	var fileUrl='<?php echo site_url()?>manual/download_manual/?model_id='+model_id;
	window.location.replace(fileUrl);
}
function showparts(){
	var call_id = $('#hdncallid').val();
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>callcenter/pop/parts', { call_id:call_id,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function partlist(){
	var call_id = $('#hdncallid').val();
	var params = 'call_id='+call_id+'&unq='+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo base_url();?>callcenter/pop/parts",
				data	:	params,
				success	:	function (data){
							$("#getcallpartlist").html(data);
					}								
		});
}

function add_used_parts(){
	var ele  = $("table#used_parts_list");
	var usdpart_qty_attr = ele.find('tr:last td').next().next().next().next().next().next().next().next().find('input:first').attr('id');
	//alert (usdpart_qty_attr);
	if (usdpart_qty_attr != undefined){
			var str = usdpart_qty_attr.split("_",2);
			var str_id = 'usdpqty_'+(parseInt(str[1])+1);
		}
	else 
	{
		var str_id = 'usdpqty_1';
		}
	
	
	var html = '';
	html+='<td><?php echo $this->lang->line('part_number');?></td>';
	html+='<td><input readonly="readonly" type="text" name="part_number[]" value="" class="pnum text-input "><input type="hidden" name="used_parts_id[]" value="0" class="text-input"></td>';
	html+='<td><a class="searchparstbtn"><?php echo icon("search","Search","gif","icon");?></a></td>';
	html+='<td><?php echo $this->lang->line('description');?></td>';
	html+='<td><input readonly="readonly" type="text" name="part_description[]" value="" class="text-input"></td>';
	
	html+='<td><?php echo $this->lang->line('company_name');?></td>';
	html+='<td><input  style="text-align:left" type="text" readonly="readonly" name="used_company[]" value="" class="text-input"></td>';
	
	
	html+='<td><?php echo $this->lang->line('quantity');?></td>';
	html+='<td><input id="'+str_id+'" style="text-align:center" type="text"  name="part_quantity[]" value="" class="text-input validate[required,custom[onlyNumberSp]]"></td>';
	html+='<td><a class="removerow"><?php echo icon('delete','Delete','png');?></a></td>';
	html+='<td></td>';
	html='<tr>'+html+'</tr>';
	$("#used_parts_list tbody").append(html);
}



function add_pending_parts(){
	var ele = $("table#pending_parts_list");
	var qty = ele.find('tr:last td').next().next().next().next().next().next().find('input:first').attr('id');
	//alert(qty);
	if (qty != undefined){
			var str = qty.split("_",2);
			var str_id = 'usdpqty_'+(parseInt(str[1])+1);
		}
	else 
	{
		var str_id = 'reqqty_1';
		}
	
	var html='';
	html+='<td><?php echo $this->lang->line('part_number');?></td>';
	html+='<td><input readonly="readonly" type="text" name="order_part_number[]" value="" class="pnum text-input"><input type="hidden" name="order_part_id[]" value="0" class="text-input"><input type="hidden" name="calls_orders_id[]" value="0" class="text-input"></td>';
	html+='<td><a class="searchpendingparstbtn"><?php echo icon("search","Search","gif","icon");?></a></td>';
	html+='<td><?php echo $this->lang->line('description');?></td>';
	html+='<td><input readonly="readonly" type="text" name="order_part_description[]" value="" class="text-input"></td>';
	
	html+='<td><?php echo $this->lang->line('quantity');?></td>';
	html+='<td><input id="'+str_id+'" style="text-align:center" type="text" name="order_part_quantity[]"  value="" class="text-input validate[required,custom[onlyNumberSp]]"></td>';
	
	html+='<td style="text-align:right"><?php echo $this->lang->line('order_number');?></td>';
	html+='<td style="text-align:left"><?php echo $this->lang->line('-');?></td>';
	html+='<td><input type="hidden" name="parts_order_id[]" value="0" /></td>';
	html+='<td><a class="removerow"><?php echo icon('delete','Delete','png');?></a></td>';
	html+='<td></td>';
	html='<tr>'+html+'</tr>';
	$("#pending_parts_list tbody").append(html);
}







function getcallpartlist(){
	showloading();
	$("#getcallpartlist").slideUp('slow');
	var call_id = $("#hdncallid").val();
	var order_id = $("#order_id").val();
	var currentpage = $("#currentpage").val();
	var searchtxt = $("#searchtxt").val();
	var model_id = '<?php echo $call_details->model_id;?>';
	var params = 'order_id ='+order_id+'&model_id='+model_id+'&searchtxt='+searchtxt+'&call_id='+call_id+"&currentpage="+currentpage+"&unq"+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>callcenter/pop/getcallpartlist",
			data	:	params,
			success	:	function (data){
					hideloading();
					$("#getcallpartlist").html(data);
					$("#getcallpartlist").slideDown('slow');
				}								
	});
}
function getcompanylist(){
	showloading();
	$("#getcompany").slideUp('slow');
	
	var currentpage = $("#currentpage").val();
	var searchtxt = $("#searchtxt").val();
	
	var params = 'searchtxt='+searchtxt+"&currentpage="+currentpage+"&unq"+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>callcenter/pop/getcompanylist",
			data	:	params,
			success	:	function (data){
					hideloading();
					$("#getcompanylist").html(data);
					$("#getcompanylist").slideDown('slow');
				}								
	});
}

function showReRegister(uid){
	window.location.href='<?php echo site_url();?>callcenter/callreregistration/'+uid;
}
function showhappycall(uid){
	window.location.href='<?php echo site_url();?>callcenter/happycall/'+uid;
}
function reopen(uid){
	window.location.href='<?php echo site_url();?>callcenter/reopen/'+uid;
	}




function checkpart(){
	var call_id = $("#hdncallid").val();
	var part_defect_id= $("#part_defect_id").val();
	var part_defected_no = $("#part_defected_no").val();
	var part_defected_quantity= $("#part_defected_quantity").val();
	var part_defected_desc = $("#part_defected_desc").val();
	var part_defected_sn = $("#part_sn").val();
	var params="&part_defected_no="+part_defected_no+"&part_defected_desc="+part_defected_desc+"&part_defected_quantity="+part_defected_quantity+"&sn="+part_defected_sn+"&call_id="+call_id+"&unq="+ajaxunq();
	 if(part_defect_id==0){
			//add case
			add= 1;
			$("#rowdata tr").each(function(index) {
				 var ptn = $(this).find('td:first').next().find('input:last').val();
				 var ptn = $(this).find('td:first').next().find('input:last').val();
				 			});
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>callcenter/checkpart",
					data	:	params,
					success	:	function (data){
						if (data == 1 && add == 1){
							checkdatabase();
							}
						if (data==3){
							alert ('Invalid Item Number!!');
							}
						if (data ==2){
							alert('Invalid part Quantity');
							}
						if (data ==4){
							alert('Invalid Quantity & Defected Part No');
							}		
						}								
				}); 
	}
}




function showpendingdiv(div_value){
	
	if(div_value == "Part Pending"){
		document.getElementById('part_pending').style.display = 'block';
	}	
}


function showmodellist(){
	
	var brand = $('#brand_select_search').val();
	var product= $('#product_select_search').val();
	var searchtxt = $("#searchtxt").val();
	var currentpage = $("#currentpage").val();
	var start = parseInt(currentpage);
	var params = "searchtxt="+searchtxt+"&currentpage="+currentpage+"&start="+start+'&product='+product+"&brand="+brand+"&ajaxunq="+ajaxunq();
	
	$.ajax({
		   type		:	"POST",
		   url		:	"<?php echo base_url();?>callcenter/modellisting",
		   data		:	params,
		   success	:	function(data){
			   
			   $("#modelcalllist").html(data);
				$("#modelcalllist").slideDown('slow');
			   }
		   });
	}
	






function setModel(model_id){
	
	showloading();
	//alert (model_id);
	var params = 'id='+model_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>productmodel/getmodeldetails",
			data	:	params,
			success	:	function (data){
			
				var dt = eval("("+data+")");
				//alert(dt.model_id);
				//$("#model_id").val(dt.model_id);
				$("#brand_select").val(dt.brand_id);
				getProductBybrand(dt.brand_id,dt.product_id);
				getModelsSearch(dt.product_id,dt.model_id);
			 	$("#model_select_box").html(dt.model_id);
				getServiceCenterByCity(dt.model_id);
				//$("#model_id").val(dt.model_id);
				$(document).trigger('close.facebox');
				hideloading();
				
				//getServiceCenterByCity(dt.model_id);
			}								
	});
}

function getModelsSearch(value,active){
	
	loading("select_model_box");
	var params = 'product_id='+value+'&active='+active+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/getmodelsSearch",
			data	:	params,
			success	:	function (data){
				$("#select_model_box").html(data);
				hideloading();
				}								
		});
}
function getProductBybrandsearch(brand_id){
	var params = 'brand_id='+brand_id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/getproductsbybrandsearch",
			data	:	params,
			success	:	function (data){
				//alert(data);
				$("#product_popsearch").html(data);
				hideloading();
				}								
		});
}


function getpartsbysearch()
{
	showloading();
	var upart_search = $('#upart_search').val();
	var sc_id=$('#pop_parts_sc_id').val();
	var engineer_id=$('#pop_parts_eng_id').val();
	var currentpage = $("#currentpage").val();
	//alert(upart_search + sc_id + engineer_id);
	var params = 'upart_search='+upart_search+'&sc_id='+sc_id+'&engineer_id='+engineer_id+'&currentpage='+currentpage+'&unq='+ajaxunq();
	$.ajax({
		   type		:	"POST",
		   data		:	params,
		   url		:	"<?php echo base_url();?>callcenter/pop/getpartslistpopup",
		   success	: 	function(data){
			   			
						$("#partsearchlist").hide();
				  	 	$("#partsearchlist").html(data);
				   		$("#partsearchlist").slideDown('slow');
						hideloading();
		   							}
									
		   });
}


function dispatchset(call_id){
	var params = 'call_id='+call_id+'&unq='+ajaxunq();
	$("#btn_dispatch").hide();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>callcenter/dispatchDevice",
			data	:	params,
			success	:	function (data){
				//alert(data);
				$("#btn_dispatch").hide();
				hideloading();
				}								
		});
	
}



</script>
