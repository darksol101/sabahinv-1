<script language="javascript">
$(document).ready(function(){
	cancel('<?php echo $userID;?>');
	showTable();
	getbrands()
	var userid = $("#hdnuserid").val();
	$("#frmuser").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ save();}}  
	});
})
</script>

<form onsubmit="return false" id="frmuser">
  <div style="float:left">
  	<table class="tbl" width="300px">
    <!--<tr>
      <th> <label><?php echo $this->lang->line('first_name'); ?>: </label></th>
      <td><input type="text" value="" id="first_name" name="first_name" class="validate[required] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('last_name'); ?>: </label></th>
      <td><input type="text" value="" id="last_name" name="last_name" class="validate[required] text-input"/></td>
    </tr>-->
    <tr>
      <th> <label><?php echo $this->lang->line('username'); ?>: </label></th>
      <td><input type="text" value="" id="username" name="username" class="validate[required,length[0,100]] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('userid'); ?>: </label></th>
      <td><input type="text" value="<?php echo $userID;?>" id="userid" name="userid" readonly="readonly" class="text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('email'); ?>: </label></th>
      <td><input type="text" value="" id="email" name="email" class="validate[required,custom[email]] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('mobile_number'); ?>: </label></th>
      <td><input type="text" value="" id="mobile_number" name="mobile_number" class="validate[custom[integer]] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('password'); ?>: </label></th>
      <td><input type="password" value="" id="password" name="password" class="validate[required] text-input" /></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('repassword'); ?>: </label></th>
      <td><input type="password" value="" id="rpassword" name="rpassword" class="validate[required,equals[password]] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('status'); ?>: </label></th>
      <td><?php echo $status;?></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('usergroup'); ?>: </label></th>
      <td><?php echo $usergroup;?></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('service_center'); ?>: </label>
      </th>
      <td><?php echo $scenters;?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel('<?php echo $userID;?>')" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
  </div>
  <div style="float:left; width:67%">
    <div id="accordion">
        <h3 id="brands_heading"><a href="#">Brands</a></h3>
        <div id="brand_box"></div>
        <h3 id="products_heading"><a href="#">Products</a></h3>
        <div><div id="box_check" style="display:none"><input type="checkbox" onclick="selectAllProduct();" id="chkproduct" /> All</div><div id="product_box" style="height:150px; overflow:auto;"></div></div>
    </div>
  </div>
  <div style="clear:both"></div>
  <input type="hidden" name="currentpage" id="currentpage" value="0"  />
  <input type="hidden" value="0" id="hdnuserid" name="hdnuserid" />
</form>
