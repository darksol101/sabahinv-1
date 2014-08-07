<style>#fromdate,#todate{ width:50%!important;} </style>

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
<div class="toolbar1">
  <form onsubmit="return false;">
  <table>
  
  <col width="20%"/>
  <col width="17%"/>
  <col width="1%"/>
  <col width="21%"/>
  <col width="1%"/>
  <col width="21%"/>
  <col width="1%"/>
  <tbody>
  <tr> 
  
    <td><?php echo $servicecenter;?> </td>
    
    <td><span id="engineer_box"> <?php echo $engineer;?> </span></td>
    
     <td >From:</td>
     
      <td ><input id="fromdate" name="fromdate" value=""  class="datepicker text-input " type="text"></td>
        
        <td> To: </td>
     
      <td> <input id="todate" name="todate" value=""  class="datepicker text-input " type="text" ></td>
   
    <td>
    <img
	src="<?php echo base_url();?>assets/style/img/icons/search.gif"
	class="searchbtn" onclick="getreport211();" /> <span class="message"><span
	class="message_text"></span></span><span class="loading"><?php // echo icon("loading","Loading","gif","icon");?></span>
   
   
    </td>
    </tr>
    </tbody>
    </table>
  </form>
</div>
<div id="report211list" style="margin-top:15px;"></div>
