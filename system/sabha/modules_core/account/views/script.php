<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?php echo base_url();?>assets/jquery/ezpz_tooltip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/jquery/custom.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
function CheckAll(params){
	var tr = $(params).parent().next().find('tr');
	$(tr).each(function(){
		var chk = $(this).find('td').find('input[type="checkbox"]');
		$(chk).each(function(){
			var checked = $(this).attr("checked");
			if($(params).is(':checked')==true){
				$(this).attr({"checked":"checked"});
			}else{
				$(this).removeAttr("checked");
				$(this).removeAttr("checked");				
			}	
		})		
	})
}
function printPopUp(url){
	$.facebox(function() { 
			$.post('<?php echo site_url();?>'+url, { task:'print', unq:ajaxunq()}, 
			function(data) { $.facebox(data) });
		});
}
function PrintReport(){
	var content = document.getElementById("reportContent");
	var pri = document.getElementById("ifmcontentstoprint").contentWindow;
	pri.document.open();
	pri.document.write(content.innerHTML);
	pri.document.close();
	pri.focus();
	pri.print();
	$(document).trigger('close.facebox');
}
</script>