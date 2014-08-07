<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div><?php echo $this->load->view('partallocation/revokepage');?></div>
<fieldset>
<legend style="font-size:13px; margin-top:3px;"><b> Transaction details</b></legend>
 </fieldset>
<div class="toolbar1">
<form onsubmit="return false">
    <table width="100%" cellpadding="0" cellspacing="0">
        <col width="25%" /><col width="25%" /><col />
        <tr>
            <td>
            <?php echo $this->lang->line('from');?>
            <input type="text" name="from_date" id="from_date" value=""  class="text-input datepicker"/>
            </td>
            <td>
			<?php echo $this->lang->line('to');?>
            <input type="text" name="to_date" id="to_date" value=""  class="text-input datepicker"/>
            </td>
            <td><img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="showallocationdetails();" /> 
            <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
            </td>
        </tr>
    </table>
  <input type="hidden" name="engineer_id" id="engineer_id" value="<?php echo $this->uri->segment(3);?>" />
 <input type="hidden" name="part_number" id="part_number" value="<?php echo $this->uri->segment(4);?>" /> 
  <input type="hidden" name="company_id" id="company_id" value="<?php echo $this->uri->segment(5);?>" />
  <input type="hidden" name="sc_id" id="sc_id" value="<?php echo $this->uri->segment(6);?>" /> 
 
</form>
<script>

</script>
</div>
<div id="allocationdetails"
	style="width: 100%;"></div>
