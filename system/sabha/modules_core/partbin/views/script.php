<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	
	
	$('a.setpart').live('click',function(event) {
			event.preventDefault();
			var ele1 = $(this).parent().parent();
			var part_number = ele1.find('td:first').next().find('a:first').html();
			var company = $.trim(ele1.find('td:first').next().next().find('input:first').val());
			
			var final = part_number+':'+company;
			
			//alert(final);
			var add = true;
			if(add==true){
				$('#part').val(final);
				$(document).trigger('close.facebox');
			}
	});
	
	$('a.setbin').live('click',function(event) {
			event.preventDefault();
			var ele1 = $(this).parent().parent();
			var bin = $.trim(ele1.find('td:first').next().next().next().find('input:first').val());
			
			var add = true;
			if(add==true){
				$('#partbin_name').val(bin);
				$(document).trigger('close.facebox');
			}
	});
	
	
	});
function savebin(){
	showloading();
	var partbin_id=$("#partbin_id").val();
	var bin_name=$("#name").val();
	var bin_desc=$("#desc").val();
	var sc_id = $("#sc_id").val();
	//alert (partbin_id);
	var params="bin_name="+bin_name+"&bin_description="+bin_desc+"&partbin_id="+partbin_id+"&sc_id="+sc_id+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>partbin/savebin",
			data	:	params,
			success	:	function (data){
				
				cancelform();
				showpartbinlist();
				hideloading(data);
				}								
		});//end  ajax
}
function cancelform()
{
	$("#addbin").validationEngine('hideAll');
	$("#name").val('');
	$("#desc").val('');
	$("#partbin_id").val('');
	$("#btn_submit").val('<?php echo $this->lang->line('save');?>');
	$(".message_text").html('');
}
function closeform()
{
	window.location='<?php echo base_url();?>';
}

function showpartbinlist(){
	cancelform();
	showloading();
	var searchtxt=$("#searchtxt").val();
	var sc_id_search = $("#sc_id_search").val();
	var currentpage = $("#currentpage").val();
	var start = parseInt(currentpage);
	var params="currentpage="+currentpage+"&start="+start+"&searchtxt="+searchtxt+"&sc_id_search="+sc_id_search+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>partbin/getpartbinlist",
			data	:	params,
			success	:	function (data){
				$("#partbinlist").html(data);
				$("#partbinlist").hide();
				$("#partbinlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax
}
function editpartbin(partbin_id){
	showloading();
	var params='partbin_id='+partbin_id+'&unq='+ajaxunq();
	//alert (params);
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>partbin/getpartbindetail",
			data	:	params,
			success	:	function (data){
				//alert (data);
				var dt= eval('(' + data + ')')
				$("#name").val(dt.partbin_name);
				$("#desc").val(dt.partbin_desc);
				$("#partbin_id").val(dt.partbin_id);
				$("#sc_id").val(dt.sc_id);
				$("#btn_submit").val('<?php echo $this->lang->line('edit');?>  ');
				hideloading();
				}								
		});//end  ajax
}

