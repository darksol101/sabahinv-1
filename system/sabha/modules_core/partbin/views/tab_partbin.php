<?php $this->load->view('add');?>

<div class="toolbar1">

  <form onsubmit="return false;">

  
  
  <table width="100%">
	<col width="30%" /><col width="20%" /><col width="20%" /><col /><col /><col />
   
	<tr>
    	<td>  Search
    <input type="text"	name="searchtxt" id="searchtxt" value="" class="text-input"
	onkeydown="Javascript: if (event.keyCode==13) showpartbinlist();" /></td>
  
  <td><?php echo $servicecenter_search;?></td>
  


      
    <td>
        <img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="showpartbinlist();" /> 
        <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
        </td>
        <td></td> <td><?php // echo $servicecenters;?></td>
    </tr>
</table>
 </form>
</div>
<div id="partbinlist"></div>
