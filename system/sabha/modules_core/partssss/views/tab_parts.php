<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<div><?php echo $this->load->view('parts/addpart');?></div>
<div class="toolbar1">
<form onsubmit="return false;">Search <input type="text" name="searchtxt" id="searchtxt" value="" class="text-input" onKeydown="Javascript: if (event.keyCode==13){ $('#currentpage').val(0);showPartList(); }" />
<img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showPartList();" /> <span	class="message"><span class="message_text"></span></span><span
	class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
     <span style="padding-left:16px;"><a class="btn" onclick="uploadForm();"><?php echo icon("upload","Click to upload","png","icon");?></a></span>
    <span style="padding-left:16px;"><a class="btn" onclick="downloadtemplate();"><?php echo icon("download","Click to download template","png","icon");?></a></span>
</form>
</div>
<div id="partlist"
	style="width: 100%;"></div>
