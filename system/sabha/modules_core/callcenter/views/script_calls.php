<link rel="stylesheet"
	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	var str = (document.location.href).split("?",2);
	var url='';
	if(str[1]){
		url = '?'+str[1];
	}
	if(url){
		$('.pagination a').each(function(index) {
			var href = $(this).attr('href')+url;
			$(this).attr('href',href);
		});
	}
});
function excelDownload()
{
	var str = (document.location.href).split("?",2);
	var url='';
	if(str[1]){
		url = '?'+str[1];
	}
	var fileUrl='<?php echo site_url()?>callcenter/create_excel'+url;
	window.location.replace(fileUrl);
}


function happycalled(uid){
	
	//alert (uid);
	$.facebox(function() { 
			$.post('<?php echo site_url();?>callcenter/happycall_done',{uid:uid},  
			function(data) { $.facebox(data) });
			
		});
	
	}
	
		function happydatess(){
			var	remark = $("#remark").val();
			var call_id = $("#call_id").val();
			var params = "remark="+remark+"&call_id="+call_id;
			$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>callcenter/savecalldatess",
				data	:	params,
				success	:	function (data){
					
						$(document).trigger('close.facebox');
						window.location.href='<?php echo site_url();?>callcenter/callregistration/'+call_id;
						
					}								
			});
			
		}
	
	function reopen(uid){
	window.location.href='<?php echo site_url();?>callcenter/reopen/'+uid;
	}
	
</script>