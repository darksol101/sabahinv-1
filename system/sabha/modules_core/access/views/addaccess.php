
<?php $this->load->model(array('mdl_menus'));?>
<script language="javascript">
$(document).ready(function(){
	cancel();
	showGroupTable();
	var userid = $("#hdngroupid").val();
	$("#frmaccess").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ save();}}  
	});
})
</script>

<form onsubmit="return false" id="frmaccess">
<div class="toolbar1">
<table class="tbl" width="300px">
	<tr>
		<th><label><?php echo $this->lang->line('usergroup'); ?>: </label></th>
		<td><?php echo $usergroup;?></td>
	</tr>
</table>
</div>
<div class="toolbar">
<table class="tbl" width="70%">
	
 <?php
 
 $i = 0;
 $id_list = '';
 
 foreach ($menus as $menu){	
 	$submenu = $this->mdl_menus->getSubmenus($menu->id); 	
 $id_list = $id_list.$menu->id.',';?> 
        <th><input <?php echo ($menu->parent_id!=0?'class="child_'.$menu->parent_id.'"':'class="top" onchange="checkallChild(this.value);"')?> type="checkbox" value="<?php echo $menu->id;?>" id="id_<?php echo $menu->id; ?>" name="id_<?php echo $menu->id; ?>" /></th>
		<td><label <?php  echo ($menu->parent_id!=0?'class="child '.$menu->parent_id.'"':'class="top"');?>><?php echo $menu->title;?></label></td>
		<?php echo '</tr>
    <tr>';?>
   
 		<?php if ( count($submenu)> 1) { 
		
 		foreach ($submenu as $menuchild){ 
 		$id_list = $id_list.$menuchild->id.',';?>
 	    <th><input <?php echo ($menuchild->parent_id!=0?'class="child_'.$menuchild->parent_id.'"':'class="top" onchange="checkallChild(this.value);"')?> type="checkbox" value="<?php echo $menuchild->id;?>" id="id_<?php echo $menuchild->id; ?>" name="id_<?php echo $menuchild->id; ?>" /></th>
		<td><label <?php  echo ($menuchild->parent_id!=0?'class="child '.$menuchild->parent_id.'"':'class="top"');?>><?php echo $menuchild->title;?></label></td>
 		<?php 	if($i==4){$i=0; echo '</tr>
    <tr>';}
  
 		$i++;
 		}  echo '</tr>
    <tr>'; ?>
 
 		<?php }
  }?>
	</tr>
  </table>
  </div>
 <table class="tbl" width="100%">
	<tr>
		<td>&nbsp;</td>
		<td><input class="button" type="submit"
			value="<?php echo $this->lang->line('save'); ?>" name="btn_submit"
			id="btn_submit" /> 
			<input class="button" type="button"
			value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel"
			id="btn_cancel" onclick="cancel()" /> <input
			type="button" value="<?php echo $this->lang->line('close'); ?>"
			name="btn_submit" id="btn_close" class="button"
			onclick="closeform();" /></td>
	</tr>
</table>

<div style="clear: both"></div>
<input type="hidden" name="currentpage" id="currentpage" value="0" />
 <input	type="hidden" value="<?php echo $id_list;?>" id="total_data" name="total_data" />
 <input	type="hidden" value="0" id="hdngroupid" name="hdngroupid" />
 <input type="hidden" name="currentpage" id="currentpage" value="0" /> 
 </form>
