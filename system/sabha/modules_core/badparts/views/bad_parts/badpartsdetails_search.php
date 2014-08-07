<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
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
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>',
			
		});
	});
	$(document).ready(function(){ $('.loading').hide();
		showbadpartsdetails();
													 
	});
</script>
<input type="hidden" id="sc_id" value="<?php echo $this->uri->segment('3');?>" />
<input type="hidden" id="part_number" value="<?php echo $this->uri->segment('4'); ?>" />

<div class="toolbar1">
<form onsubmit="return false">
    <table width="100%" cellpadding="0" cellspacing="0">
        <col width="25%" /><col width="25%" /><col />
        <tr>
            <td>
            <?php echo $this->lang->line('from');?>
            <input type="text" name="from_date" id="from_date" value=""  class="text-input datepicker" readonly="readonly"/>
            </td>
            <td>
			<?php echo $this->lang->line('to');?>
            <input type="text" name="to_date" id="to_date" value=""  class="text-input datepicker" readonly="readonly"/>
            </td>
            <td><img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="showbadpartsdetails();" /> 
            </span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
            </td>
        </tr>
    </table>
  
</form>
<script>

</script>
</div>
<div id="badpartsdetails"
	style="width: 100%;"></div>
