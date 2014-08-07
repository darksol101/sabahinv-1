<div style="width: 650px;">
<div class="toolbar1">
<form onsubmit="return false">
<table>
<tr>
<td>Search <input type="text" name="searchtxt"
	id="searchtxt" class="text-input" value=""
	onKeydown="Javascript: if (event.keyCode==13) { $('#currentpage').val(0);showmodellist();}" />
    
<img src="<?php echo base_url();?>assets/style/img/icons/search.gif"
	style="margin-bottom: -8px;" class="searchbtn"
	onclick="javascript:$('#currentpage').val(0);showmodellist();" /> <span
	class="message"><span class="message_text"></span></span>
   
   </td><td style="vertical-align:top;">
   <?php  echo $brandOptions;?></td>
   <td style="vertical-align:top;">
   <span id= 'product_popsearch'> <?php echo $productOptions;?></span></td>
	</tr>
   </table>
    
    
    </form>
</div>
<div id="modelcalllist"></div>
</div>
