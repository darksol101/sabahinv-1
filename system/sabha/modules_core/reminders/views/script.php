<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	showReminderList();
});
$(document).bind('close.facebox', function() {
 cancelform();
});
function saveReminder(){
	showloading();
	var call_id = $('#hdncallid').val();
	var reminder_remarks=$("#reminder_remarks").val();
	var reminder_id = $("#hdnreminderid").val();
	if(reminder_remarks==''){
		$('#reminder_remarks').validationEngine('showPrompt', 'Reminder remarks is required', '');
		return false;
	}
	var params="reminder_id="+reminder_id+"&call_id="+call_id+"&reminder_remarks="+reminder_remarks+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>reminders/savereminder",
			data	:	params,
			success	:	function (data){
						hideloading(data);
						showReminderList();
				}								
		});//end  ajax
}
function showReminderList(){
	showloading();
	var searchtxt = $("#searchtxt").val();
	var currentpage = $("#currentpage").val();
	var call_id = $('#hdncallid').val();
	var params = 'call_id='+call_id+'&currentpage='+currentpage+'&searchtxt='+searchtxt+'&ajaxaction=getreminderlist&unq='+ajaxunq();
	$.ajax({
		   type		:	'POST',
		   url		:	'<?php echo site_url();?>reminders/getreminders',
		   data		:	params,
		   success	:	function (data){
			   			hideloading();
						cancelform();
			   			$("#reminderlist").hide();
						$("#reminderlist").html(data);
		   				$("#reminderlist").slideDown();
						}
		   });
}
function cancelform()
{
	$("#reminderForm").validationEngine('hideAll');
	$("#reminderForm").validationEngine('detach');
	$("#reminder_remarks").val('');
	$("#hdnreminderid").val(0);
	$("#savereminder").val('<?php echo $this->lang->line('add');?>');
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}
function editReminder(reminder_id){
	var params = 'reminder_id='+reminder_id+'&unq='+ajaxunq();
	$.ajax({
		   type		:	'POST',
		   url		:	'<?php echo site_url();?>reminders/getreminderdetails',
		   data		:	params,
		   success	: function(data){
			   			var dt = eval('('+data+')');
						$("#reminder_remarks").val(dt.reminder_remarks);
						$("#hdnreminderid").val(dt.reminder_id);
						$("#savereminder").val('<?php echo $this->lang->line('edit');?>');
		   			}
		   })
}
function deleteReminder(reminder_id){
	if(confirm("Are you Sure to delete this reminder")){
		showloading();
		var params="reminder_id="+reminder_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>reminders/deletereminder",
				data	:	params,
				success	:	function (data){
					showReminderList();
					hideloading(data);
					}								
			});//end  ajax
	}
}
function ajaxunq(){
	var d = new Date();
    var unq = d.getYear()+''+d.getMonth()+''+d.getDay()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds();
	return unq;
	}
function showloading(){
	$(".loading").show();
	}
function hideloading($msg){
	$(".loading").hide();
	if($msg){$('.message_text').show();$(".message_text").html($msg);}
	if($msg)$('.message_text').delay(10000).fadeOut("slow");
	}

</script>
