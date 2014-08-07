<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
	
function cancel(){
	var autoincrementId = $('#autoincrementId').val();
	$('#userid').val(autoincrementId);
	$("#username").val("");
	$("#email").val("");
	$("#password").val("");
	$("#rpassword").val("");
	$("#status").val(0);	
	$("#usergroup").val(0);
	$("#hdnuserid").val(0);
	$("#groupname").val('');
	$("#description").val('');
	$("#hdngroupid").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo site_url();?>';
}
function showGroupTable(){
	$("#grouplist").css({'display':'none'});
	showloading();
	var searchtxt=$("#searchgrptxt").val()
	var params="ajaxaction=getgrouplist&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>usergroups/getgrouplist",
			data	:	params,
			success	:	function (data){
				$("#grouplist").css({'display':'none'});
				$("#grouplist").html(data);
				$("#grouplist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}

function showgroup(id){
	showloading();
	var params='id='+id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>usergroups/getgroup",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')')
				$("#groupname").val(dt.details);
				$("#description").val(dt.description);
				$("#frmusergroup #status").val(dt.published);
				$("#hdngroupid").val(dt.usergroup_id);
				$(".formError").remove();
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax

}

function savegroup(){
	showloading()
	var groupname=$("#groupname").val();
	var description=$("#description").val();
	var status=$("#frmusergroup #status").val();	
	var hdngroupid=$("#hdngroupid").val();
	var params="groupname="+groupname+"&description="+description+"&status="+status+"&hdngroupid="+hdngroupid+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>usergroups/savegroup",
			data	:	params,
			success	:	function (data){
				showGroupTable()
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}

function deletgroup(groupid){	
	if(confirm("Are you Sure to delete this Group")){
		showloading()
		var params="groupid="+groupid+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>usergroups/deletegroup",
				data	:	params,
				success	:	function (data){
					showGroupTable()
					hideloading(data)
					}								
			});//end  ajax
	}else{
		return false
	}
}
</script>
