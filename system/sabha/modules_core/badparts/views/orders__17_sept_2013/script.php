<script type="text/javascript">
var sn;
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	$('a.editpart').live('click',function(event) {
		event.preventDefault();
		var ele = $(this).parent().parent();
		$("#add_part").val('Edit');
		$("#cancel_part").show();
		$(this).parent().parent().parent().find('tr').removeClass('editactive');
		$(this).parent().parent().addClass('editactive');
		var order_part_id = ele.find('td:first').next().find('input:first').val();
		$("#hdnorder_part_id").val(order_part_id);
		var part_number = ele.find('td:first').next().find('input:last').val();
		var part_desc = ele.find('td:first').next().next().find('input').val();
		var part_quantity = ele.find('td:first').next().next().next().find('input').val();
		
		//alert (company);
		//alert(part_number+'--'+part_quantity+'--'+part_rate) 
// 
		$("#part_number").val(part_number);
		$("#part_desc").val(part_desc);
		$("#part_quantity").val(part_quantity);
		$("#hdnorder_part_id").val(order_part_id);
		
		
	});
	$('a.deletepart').live('click',function(event) {
		if(confirm("Do you want to delete this Part ?")){
			event.preventDefault();
			var ele = $(this).parent().parent();
			var order_part_id = ele.find('td:first').next().find('input:first').val();
			var params="order_part_id="+order_part_id+"&unq="+ajaxunq();
			$.ajax({			
					type	:	"POST",
					url		:	"<?php echo site_url();?>badparts/badparts_order/deleteparts",
					data	:	params,
					success	:	function (data){
						$(ele).remove();
						hideloading(data);
						}								
				});//end  ajax
		}
		
	});
	
		$('a.searchpart').live('click',function(event){
		var from_sc_id = $('#badparts_from_sc_id').val();
		$.facebox(function() { 
				$.post('<?php echo site_url();?>badparts/badparts_order/badpartlist',{from_sc_id:from_sc_id}, 
				function(data) { $.facebox(data) });
			})
		});
		
		$('a.setorderpart').live('click',function(){
		var ele = $(this).parent().parent();
		//console.log(ele.find('td:first').next().html());
		var part_number = ele.find('td:first').next().html();
		var part_desc = ele.find('td:first').next().next().html();
		var part_quantity = ele.find('td:first').next().next().next().html();
		
		
		
		
		
		sn = $("#rowdata tr:last td.sn_td").html();
	sn++
	var html = '';
		var trclass = (sn%2==0)?'even':'odd';
		html+= '<td class="sn_td">'+sn+'</td>';
		html+='<td><input type="hidden" value="" id="" name="badparts_order_part_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
	html+='<td style="text-align: left;"><input type="hidden" name="pdesc[]" value="'+part_desc+'" class="text-input" /><span class="lbl">'+part_desc+'</span></td>';
		html+='<td style="text-align: center;"><input type="hidden" name="pqty[]" value="'+part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
		html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
		html='<tr class="'+trclass+'">'+html+'</tr>';
		
		
		$("#rowdata").append(html);
		
		 $(this).parent().parent().remove();
												  
		});
	
	
	
});
function checkpart(){
   var part_number = $("#part_number").val();
	var part_quantity = $("#part_quantity").val();
	var part_desc = $("#part_desc").val();
	var order_part_id = $("#hdnorder_part_id").val();
	$("#orderForm").validationEngine('hideAll');
	var validate =1;
	
	
	if(part_number==''){
		$('#part_number').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		validate =0;
	}
	if(part_quantity==''){
		$('#part_quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		validate =0;
	}
	
	sn = $("#rowdata tr:last td.sn_td").html();
	sn++
	var html = '';
		var trclass = (sn%2==0)?'even':'odd';
		html+= '<td class="sn_td">'+sn+'</td>';
		html+='<td><input type="hidden" value="" id="" name="badparts_order_part_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
	html+='<td style="text-align: left;"><input type="hidden" name="pdesc[]" value="'+part_desc+'" class="text-input" /><span class="lbl">'+part_desc+'</span></td>';
		html+='<td style="text-align: center;"><input type="hidden" name="pqty[]" value="'+part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td style="text-align:center"></td>';
		html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
		html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
		html='<tr class="'+trclass+'">'+html+'</tr>';
		
		
		
		
		
		if(order_part_id==0){
			//add case
			$("#rowdata tr").each(function(index) {
				 var ptn = $(this).find('td:first').next().find('input:last').val();
				 if(ptn == part_number){
					 if(confirm('Item Number '+part_number+' already exists. Do you want to update it?')){
						$(this).find('td:first').next().next().next().find('input').val(part_quantity);
						$(this).find('td:first').next().next().next().find('span.lbl').html(part_quantity);
						$(this).find('td:first').next().next().find('input').val(part_desc);
						$(this).find('td:first').next().next().find('span.lbl').html(part_desc);
						 }
					 validate = 0;
				 }
			});
			
		}else{
			var ele = $(".editactive");
			//alert(company);
			ele.find('td:first').next().next().next().find('input').val(part_quantity);
			ele.find('td:first').next().next().next().find('span.lbl').html(part_quantity);
			ele.find('td:first').next().next().find('input').val(part_desc);
			ele.find('td:first').next().next().find('span.lbl').html(part_desc);
			//$(this).find('td:first').next().next().next().next().find('input').val(company);
			//$(this).find('td:first').next().next().next().next().find('span.lbl').html(company);
			//alert(company);
			$(ele).parent().find('tr').removeClass('editactive');
		}
		
		
		
		
		if (validate == 1){
		$("#rowdata").append(html);
		
		
		$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_desc").val('');
		$("#hdnorder_part_id").val('');
		}
		
		
}

function cancelrow(){
		$("#part_number").val('');
		$("#part_quantity").val('');
		$("#part_desc").val('');
		$("#hdnorder_part_id").val('');
	}

function checkpart1(){
	
	if(part_number==''){
		$('#part_number').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		validate =0;
	}
	if(part_quantity==''){
		$('#part_quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
		validate =0;
	}
	
	
	}

</script>