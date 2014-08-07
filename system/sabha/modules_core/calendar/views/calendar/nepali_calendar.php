<style type="text/css">
#cal_wrapper{ position:relative}
#cal_wrapper .popup{position:absolute;left:0;top:-24px;background-color:#fff;color:red;font-family:Arial;font-weight:700;font-size:10pt;z-index:2;visibility:hidden;width:auto;min-width:200px;border-color:#000;border-style:solid;border-width:0px!important;padding:5px 0; margin-left:0px;}
.mod_npcal_table {width: 100%; font-size: .9em; border-collapse: collapse; margin:0 0 .4em; }
.mod_npcal_table th{ padding: .7em .3em; text-align: center; font-weight: bold; border: 0; line-height:normal!important; color:#000!important; }
.mod_npcal_table td{ border: 0; padding: 1px!important; text-align: right; text-decoration: none; line-height:normal!important;}
.mod_npcal_table td a{background: url("<?php echo base_url();?>assets/style/css/images/ui-bg_glass_75_e6e6e6_1x400.png") repeat-x scroll 50% 50% #E6E6E6;border: 1px solid #D3D3D3;color: #555555;font-weight: normal;display: block;padding: 0.2em;text-align: right; text-decoration: none; font-size:11px; line-height:11px; cursor:pointer}
#cal{border:1px solid #aaa;  background:#fff; border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px; display:inline-block; padding:0.2em 0.2em 0; width:100%; font-family:Verdana, Geneva, sans-serif; font-size:11px!important;}
.cal_title{background:#CCCCCC;border: 1px solid #AAAAAA;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;color: #222222;font-weight: bold;line-height: 1.8em;margin: 0 0em;text-align: center; padding:0.2em 0;}
.cal_title span{width:90px; display:inline-block;}
.split1,.split2,.split3,.split4,.split5,.split6,.split7{border:none!important;}
.mod_npcal_table td a.cal_today{background: url("<?php echo base_url();?>assets/style/css/images/ui-bg_glass_55_fbf9ee_1x400.png") repeat-x scroll 50% 50% #FBF9EE!important;border: 1px solid #FCEFA1;color: #363636;}
</style>

<span id="cal_btn" onclick="ShowPop('cal');"><img src="<?php echo base_url();?>assets/style/img/icons/nepali_calendar.png" id="img_btn" /></span>
<span id="cal_wrapper">
	<span class="popup" style="text-align:right">
       <span id="cal"></span>
    </span>
</span>
<?php 
$this->load->library('nepalicalendar');
$current_year = date('Y');
$current_month = date('m');
$current_day = date('d');

$current_date =  $this->nepalicalendar->eng_to_nep($current_year, $current_month, $current_day);
$current_year = $current_date['year'];
$current_month = $current_date['month'];
$current_day = $current_date['date'];
?>
<script type="text/javascript">
var ajax_action = false;
$(document).ready(function(){
	getcalendar();
	$(document).click(function(e){
		if(e.target.id=='img_btn'){
			$("#cal").show();
		}else{
			$("#cal").hide();
		}
	});	
	$('#cal').click(function() {
		return false;
	});
});
function convertdate(year,month,day){
	loading("cal");
	var params = 'year='+year+'&month='+month+'&day='+day+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo base_url();?>calendar/getengdate",
			data	:	params,
			success	:	function (data){
				var dt=eval('(' + data + ')');
				$("#product_purchase_date").val(dt.eng_date);
				$("#lblnepalidate").html(dt.nep_date);
				$("#cal").fadeOut('slow');
				ajax_action = false;
				}								
		});
}
function setcalendarurl(yr,mth){
	ajax_action = true;
	getcalendar();
}
function ShowPop(id){
	if($("#cal").css('visibility')=='hidden'){
		document.getElementById(id).style.visibility = "visible";
		$("#cal_wrapper").css({'visibility':'visible','display':'inline-block'});
		$(".loading").remove();
		document.getElementById(id).style.display = 'block';
		$('#popup').show();
	}else{
		HidePop('cal');
	}
}
function getcalendar(){
	var year = $("#year").val();
	var month = $("#month").val();
	if($("#year").length==0){
		year = '<?php echo $current_year;?>';
		month = '<?php echo $current_month;?>';
	}
	loading("cal");
	var day = '';
	var params = 'year='+year+'&month='+month+'&day='+day;
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>calendar/calendar/getcalendar",
			data	:	params,
			success	:	function (data){
				$("#cal").css({'display':'none'});
				$("#cal").html(data);
				$("#cal").slideDown('slow');
				}								
		});
}
function HidePop(id){
   document.getElementById(id).style.visibility = "hidden";
}
</script>
