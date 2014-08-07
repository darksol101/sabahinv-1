<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div class="toolbar1" style="width:450px;">
  <form onsubmit="return false">Search
  <input type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) searchmodel();" />
  <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" style="margin-bottom: -8px;" class="searchbtn"  onclick="searchmodel();" /> <span class="message"><span class="message_text"></span></span></form></div>
<div id="lismodels">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="1%" /><col />
	<thead><tr><th width="1%" align="center">SN</th><th style="text-align:left; padding:2px;">Model Number</th></tr></thead>
	<tbody>
    <?php 
    $i=1;
    $trclass=$i%2==0?" class='even' ": " class='odd' ";
    foreach($list as  $row){?>
            <tr<?php echo $trclass;?>>
                <td><?php echo $i;?></td>
                <td style="padding:2px;text-align:center;text-align:left" align="left"><span  style="cursor:pointer;"onClick="setModelNumber('<?php echo $row->model_id;?>','<?php echo $row->model_number;?>','<?php echo $row->prod_category_name;?>');"><?php echo $row->model_number;?></span></td>
            </tr>
    <?php $i++; }?>
	</tbody>
</table>
</div>
