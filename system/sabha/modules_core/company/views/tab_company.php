<?php $this->load->view('add');?>

<div class="toolbar1">
  <form onsubmit="return false;">
    Search
    <input type="text"	name="searchtxt" id="searchtxt" value="" class="text-input"
	onkeydown="Javascript: if (event.keyCode==13) showCompanyList();" />
    <img
	src="<?php echo base_url();?>assets/style/img/icons/search.gif"
	class="searchbtn" onclick="showCompanyList();" /> <span class="message"><span
	class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
  </form>
</div>
<div id="companylist"></div>