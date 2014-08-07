<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showCityTable();
	cancel();
	$("#frmcity").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){savecity();}}  
	});
})
</script>
<form onsubmit="return false" id="frmcity" name="frmcity">
  <table width="100%">
  <col width="5%" /><col width="15%" /><col width="5%" /><col width="15%" /><col width="5%" /><col width="15%" /><col width="10%" /><col width="17%" />
    <tr>
      <th><label><?php echo $this->lang->line('zone'); ?>: </label></th>
      <td><?php echo $zone_select; ?></td>
	  <th><label><?php echo $this->lang->line('district'); ?>: </label></th>
      <td><div id="select_district_box"><?php echo $district_select; ?></div></td>
	  <th><label><?php echo $this->lang->line('city'); ?>: </label></th>
      <td><input type="text"  name="city_name" id="city_name" class="validate[required,length[0,100]] text-input"/></td>
      <th><label><?php echo $this->lang->line('serivcecenter');?>: </label></th>
      <td><?php echo $servicecenter_select;?></td>
    </tr>
   <tr>
      <td colspan="5"><input type="hidden" value="0" id="hdncity_id" name="hdncity_id" />
      	<input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" onClick=""/>
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
</form>
