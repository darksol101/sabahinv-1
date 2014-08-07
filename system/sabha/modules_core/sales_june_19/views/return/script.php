<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet"	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js"	type="text/javascript"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js"	type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	$('input.return_qty').change(function(){
		var tr = $(this).parent().parent();
		var tds = $(tr).children();
		var part_qty = $(tds[4]).find('input').val();
		var part_return_qty = $(tds[5]).find('input').val();
		var part_rate = $(tds[6]).find('input').val();

		part_qty = parseInt(part_qty);
		part_return_qty = parseInt(part_return_qty);
		if(part_return_qty <= part_qty ){
			return_price = ((part_return_qty*part_rate*100)/100).toFixed(2);
			$(tds[6]).find('span.lbl').html(return_price);
			 
			var total_price = 0.00;
			var total = 0.00;
			$.each($('tbody#rowdata tr'),function(){
					var tbtd = $(this).children();
					part_return_qty  = $(tbtd[5]).find('input').val();
					part_rate  = $(tbtd[6]).find('input').val();
					total = part_return_qty * part_rate;
					total_price = parseFloat(total_price) + parseFloat(total);
			});
			total_price = ((total_price*100)/100).toFixed(2);
			$('input#total_price').val(total_price);
			$('span#spn_total_price').html(total_price);	
			var bill_type = $('#bill_type').val();
			var discount = 0;
			var tax = 0;
			var discount_type = $('#discount_type').val();
			var discount_amount= $('#discount_amount').val();
			if(discount_type==1){
				discount = (total_price*discount_amount/100);
			}else{
				discount = discount_amount;
			}
			discount = ((discount*100)/100).toFixed(2);
			$('#spn_discount_price').html(discount);
			if(bill_type == 1){
				tax = (total_price/1.13)*.13;
				tax = ((tax*100)/100).toFixed(2);
				$('#spn_vat_price').html(tax);
				total_price = parseFloat(total_price) - parseFloat(discount);
				total_price = ((total_price*100)/100).toFixed(2);
				$('#spn_total_taxable_price').html(total_price);
				total_price = Math.round(total_price);
				$('#spn_grand_total').html(total_price.toFixed(2));
			}else{
				total_price = total_price - discount;
				$('#spn_total_taxable_price').html(total_price.toFixed(2));
				tax = (total_price*.13);
				tax = ((tax*100)/100).toFixed(2);
				total_price = parseFloat(total_price)+parseFloat(tax);
				$('#spn_vat_price').html(tax);
				total_price = Math.round(total_price);
				$('#spn_grand_total').html(total_price.toFixed(2));
			}
		}else{
			$(tds[5]).find('input').val(part_qty);
			alert('Not more than '+part_qty+' are allowed.');
		}
	});
});
function showSalesReturnList(){
	loading('salesreturnlist');
	var searchtxt = $('#searchtxt').val();
    var currentpage = $("#currentpage").val();
    var params="searchtxt="+searchtxt+"&currentpage="+currentpage+"&unq="+ajaxunq();
    $.ajax({                        
			type	:  	"POST",
			url    	:	"<?php echo site_url();?>sales/salesreturn/getsalesreturnlist",
			data   	:   params,
			success	:	function (data){
							$('#salesreturnlist').html(data);                                  
							$('#salesreturnlist').slideDown('slow');						
						}
	});
	
}
function getBillPartDetails(bill_id){
	loading('table-loading');
	var sales_type = $('#sales_type').val();
    var sc_id = $("#sc_id").val();
    var params="bill_id="+bill_id+"&unq="+ajaxunq();
    $.ajax({                        
			type	:  	"POST",
			url    	:	"<?php echo site_url();?>sales/salesreturn/getbillpartdetails",
			data   	:   	params,
			success	:	function (data){
						$('#rowdata').hide();
						$('#rowdata').html(data);                                  
						$('#rowdata').slideDown('slow');
						hideloading('table-loading');
            }});
}
function ResetParts(){
	$('#part_number').val('');
	$('#part_desc').val('');
	$('#stock_type').val(0);
	$('#part_qty').val('');
	$('#part_rate').val('');
	$('#max_part_qty').val(0);
}
function addpart(){	
		var part_number = $('#part_number').val();
		var stock_type = $('#stock_type').val();
		var part_rate = $('#part_rate').val();
		var part_qty = $('#part_qty').val();
		var max_part_qty = $('#max_part_qty').val();
		if(part_qty>max_part_qty){
			alert('Not more than '+max_part_qty+' are allowed');
			$('#part_qty').focus();
		}else{
			var tr = $('tr.edirecord');
			var tds =  $(tr).children();
			
			$(tds[4]).find('input').val(part_qty);
			$(tds[4]).find('span.lbl').html(part_qty);
			
			var total_part_price = (part_qty*part_rate).toFixed(2);
			$(tds[5]).find('input').val(part_rate);
			$(tds[5]).find('span.lbl').html(total_part_price);
			$(tr).removeClass('edirecord');
			$('#add_part').val('Add');
			ResetParts();
		}
}
function printSalesReturn(sales_return_id){
	$.facebox(function() { 
		$.post('<?php echo site_url();?>sales/salesreturn/creditnote',{sales_return_id:sales_return_id}, 
		function(data) { $.facebox(data) });
	});
}
function printcreditnote(){
var content = document.getElementById("cardContent");
var pri = document.getElementById("ifmcontentstoprint").contentWindow;
pri.document.open();
pri.document.write(content.innerHTML);
pri.document.close();
pri.focus();
pri.print();
$(document).trigger('close.facebox');
}
function closeform()
{
	window.location='<?php echo base_url();?>sales/salesreturn';
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>
