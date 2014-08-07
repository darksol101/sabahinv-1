<div><?php echo $this->load->view('servicecenters/addservicecenter');?></div>
<div class="toolbar1">
<form onsubmit="return false">
  Search
  <input type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) showServiceCenterList();" />
  <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="showServiceCenterTable();" /> <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
  </div>
</form>
<div id="servicecenterlist" style="display:none;"></div>
