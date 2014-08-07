<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})

function getCalls(){
	loading("callslist");
	$("#callslist").html('');
	$("#btn_generate_box").html('');
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var params="ajaxaction=getcallslist&from="+from_date+"&to="+to_date+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>jobcard/getcallslist",
			data	:	params,
			success	:	function (data){
				$("#btn_generate_box").html('<input class="button" type="submit" value="<?php echo $this->lang->line('generate_jobcards'); ?>" name="btn_generate" id="btn_generate" onClick="generatejobcard();"/>');
				$("#callslist").css({'display':'none'});
				$("#callslist").html(data);
				$("#callslist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
}
function generatejobcard()
{
	
	if ($(".chkb:checked").length > 0){
		$.facebox({ ajax: '<?php echo site_url();?>jobcard/getjobcardspop?unq='+ajaxunq() });
	}else{
		alert('Please select first');
	}
}
function checkAll()
{
	var checked = $("#chk").attr("checked");
	if(checked=='checked'){
		$(".chkb").attr({"checked":"checked"});
	}else{
		$(".chkb").removeAttr("checked");
	}
}
function printJobCard()
{
	if(confirm('Are you sure you want to print?')){
		var call_id = $("#hdncallid").val();
		var content = document.getElementById("cardContent");
		var pri = document.getElementById("ifmcontentstoprint").contentWindow;
		pri.document.open();
		pri.document.write(content.innerHTML);
		pri.document.close();
		pri.focus();
		pri.print();
		changecallstatus();
	}
	$(document).trigger('close.facebox');
}
function changecallstatus()
{
	var call_id = new Array();
	$("input.chkb").each(function(index){
		if(this.checked){
			call_id[index] = this.value;
		}
	});
	var params="call_id="+call_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>jobcard/changecallstatus",
			data	:	params,
			success	:	function (data){
				}								
		});//end  ajax
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>