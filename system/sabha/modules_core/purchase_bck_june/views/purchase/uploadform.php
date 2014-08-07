<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script src="<?php echo base_url();?>assets/jquery/jquery.form.js"></script>

<div style="width:850px;">
<form id="exlForm" name="exlForm" method="post">
	<fieldset>
<table cellpadding="0" cellspacing="0" width="35%">
<col /><col width="7%" />

<legend>Upload an excel file</legend>
	<tr>
    	<td><input type="file" name="excel_file" id="excel_file" class="text-input" ></td>
        <td style="text-align:left"><input type="button" name="upload" id="upload" value="Upload" class="button" onClick="uploadFile();" /></td>
    </tr>
</table> 
</fieldset>  
</form>
<div id="result"></div>
</div>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js"></script>
