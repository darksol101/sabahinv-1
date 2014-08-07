 <?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
 <?php 
	$i=1;
    foreach($bill_parts as $details){    	
		$trstyle=$i%2==0?' class="even" ': ' class="odd"';
		?>
		<tr<?php echo $trstyle;?>>
			<td><input type="hidden" id="" name="sales_return_details_id[]" value="0" /><input type="hidden" id="" name="price[]" value="<?php echo $details->price;?>" /><input type="hidden" id="" name="part_id[]" value="<?php echo $details->part_id;?>" /><?php echo $i;?></td>
			<td><input type="hidden" id="" name="pnum[]" value="<?php echo $details->part_number;?>"  /><span class="lbl"><?php echo $details->part_number;?></span></td>
            
            <td style="text-align: left;"><input type="hidden" name="pdesc[]" value="<?php echo $details->part_desc;?>" class="text-input" /><span class="lbl"><?php echo $details->part_desc;?></span></td>
            <td style="text-align: center; "><input type="hidden" name="comp[]" value="<?php echo $details->company_id;?>" class="text-input" /><span class="lbl"><?php echo $details->company_title;?></span></td>
            
			<td style="text-align: center;"><input type="hidden" name="pqty[]" value="<?php echo $details->part_quantity;?>" class="text-input" /><span class="lbl"><?php echo $details->part_quantity;?></span></td>
            <td style="text-align:center"><input type="text" name="p_return_pqty[]" class="text-input return_qty" value="<?php echo $details->part_quantity;?>" /></td>			
			<td style="text-align: right;"><input type="hidden" name="prate[]" value="<?php echo $details->part_rate;?>" class="text-input" /><span class="lbl"><?php echo sprintf('%.2f',$details->part_quantity * $details->part_rate);?></span></td>
        </tr>
        <?php $i++;}?>
		
<script type="text/javascript">
$(document).ready(function(){
	$('input.return_qty').change(function(){
		var tr = $(this).parent().parent();
		var tds = $(tr).children();
		var part_qty = $(tds[4]).find('input').val();
		var part_return_qty = $(tds[5]).find('input').val();
		var part_rate = $(tds[6]).find('input').val();

		part_qty = parseInt(part_qty);
		part_return_qty = parseInt(part_return_qty);
		if(part_return_qty <= part_qty ){
			return_price = ((part_return_qty*part_rate/100)*100).toFixed(2);
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
})
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
			ResetParts();
		}
}
</script>