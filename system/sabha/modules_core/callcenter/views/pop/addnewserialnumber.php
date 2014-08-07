<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
#ct_zone_search,#ct_district_search{position:relative;}
#ct_zone_search .loading,#ct_district_search .loading{position:absolute; left:0px; top:0; width:100%; height:30px; margin:0 auto; text-align:center;}
#facebox .footer {visibility:visible;}
</style>
<script type="text/javascript">
function saveregForm(){
	var new_serial_number = $("#serial_number").val();
	if(new_serial_number){
		$("#product_serial_number_new").val(new_serial_number);
		$(document).trigger('close.facebox');
		alert('New serial is added');
		$("#btn_save").click();
	}
}
</script>
<div style="width:650px;">
<div class="toolbar1">
  <form onsubmit="return true" method="post">
  <table width="70%">
  	<col width="30%" /><col width="70%" />
  	<tr>
    	<td><label>Add New Serial Number</label></td>
        <td><input type="text" name="serial_number" id="serial_number" class="text-input" value="" /></td>
    </tr>
    <tr>
    	<td colspan="2"><input type="button" value="<?php echo $this->lang->line('add'); ?>" onclick="saveregForm();" name="btn_save" id="btn_save_new" class="button" /></td>
    </tr>
  </table>
   <span class="message"><span class="message_text"></span></span><input type="hidden" name="currentpage" id="currentpage" value="0"  /></form></div>
<div id="listcities"></div>
</div>