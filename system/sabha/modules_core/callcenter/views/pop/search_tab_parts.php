<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script type="text/javascript">
$(document).ready(function(){ getpartsbysearch(); });
</script>
<div class="toolbar1">
<form onsubmit="return false;">
<table width="50%" border="0">
<tr>
<td><?php echo $this->lang->line('search');?>:</td>
<td><input class="text-input"  type="text" value="" id="upart_search" onKeydown="Javascript: if (event.keyCode==13){ getpartsbysearch(); }" />
</td>
<td>
        <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="getpartsbysearch();" /> 
        <span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
        </td>
</tr>
</table>
</form>
</div>
<input type="hidden" id="pop_parts_sc_id" value="<?php echo $sc_id;?>"/>
<input type="hidden" id="pop_parts_eng_id" value="<?php echo $engineer_id;?>"/>
<input type="hidden" name="currentpage" id="currentpage" value="0" />
<div>&nbsp;</div><div>&nbsp;</div>
<div id="partsearchlist" style="display:none"></div>