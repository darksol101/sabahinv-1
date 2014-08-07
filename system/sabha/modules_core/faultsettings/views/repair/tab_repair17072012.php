<div><?php echo $this->load->view('faultsettings/repair/addrepair');?></div>
<style>
#zone_search,#district_search{
	width:15%!important;
}
</style>
<div class="toolbar1">
  <form onsubmit="return false">Search
  <input type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) {$('#currentpage').val(0);showrepairlist();}" />
  <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showrepairlist();" /> <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></div>
</form>
<div id="repairlist" style="display:none;"></div>