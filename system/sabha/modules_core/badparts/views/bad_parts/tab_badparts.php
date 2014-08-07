<script type="text/javascript">
$(document).ready(function(){
	showBadPartsList();
});
</script>
<?php $this->load->view('bad_parts/revoke')?>

<div class="toolbar1">
  <form onsubmit="return false">
  <table>
  <col width="3%" />
   <col width="1%" />
    <col width="12%" />
     <col width="11%" />
      <col width="27%" />
  <tbody>
  	<tr> 
  		<td> Search</td>
        <td> <input type="text" name="searchtxt"	id="searchtxt" class="text-input" value=""	onKeydown="Javascript: if (event.keyCode==13){ $('#currentpage').val(0);showBadPartsList();}" /></td>
        <td> <?php echo $servicecenter_select_search;?></td>
        <td><span id="engineer_box_search"><?php echo $engineer_select_search;?> </span></td>
        <td> 
    <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showBadPartsList();" /> <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
  
</div> </td>
  	</tr>
    <tbody>
  </table>
    
  </form>
<div id="badpartlist" style="display: none;"></div>
<input type="hidden" name="currentpage" id="currentpage" value="0" />