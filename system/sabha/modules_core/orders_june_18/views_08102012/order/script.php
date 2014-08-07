<script type="text/javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});						   
});

function cancelform()
{
	//$("#status").val('');
	//$("#orderForm").validationEngine('hideAll');
}

function closeform()
{
	window.location='<?php echo base_url();?>orders';
}
</script>