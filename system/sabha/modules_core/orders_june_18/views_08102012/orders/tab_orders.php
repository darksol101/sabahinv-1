<style type="text/css">
#fromdate,#todate{ width:50%!important;}
#searchtxt{ width:70%!important}
.toolbar1 table td img.ui-datepicker-trigger{ margin-left:7px!important;}
.toolbar1 td{ padding: 0px 0px!important;}
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
<div class="toolbar1">
  <form onsubmit="return false;">
   <table width="100%">
   <col width="25%" /><col width="15%" /><col width="15%" /><col />
   	<tr>
    	<td>Search <input type="text" name="searchtxt" id="searchtxt" value="" class="text-input" onkeydown="Javascript: if (event.keyCode==13){ $('#currentpage').val(0);showOrderList();}"  /></td>
        <td>From : <input id="fromdate" name="fromdate" value=""  class="datepicker text-input" type="text"></td>
        <td> To : <input id="todate" name="todate" value=""  class="datepicker text-input" type="text" ></td>
        <td><img
	src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="javascript:$('#currentpage').val(0);showOrderList();" /> <span class="message"><span
	class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    </tr>
   </table>
   
    
   
    
     <span style="margin-left:20px;"></span>  
                    </h3>
                    <div class="content toggle no_padding" id="ordertablelist"></div>
  <input type="hidden" name="currentpage" id="currentpage" value="0"  />
  </form>
</div>
<div id="orderlist"></div>

