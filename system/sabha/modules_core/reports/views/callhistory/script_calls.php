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
	var fileUrl='<?php echo site_url()?>reports/callhistory/create_excel'+url;
	window.location.replace(fileUrl);
}

</script>