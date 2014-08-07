 <script language='javascript'>
$(document).ready(function(){
		$("#td_bin_search").hide();
		$("#td_part_search").hide();
	 showsearchbutton();
     showpartbin_details();
	$("#addbin").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ savebindetail();}}  
	});
})
</script>

<form  onSubmit="return false" id="addbin" name="addbin">
  <table width="35%" cellpadding="0" cellspacing="0">
  <col width="35%" /><col  />
    <tr>
      <td><label> <?php echo $this->lang->line('service_center')?></label></td>
      <td><?php echo $servicecenters ; ?></td>
      <td></td>
    </tr>
    <tr>
      <td><label><?php echo $this->lang->line('bin_name')?> </label></td>
      <td><?php echo $partbin;?> </td>
      <td id="td_bin_search"><img alt="Search City" title="Search Bin" class="btn"
					onclick="getBinSearch();"
					src="<?php echo base_url();?>assets/style/img/icons/search.gif"
					style="margin-bottom: -8px;" /></td>
    </tr>
    <tr>
      <td><label><?php echo $this->lang->line('part');?></label></td>
      <td><span id="parts_pop"><?php echo $parts;?></span></td>
      <td id="td_part_search"> <img alt="Search City" title="Search Parts" class="btn"
					onclick="getPartSearch();"
					src="<?php echo base_url();?>assets/style/img/icons/search.gif"
					style="margin-bottom: -8px;" /></td>
    </tr>
    <tr>
    <td></td>
      <td><input type="hidden" value="0" id="partbin_id" name="partbin_id" />
        <input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit"	value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button"	value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancelform()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
        </td>
    </tr>
  </table>
</form>
