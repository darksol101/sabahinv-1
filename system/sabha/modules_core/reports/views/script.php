<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})

function getreportslist(){
	showloading();
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var params="ajaxaction=getreportslist&from_date="+from_date+"&to_date="+to_date+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reports/getreportslist",
			data	:	params,
			success	:	function (data){
				$("#reportslist").css({'display':'none'});
				$("#reportslist").html(data);
				$("#reportslist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}

</script>