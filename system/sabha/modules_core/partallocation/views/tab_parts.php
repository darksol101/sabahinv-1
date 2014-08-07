<style>
.toolbar1{margin-bottom:28px}

</style>

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<div><?php echo $this->load->view('partallocation/assign');?></div>
<div class="toolbar1">
<form onsubmit="return false;">
<table width="100%">
	<col width="30%" /><col width="20%" /><col width="20%" /><col /><col /><col />
   
	<tr>
    	<td><?php echo $this->lang->line('search');?> <input type="text" name="searchparttxt" id="searchparttxt" value="" class="text-input" onKeydown="Javascript: if (event.keyCode==13){ allocationlist();}" /></td>
  <td><?php echo $servicecenters_search ;?> 
  <td><?php echo $engineer;?></td>
  
<td><?php echo $company_options?></td>

      
    <td>
        <img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="allocationlist();" /> 
        <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
        </td>
        <td></td> <td><?php // echo $servicecenters;?></td>
    </tr>
</table>
</form>
</div>

<div id="allocationlist"
	style="width: 100%; margin-top: 15px"></div>
    
