<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<div><?php echo $this->load->view('assign');?></div>
<div class="toolbar1">
<form onsubmit="return false;">
<table width="100%">
	<col width="25%" /><col width="25%" /><col width="20%" /><col /><col /><col />
   
	<tr>
    	<td><?php echo $this->lang->line('bin');?> <input type="text" name="binsearch" id="binsearch" value="" class="text-input" onKeydown="Javascript: if (event.keyCode==13){ showpartbin_details();}" /></td>
  
  <td><?php echo $this->lang->line('part');?> <input type="text" name="partsearch" id="partsearch" value="" class="text-input" onKeydown="Javascript: if (event.keyCode==13){ showpartbin_details();}" /></td>
 
  
  <td><?php echo $servicecenter_search;?></td>
  
<td><?php echo $partbin_search?></td>

      
    <td>
        <img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="showpartbin_details();" /> 
        <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
        </td>
       
    </tr>
</table>
</form>
</div>

<div id="partbin_details"
	style="width: 100%;"></div>
    
