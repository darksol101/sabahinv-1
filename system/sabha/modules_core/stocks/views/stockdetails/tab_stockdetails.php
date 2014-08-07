<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<style type="text/css">
#from_date,#to_date{ width:55%;}
table.tblgrid tfoot td{border-top:#ccc 1px solid!important;}
.toolbar1 table td img.ui-datepicker-trigger{ margin-left:7px!important;}
.toolbar1 td{ padding: 0px 0px!important;}
</style>
<div class="toolbar1">
<form onsubmit="return false">
    <table width="100%" cellpadding="0" cellspacing="0">
        <col width="15%" /><col width="15%" /><col />
        <tr>
            <td>
            <?php echo $this->lang->line('from');?>
            <input type="text" name="from_date" id="from_date" value=""  class="text-input datepicker"/>
            </td>
            <td>
			<?php echo $this->lang->line('to');?>
            <input type="text" name="to_date" id="to_date" value=""  class="text-input datepicker"/>
            </td>
            <td><img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="showstockDetails();" /> 
            <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
            </td>
        </tr>
    </table>
  <input type="hidden" name="sc_id" id="sc_id" value="<?php echo $this->uri->segment(3);?>" />
 <input type="hidden" name="part_number" id="part_number" value="<?php echo $this->uri->segment(4);?>" /> 
  <input type="hidden" name="company_id" id="company_id" value="<?php echo $this->uri->segment(5);?>" /> 
</form>
</div>
<div id="stocklistdetail"></div>