<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
});
function getServiceCenterSelect(from_sc_id){
    loading("from_sc_box");
    var params = 'sc_id='+from_sc_id+'&unq='+ajaxunq();
    $.ajax({			
            type	:	"POST",
            url		:	"<?php echo base_url();?>parts/bad_parts/getservicecenters",
            data	:	params,
            success	:	function (data){
                                        $('#from_sc_box').html(data);
                                        hideloading();
                            }								
            });
}
function saveparttosc(){
    var from_sc_id = $('#from_sc_id').val();
    var to_sc_id = $('#to_sc_id').val();
    var part_number = $('#part_number').val();
    var part_quantity = $('#part_quantity').val();
    
    var params = 'from_sc_id='+from_sc_id+'&to_sc_id='+to_sc_id+'&part_number='+part_number+'&part_quantity='+part_quantity+'&unq='+ajaxunq();
        $.ajax({			
                type	:	"POST",
                url		:	"<?php echo base_url();?>badparts/transfer/saveparttosc",
                data	:	params,
                success	:	function (data){
                                        //$('#from_sc_id').val('');
                                        //$('#to_sc_id').val('');
                                        //$('#part_number').val('');
                                        //$('#part_quantity').val('');
                                        hideloading(data);
                                }								
                });
                
}
function getPartBySc(){
    var sc_id = $('#from_sc_id').val();
    loading('parts_box');
    var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
	$.ajax({			
		type	:	"POST",
		url		:	"<?php echo base_url();?>badparts/transfer/getpartsbysc",
		data	:	params,
		success	:	function (data){
						$('#parts_box').html(data);
						hideloading();
				}								
		});
}
function checkParts(){
    var from_sc_id = $('#from_sc_id').val();
    var part_number = $('#part_number').val();
    var part_quantity = parseInt($('#part_quantity').val());
    var quantity = part_quantity;
    var params = 'from_sc_id='+from_sc_id+'&part_number='+part_number+'&part_quantity='+part_quantity+'&unq='+ajaxunq();
        $.ajax({			
                type	:	"POST",
                url		:	"<?php echo base_url();?>badparts/transfer/checkparts",
                data	:	params,
                success	:	function (data){
                			$('tbody#tbdata tr').each(function(){
                				quantity+= parseInt($(this).find('td').next().find('input').val());
                    		});
                            if(quantity <= data){
                            	var tr ='<tr>';
                                tr+= '<td><input type="hidden" name="return_sc_detail_id[]" value="" /><input type="hidden" name="part_numbers[]" value="'+part_number+'" class="part_number" />'+part_number+'</td>';
                                tr+= '<td style="text-align: center">'+part_quantity+'<input type="hidden" name="part_quantities[]" value="'+part_quantity+'" class="part_quantity" /></td>';
                                tr+= '</tr>';
                                $('#tbdata').append(tr);
                                $('#part_number').val('');
                                $('#part_quantity').val('');
                            }else{
                                alert('Not enough quantity.');
                                $('#part_quantity').val('');
                             }
                             //hideloading(data);
                           }								
                });
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
function closeform()
{
	window.location='<?php echo base_url();?>badparts/transfer';
}

function createChalan()
{
	var order_id = 1;
	
	
		$.facebox(function() { 
			$.post('<?php echo site_url();?>orders/partialdeliverychalan',{order_id:order_id}, 
			function(data) { $.facebox(data) });
		})
	}
</script>