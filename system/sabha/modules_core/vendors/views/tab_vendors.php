<?php $this->load->view('add');?>

<div class="toolbar1">
<form id="fname" name="fname">
<table width="80%">
 <col width="10%" /><col width="5%" /><col width="5%" /><col width="10%" /><col width="5%" /><col width="5%" />
    <tr><td>Vendor name</td>
    <td>
    <input type="text"	name="namesearch" id="namesearch" class="text-input"
	onkeydown="Javascript: if (event.keyCode==13) showVendorList();" /></td>
    <td><?php echo $this->lang->line('phone');?></td>
    <td>
    <input type="text"	name="phnsearch" id="phnsearch" class="text-input"
	onkeydown="Javascript: if (event.keyCode==13) showVendorList();" /></td>
    <td><img
	src="<?php echo base_url();?>assets/style/img/icons/search.gif"
	class="searchbtn" onclick="showVendorList();" /> </td>
    
    <td><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    <td><span class="message"><span
	class="message_text"></span></span></td>
    </tr>
  
  <table>
  </form>
</div>
<div id="vendorlist"></div>
