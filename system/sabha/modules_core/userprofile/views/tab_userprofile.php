<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style>
.validationerror{color: #FB1515;}
</style>
<?php 
if($this->session->flashdata('password_save')){
	echo '<div class="notification success png_bg"><div>'.$this->session->flashdata('password_save').'</div></div>';
}
if($this->session->flashdata('error_save')){
	echo '<div class="notification error png_bg"><div>'.$this->session->flashdata('error_save').'</div></div>';
}
?>
<form method="post" name="userForm" id="userForm" action="<?php echo site_url()?>userprofile/changepassword">
	<table width="70%">
    	<col width="20%" /><col width="40%" /><col width="40%" />
    	<tr>
        	<th><label><?php echo $this->lang->line('oldpassword');?> :</label></th>
        	<td><input type="password" name="oldpassword" id="oldpassword" value="<?php echo set_value('oldpassword'); ?>" class="text-input" /></td>
            <td><?php echo form_error('oldpassword'); ?></td>
        </tr>
        <tr>
        	<th><label><?php echo $this->lang->line('newpassword');?> :</label></th>
        	<td><input type="password" name="newpassword" id="newpassword" value="<?php echo set_value('newpassword'); ?>" class="text-input" /></td>
            <td><?php echo form_error('newpassword'); ?></td>
        </tr>
        <tr>
        	<th><label><?php echo $this->lang->line('repassword');?> :</label></th>
        	<td><input type="password" name="repassword" id="repassword" value="<?php echo set_value('repassword'); ?>" class="text-input" /></td>
            <td><?php echo form_error('repassword'); ?></td>
        </tr>
        <tr>
        	<td colspan="3"><input type="submit" name="changepassword" id="changepassword" value="Change Password" class="button" /> <input type="button" name="cancel" id="cancel" value="Cancel" class="button" onclick="clearForm();"  /></td>
        </tr>
    </table>
</form>