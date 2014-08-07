<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})

function getscreportslist(){
	showloading();
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var params="ajaxaction=getscreportslist&from_date="+from_date+"&to_date="+to_date+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>screports/getscreportslist",
			data	:	params,
			success	:	function (data){
				$("#screportslist").css({'display':'none'});
				$("#screportslist").html(data);
				$("#screportslist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}

</script>