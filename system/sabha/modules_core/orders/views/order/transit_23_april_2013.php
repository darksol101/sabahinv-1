<style type="text/css">
#fromdate,#todate{ width:50%!important;}
#searchtxt{ width:75%!important}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>

<script>
	$(function() {
		$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
		});
	});
</script>
<?php //print_r($transit_details);?>
<?php  foreach ($transit_details as $transit_detail){?>

<form onsubmit="return false" >
<table style="width:100%;">
<tr> 
<td> <label> Courior Number </label></td>
<td>  <input type="input" id="courior_number"  class="text-input" value="<?php echo $transit_detail->courior_number ;?>" />  </td>

</tr>
<tr> 
<td> <label> Number of Boxes </label></td>
<td>  <input type="input" id="box_number"  class="text-input"  value="<?php echo $transit_detail->box_number;?>"/>  </td>
</tr>
<tr> 
<td> <label> Vehicle Number </label></td>
<td>  <input type="input" id="vehicle_number" value="<?php echo $transit_detail->vehicle_number;?>" class="text-input" />  </td>
</tr>
<tr> 
<td> <label> Transit Number </label></td>
<td>  <input type="input" id="transit_number" value="<?php echo $transit_detail->transit_number;?>" class="text-input" />  </td>
</tr>
<tr> 
<td> <label> Courior Date </label></td>
<td>   <input id="couriordate" name="fromdate" value="<?php echo $transit_detail->courior_date;?>"  class="datepicker text-input" type="text" style="width:80%;">  </td>
</tr>
<tr>
<td colspan="2" align="right"></td>
</tr>
</table>
<input type="submit" value="Save Transit Detail" onclick="confirmtransit();" class="button" style="position: absolute; margin-left: 175px; margin-top: 16px;"/>
<input type="hidden" id="transit_detail_id" value="<?php  echo $transit_detail->transit_detail_id; ?>" />

</form>
<?php }?>