<?php $this->load->view('add');?>

<div class="toolbar1">
  <form onsubmit="return false;">
    Search
    <input type="text"	name="psearchtxt" id="psearchtxt" value="" class="text-input"
	onkeydown="Javascript: if (event.keyCode==13) showProductList();" />
    <img
	src="<?php echo base_url();?>assets/style/img/icons/search.gif"
	class="searchbtn" onclick="showVendorList();" /> <span class="message"><span
	class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
  </form>
</div>
<div id="vendorlist"></div>
