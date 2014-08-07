<style>
.mod_npcal_table{border:1px solid #000000; padding: 5px; width: 160px;}
.mod_npcal_header{height: 30px; font-size: 16px;font-family:Verdana, Geneva, sans-serif;font-size:.8em}
.mod_npcal_dayname{padding: 6px 6px; font-family:Verdana, Geneva, sans-serif;font-size:.8em}
.mod_npcal_today {border: 1px solid #ff0000; text-align: center;}
.mod_npcal_table tr td{ cursor:pointer; border: #D3D3D3 1px solid; color: #555555;font-size:.8em}
.split1,.split2,.split3,.split4,.split5,.split6,.split7{
	border:none!important;
}
</style>
<script src="<?php echo base_url(); ?>assets/jquery/jquery-1.7.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".mod_npcal_table tr td").click(function(){
	var params = 'str='+this.id+'&unq='+ajaxunq();
	if(this.id){
		var arr = (this.id).split("_",3);
		var str_date = (arr[2]+"/"+arr[1]+"/"+arr[0]);
		opener.document.getElementById('lblnepalidate').innerHTML = str_date;
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo base_url();?>calendar/getengdate",
				data	:	params,
				success	:	function (data){
					//document.getElementById('product_purchase_date').value(data);
					opener.document.getElementById('product_purchase_date').value= data;
					self.close();
					}								
			});
		}
	})
});
function ajaxunq(){
	var d = new Date();
    var unq = d.getYear()+''+d.getMonth()+''+d.getDay()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds();
	return unq;
}
</script>
<?php echo $calender;?>
