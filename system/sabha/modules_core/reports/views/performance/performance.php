<div class="">
<?php if(count($list)==0){
	echo $this->lang->line('unavailable_data');
	die();
}
?>
<style type="text/css">
div#graph img{display:none!important;}
</style>
<div style="padding-left:67px;">
<table width="30%" cellpadding="0" cellspacing="0" border="1" class="tblgrid" style="border:#999 1px solid;">
<thead>
<col width="1%" /><col width="30%" /><col />
</thead>
<tbody>
<tr><th>S.No.</th><th>User</th><th style="text-align:center">Total Calls Registered</th></tr>
<?php $i=1; foreach($list as $user){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?>
	<tr <?php echo $trstyle;?>><td style="text-align:center"><?php echo $i;?></td><td><?php echo $user->username;?></td><td style="text-align:center"><?php echo $user->total_call_reg;?></td></tr>
<?php $i++; }?>
</tbody>
</table>
</div>
<div id="graph"><?php echo icon('loading','Loading...','gif');?></div>
<!--<script type="text/javascript">
$(document).ready(function(){
	var from_dt = $("#from_date").val();
	var to_dt = $("#to_date").val();
	
	var myData = new Array(<?php $i=1; foreach($list as $row){?>['<?php echo $row->username;?>',<?php echo $row->total_call_reg;?>]<?php if(count($list)!=$i){?>,<?php }?><?php $i++;}?>);
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
	
	var colors = [<?php echo $str;?>];
	var myChart = new JSChart('graph', 'bar');
	myChart.setDataArray(myData);
	myChart.colorizeBars(colors);
	myChart.setTitle('Call Center Performance ( '+from_dt+' - '+to_dt+' )');
	myChart.setTitleColor('#000');
	myChart.setAxisNameX('Call Center');
	myChart.setAxisNameY('Calls');
	myChart.setAxisColor('#000');
	myChart.setAxisNameFontSize(10);
	myChart.setAxisNameColor('#999');
	myChart.setAxisValuesColor('#000');
	myChart.setBarValuesColor('#000');
	myChart.setAxisPaddingTop(60);
	myChart.setAxisPaddingRight(40);
	myChart.setAxisPaddingLeft(70);
	myChart.setAxisPaddingBottom(40);
	myChart.setTextPaddingLeft(20);
	myChart.setTitleFontSize(10);
	myChart.setBarBorderWidth(1);
	myChart.setBarBorderColor('#C4C4C4');
	myChart.setBarSpacingRatio(50);
	myChart.setGrid(true);
	myChart.setSize(840, 321);
	myChart.setLegendFontSize(2);
	myChart.setLineSpeed(10);
	myChart.setBarOpacity(.8);
	myChart.draw();
});
</script>-->
</div>