function deletepartbin(partbin_id){	
	if(confirm("Are you Sure to delete this company")){
		showloading()
		var params="partbin_id="+partbin_id+"&unq="+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo site_url();?>partbin/deletepartbin",
				data	:	params,
				success	:	function (data){
					showpartbinlist();
					hideloading();
					}								
			});//end  ajax
	}else{
		return false
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
	
	
function showpartbin_details(){
	showloading();
	var bin = $('#binsearch').val();
	var part =$('#partsearch').val();
	var sc_id = $("#sc_id_search").val();
	var partbin_id = $("#partbin_name_search").val();
	var currentpage = $("#currentpage").val();
	var start = parseInt(currentpage);
	var params ="currentpage="+currentpage+"&start="+start+'&sc_id='+sc_id+"&partbin_id="+partbin_id+"&bin="+bin+"&part="+part+"&unq="+ajaxunq();
	$.ajax({
		   
		   type		:	"POST",
		   url		:	"<?php echo site_url();?>partbin/partbindetail",
		   data		: 	params,
		   success	:	function(data){
			   
						$("#partbin_details").html(data);
						$("#partbin_details").hide();
						$("#partbin_details").slideDown('slow');
						hideloading();			
			   
			   }
		   });
	}
	
function getbinlist(sc_id){
	//loading("partbin_name");
	var params = "sc_id="+sc_id+'&unq='+ajaxunq();
	//alert (sc_id);
	$.ajax({
		   type		:	"POST",
		   url		:	"<?php echo site_url();?>partbin/binnamelist",
		   data		: 	params,
		   success	: 	function(data){
						//alert (data);
					$("#partbin_name").html(data);
					//$("#partbin_name_search").html(data);
					//hideloading();
				}
		   
		   });
	}
	function getbinlist1(sc_id){
	//loading("partbin_name");
	var params = "sc_id="+sc_id+'&unq='+ajaxunq();
	//alert (sc_id);
	$.ajax({
		   type		:	"POST",
		   url		:	"<?php echo site_url();?>partbin/binnamelist",
		   data		: 	params,
		   success	: 	function(data){
						//alert (data);
					$("#partbin_name_search").html(data);
					//hideloading();
				}
		   
		   });
	}
	
	function getpartlist(sc_id){
		//alert (sc_id);
		var params = 'sc_id='+sc_id+'&unq='+ajaxunq();
		$.ajax({
			   type		:	"POST",
			   url		: 	"<?php echo base_url();?>partbin/getpart",
			   data		:	params,
			   success	:	function(data){
				  			$("#part").html(data);
				   
				   }
			   });
		}
		
		function savebindetail(){
			var partbin = $("#partbin_name").val();
			
			var sc_id = $("#sc_id").val();
			var part = $("#part").val();
			var params = 'partbin='+partbin+'&sc_id='+sc_id+'&part='+part+'&unq='+ajaxunq();
			$.ajax({
				  
				   type		:	'POST',
				   url		:	 "<?php echo base_url();?>partbin/savebindetail",
				   data		:	params,
				   success	:	function(data){
					   if(data == 1){
					   			alert('Bin already contains part on it');}
						else
						{alert('Item assigned to bin.');
						cancelform1();
						showpartbin_details();
							
						
						}
					   }
				   
				   });
			
			}
function cancelform1()
			{
				$("#addbindetail").validationEngine('hideAll');
				$("#partbin_name").val('');
				$("#sc_id").val('');
				$("#part").val('');
			}
function showsearchbutton(){
	var sc_id = $('#sc_id').val()
	if (sc_id>0){
		$("#td_bin_search").show();
		$("#td_part_search").show();
	}
}

			
function getPartSearch(){
	var sc_id = $('#sc_id').val();
	//alert(sc_id);
			$.facebox(function() { 
			$.post('<?php echo site_url();?>partbin/getPartList', {sc_id:sc_id},
			function(data) { $.facebox(data) });
		})
	
	}
	
function getPartSearchList(){
	
	var sc_id = $('#pop_sc_id').val();
	var searchtxt = $('#searchtxt').val();
	var currentpage = $("#currentpage").val();
	var start = parseInt(currentpage);
	var params= "sc_id="+sc_id+'&searchtxt='+searchtxt+'&currentpage='+currentpage+'&start='+start;
	$.ajax({
				  
				   type		:	'POST',
				   url		:	 "<?php echo base_url();?>partbin/gertPartSearchList",
				   data		:	params,
				   success	:	function(data){ 
				   
				    $("#partsearchlist").hide();
				   $("#partsearchlist").html(data);
				   $("#partsearchlist").slideDown('slow');
				   
				   }
				 
				   });
	}
	
function getBinSearch(){
	var sc_id = $('#sc_id').val();
	//alert(sc_id);
			$.facebox(function() { 
			$.post('<?php echo site_url();?>partbin/getBinList', {sc_id:sc_id},
			function(data) { $.facebox(data) });
		})
	
	}
	
	function getBinSearchList(){
	
	var sc_id = $('#pop_sc_id').val();
	
	var searchtxt = $('#searchtxt').val();
	var currentpage = $("#currentpage").val();
	var start = parseInt(currentpage);
	var params= "sc_id_search="+sc_id+'&searchtxt='+searchtxt+'&currentpage='+currentpage+'&start='+start;
	$.ajax({
				  
				   type		:	'POST',
				   url		:	 "<?php echo base_url();?>partbin/getBinSearchList",
				   data		:	params,
				   success	:	function(data){ 
				   
				    $("#binsearchlist").hide();
				   $("#binsearchlist").html(data);
				   $("#binsearchlist").slideDown('slow');
				   
				   }
				 
				   });
	}

</script>
