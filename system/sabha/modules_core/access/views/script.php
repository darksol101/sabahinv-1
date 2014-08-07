<link rel="stylesheet"
	href="<?php echo base_url(); ?>assets/style/css/jquery.ui.accordion.css"
	type="text/css" media="all" />
<link
	rel="stylesheet"
	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script
	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js"
	type="text/javascript"></script>
<script
	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js"
	type="text/javascript"></script>
<script
	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.accordion.min.js"
	type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	$( "#accordion" ).accordion({
		autoHeight: false,
		collapsible: true							
	});
})

function checkallChild(main_menu){

	if($('#id_'+main_menu).is(':checked'))	{		
		$('.child_'+main_menu).each(function () {
				
				$(this).attr('checked', true);
		});
	}else{
		
		$('.child_'+main_menu).each(function () {
			
				$(this).attr('checked', false);					 
			 //  var sThisVal = (this.checked ? $(this).val() : "");
		  });
	}
}
	
function cancel(){
	document.getElementById('usergroup').value='';
	var total_datas = document.getElementById('total_data').value;
	var total_ids = total_datas.split(",");
	var i;
	for(i=0;i<total_ids.length-1;i++){
		$("#id_"+total_ids[i]).attr('checked', false);
	}
	$("#hdngroupid").val(0);
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".formError").remove();
}
function selectAllAccess(){
	var total_datas = document.getElementById('total_data').value;
	var total_ids = total_datas.split(",");	
	for(i=0;i<total_ids.length;i++){
		if($('#select_all').is(':checked'))
			$("#id_"+total_ids[i]).attr('checked', true);
		else
			$("#id_"+total_ids[i]).attr('checked', false);
	}
	
}
function closeform()
{
	window.location='<?php echo site_url();?>';
}
function showGroupTable(){
	$("#grouplist").css({'display':'none'});
	showloading();
	var searchtxt=$("#searchgrptxt").val();
	var currentpage = $("#currentpage").val();
	var params="currentpage="+currentpage+"&ajaxaction=getgrouplist&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>access/getgrouplist",
			data	:	params,
			success	:	function (data){
				$("#accesslist").css({'display':'none'});
				$("#accesslist").html(data);
				$("#accesslist").slideDown('slow');
				hideloading()
				}								
		});//end  ajax
	}

function showgroup(id){
	showloading();
	cancel();	
	if (id=='')
		id = 0;
	var params='id='+id+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>access/getgroup",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')')
				var i = 0;
				for(i=0;i<dt.length;i++){
					$("#id_"+dt[i].id).attr('checked', true);
				}
				$("#usergroup").val(id);
				$("#hdngroupid").val(id);
				$(".formError").remove();
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax

}

function save(){
	showloading();
	var total_datas = document.getElementById('total_data').value;
	var total_ids = total_datas.split(",");	
	var hdngroupid=$("#hdngroupid").val();
	var i;
	var selected ='';
	var unselected ='';
	
	for(i=0;i<total_ids.length-1;i++){		
		if($('#id_'+total_ids[i]).is(':checked')){			
			//saveRow(params[i],i,params.length); 
			selected = selected+total_ids[i]+",";
		}
		else
			unselected = unselected+total_ids[i]+",";
	}
	
	var params="selected="+selected+"&unselected="+unselected+"&hdngroupid="+hdngroupid+"&unq="+ajaxunq();
	//alert (params);
	//return false;
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>access/savegroup",
			data	:	params,
			success	:	function (data){
					showGroupTable()
					cancel();
					hideloading(data)
			}								
		});//end  ajax
		

}

</script>
