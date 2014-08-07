<div><?php echo $this->load->view('engineers/addengineer');?></div>
<div class="toolbar1">
<style>
#sc_search{
	width:20%!important;
}
</style>
  <form onsubmit="return false">
    Search
    <input type="text" name="searchtxt" id="searchengtxt" value=""  class="text-input" onKeydown="Javascript: if (event.keyCode==13) $('#currentpage').val(0);showEngineerTable();" />&nbsp;<span><?php echo $servicecenter_search;?></span>
    <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showEngineerTable();" /> <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
  </form>
</div>
<div id="engineerlist" style="display:none;"></div>
