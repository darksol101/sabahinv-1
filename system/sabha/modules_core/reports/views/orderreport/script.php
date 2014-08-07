<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})

function showOrderReport(){

	var sc_id_from = $('#sc_id_from').val();
	var sc_id_to = $('#sc_id_to').val();
	var from_date = $('#fromdate').val();
	var to_date =$('#todate').val();
	//alert (sc_id_from+' '+sc_id_to+' '+from_date+' '+to_date);
	$.ajax({
			type : "post",
			url	 : "<?php echo site_url();?>reports/orderreport/getorderreport",
			data : {sc_id_from:sc_id_from,sc_id_to:sc_id_to,from_date:from_date,to_date:to_date},
			success: function(data){
				//alert(data);

				$("#orderreportlist").css({'display':'none'});
				$("#orderreportlist").html(data);
				$("#orderreportlist").slideDown('slow');
				hideloading()

				}


		});
}

</script>