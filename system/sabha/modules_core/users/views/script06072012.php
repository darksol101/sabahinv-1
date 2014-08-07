<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
})
	
function showTable(){
	$("#userlist").css({'display':'none'});
	showloading();
	var searchtxt=$("#searchtxt").val()
	var params="ajaxaction=getuserlist&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>users/getuserlist",
			data	:	params,
			success	:	function (data){
				$("#userlist").css({'display':'none'});
				$("#userlist").html(data);
				$("#userlist").slideDown('slow');
				var autoincrementId = $('#autoincrementId').val();
				$('#userid').val(autoincrementId);
				hideloading()
				}								
		});//end  ajax
	}
function showuser(userid){
	showloading();
	var params="userid="+userid;
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>users/getuser",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')')
				$("#username").val(dt.username);
				$("#userid").val(dt.user_id);
				$("#email").val(dt.email_address);
				$("#mobile_number").val(dt.mobile_number);
				$("#password").val(dt.password);
				$("#rpassword").val(dt.password);
				$("#status").val(dt.ustatus);
				$("#usergroup").val(dt.usergroup_id);
				$("#sc_id").val(dt.sc_id);
				$("#hdnuserid").val(dt.id);
				$(".formError").remove();
				hideloading();
				}								
		});//end  ajax

}


function save(){
	showloading()
	var username=$("#username").val();
	var userid=$("#userid").val();
	var email=$("#email").val();
	var mobile_number=$("#mobile_number").val();
	var password=$("#password").val();
	var rpassword=$("#rpassword").val();
	var status=$("#status").val();	
	var usergroup=$("#usergroup").val();
	var sc_id = $("#sc_id").val();
	var hdnuserid=$("#hdnuserid").val();
	var params="username="+username+"&userid="+userid+"&email="+email+"&mobile_number="+mobile_number+"&password="+password+"&status="+status+"&usergroup="+usergroup+"&sc_id="+sc_id+"&hdnuserid="+hdnuserid+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>users/saveuser",
			data	:	params,
			success	:	function (data){
				showTable()
				cancel();
				hideloading(data)
				}								
		});//end  ajax
}

function delet(userid){
	
	if(confirm("Are you Sure to delete this User")){
		showloading()
		var params="userid="+userid+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"users/deleteuser",
				data	:	params,
				success	:	function (data){
					showTable()
					hideloading(data)
					}								
			});//end  ajax
	}else{
		return false
	}
}
function changestatus(user_id,ustatus){
	showloading()
		var params="user_id="+user_id+"&ustatus="+ustatus+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"users/chagestatus",
				data	:	params,
				success	:	function (data){
					showTable()
					hideloading(data)
					}								
			});//end  ajax
}
function cancel(){
	var autoincrementId = $('#autoincrementId').val();
	$('#userid').val(autoincrementId);
	$("#username").val("");
	$("#email").val("");
	$("#mobile_number").val('');
	$("#password").val("");
	$("#rpassword").val("");
	$("#status").val(1);	
	$("#usergroup").val(0);
	$("#hdnuserid").val(0);
	$("#groupname").val('');
	$("#description").val('');
	$("#sc_id").val('');
	$("#hdngroupid").val(0);
	$(".formError").remove();
}
function closeform()
{
	window.location='<?php echo site_url();?>';
}
</script>