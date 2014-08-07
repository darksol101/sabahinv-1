<div class="">
<?php if(count($list)==0){
	printf($this->lang->line('unavailable_data'),$this->input->post('selected_text'));
	die();
}
?>
<style type="text/css">
div#graph img{display:none!important;}
</style>
<div style="padding-left:67px;">
<table width="90%" cellpadding="0" cellspacing="0" border="1" class="tblgrid" style="border:#999 1px solid;">
<thead><col width="1%" /><col width="30%" /><col /><col /><col /><col /><col /></thead>
<tbody>
<tr><th style="text-align:center">S.No.</th><th>Engineer</th><th>Open Calls</th><th>Pending Calls</th><th>Partpending Calls</th><th>Closed Calls</th><th>Cancelled Calls</th></tr>
<?php $i=1; foreach($list as $engineer){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?>
	<tr <?php echo $trstyle;?>><td style="text-align:center"><?php echo $i;?></td><td><?php echo $engineer->engineer_name;?></td><td style="text-align:center"><?php echo $engineer->total_call_open;?></td><td style="text-align:center"><?php echo $engineer->total_call_pending;?></td><td style="text-align:center"><?php echo $engineer->total_call_partpending;?></td><td style="text-align:center"><?php echo $engineer->total_call_closed;?></td><td style="text-align:center"><?php echo $engineer->total_call_cancelled;?></td></tr>
<?php $i++; }?>
</tbody>
</table>
</div>
<div  style="min-width:916px;overflow:auto; padding:8px;">
<div id="graph"><?php echo icon('loading','Loading...','gif');?></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var from_dt = $("#from_date").val();
	var to_dt = $("#to_date").val();
	
	var myData = new Array(<?php $i=1; foreach($list as $row){?>['<?php echo $row->engineer_name;?>',<?php echo $row->total_call_open;?>,<?php echo $row->total_call_pending;?>,<?php echo $row->total_call_partpending;?>,<?php echo $row->total_call_closed;?>,<?php echo $row->total_call_cancelled;?>]<?php if(count($list)!=$i){?>,<?php }?><?php $i++;}?>);
	<?php
	$arr = array();
	$str = '';
	foreach($list as $row){
		$arr[] = "'#00689C'";
		$str = implode(",",$arr);
	}
	?>
	var from_dt = '<?php echo date("Y, F d",date_to_timestamp($this->input->post('from_date')));?>';
	var to_dt = '<?php echo date("Y, F d",date_to_timestamp($this->input->post('to_date')));?>';
	var myChart = new JSChart('graph', 'bar');
	myChart.setDataArray(myData);
	myChart.setTitle('Engineer Performance ( '+from_dt+' - '+to_dt+' )');
	myChart.setTitleColor('#000');
	myChart.setAxisNameX('Call Center');
	myChart.setAxisNameY('Calls');
	myChart.setAxisNameFontSize(16);
	myChart.setAxisNameColor('#999');
	myChart.setAxisValuesAngle(10);
	myChart.setAxisValuesColor('#000');
	myChart.setAxisColor('#000');
	myChart.setAxisNameFontSizeY(8);
	myChart.setAxisNameFontSizeX(8);
	myChart.setAxisValuesFontSizeX(7);
	myChart.setAxisWidth(1);
	myChart.setAxisPaddingTop(60);
	myChart.setAxisPaddingLeft(70);
	myChart.setAxisPaddingBottom(40);
	myChart.setTextPaddingLeft(20);
	myChart.setTitleFontSize(10);
	myChart.setBarColor('#005B93', 1);
	myChart.setBarColor('#1A9F04', 2);
	myChart.setBarColor('#ff9900', 3);
	myChart.setBarColor('#A9A9A9', 4);
	myChart.setBarColor('#C42000', 5);
	myChart.setBarValuesColor('#000');
	myChart.setBarBorderWidth(1);
	myChart.setBarBorderColor('#C4C4C4');
	myChart.setBarSpacingRatio(50);
	myChart.setBarOpacity(.9);
	myChart.setLegendShow(true);
	myChart.setLegendPosition('right top');
	myChart.setLegendForBar(1, 'Open Call');
	myChart.setLegendForBar(2, 'Pending Call');
	myChart.setLegendForBar(3, 'Partpending Call');
	myChart.setLegendForBar(4, 'Closed Call');
	myChart.setLegendForBar(5, 'Cancelled Call');
	myChart.setGrid(true);
	<?php if(count($list)>10){?>
	var width = <?php echo count($list);?>*110;
	myChart.setSize(width, 321);
	<?php }else{?>
	myChart.setSize(916, 321);
	<?php }?>
	myChart.setLegendFontSize(7);
	myChart.setLineSpeed(10);
	myChart.draw();
});
</script>
</div>