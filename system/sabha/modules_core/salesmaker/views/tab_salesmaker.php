<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<div><?php echo $this->load->view('salesmaker/add');?></div>



<div class="toolbar1">

<form onsubmit="return false;">Search

<input type="text" name="searchtxt" onKeydown="Javascript: if (event.keyCode==13){ showSalesMakerList(this.value)}" id="searchtxt" value="" class="text-input"/>

<img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="showSalesMakerList($('#searchtxt').val())" />
<span	class="message">
 	<span class="message_text"></span>
</span>
</form>
</div>

<div id="partlist" style="width: 100%;">
	
</div>