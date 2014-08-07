<div><?php echo $this->load->view('users/adduser');?></div>
<div class="toolbar1">
  <form onsubmit="return false">
 Search
  <input type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) showTable();"  />
  <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="showTable();" /> <span class="message"><span class="message_text"></span></span>
</form>
</div>
<div id="userlist" style="display:none;"></div>
