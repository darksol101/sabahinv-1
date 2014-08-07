<div><?php echo $this->load->view('engineers/addengineer');?></div>
<div class="toolbar1">
  <form onsubmit="return false">
    Search
    <input type="text" name="searchtxt" id="searchengtxt" value=""  class="text-input" onKeydown="Javascript: if (event.keyCode==13) $('#currentpage').val(0);showEngineerTable();" />
    <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showEngineerTable();" /> <span class="message"><span class="message_text"></span></span>
  </form>
</div>
<div id="engineerlist" style="display:none;"></div>
