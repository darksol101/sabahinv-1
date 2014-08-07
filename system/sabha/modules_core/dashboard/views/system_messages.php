<?php if (validation_errors()) { ?>
<?php echo validation_errors(); ?>
<?php } ?>

<?php if ($this->session->flashdata('success_save')) { ?>
<div class="notification success png_bg"><div><?php echo $this->session->flashdata('success_save'); ?></div></div>
<?php } ?>

<?php if ($this->session->flashdata('success_delete')) { ?>
<div class="notification attention png_bg"><div><?php echo $this->lang->line('this_item_has_been_deleted'); ?></div></div>
<?php } ?>

<?php if ($this->session->flashdata('custom_warning')) { ?>
<div class="notification attention png_bg"><div><?php echo $this->session->flashdata('custom_warning'); ?></div></div>
<?php } ?>

<?php if ($this->session->flashdata('custom_error')) { ?>
<div class="notification error png_bg"><div><?php echo $this->session->flashdata('custom_error'); ?></div></div>
<?php } ?>
<?php if ($this->session->flashdata('customerror')) { ?>
<div class="notification error png_bg"><div><?php echo $this->session->flashdata('customerror'); ?></div></div>
<?php } ?>
<?php if ($this->session->flashdata('login_error')) { ?>
<div class="error"><div><?php echo $this->session->flashdata('login_error'); ?></div></div>
<?php } ?>

<?php if ($this->session->flashdata('custom_success')) { ?>
<div class="notification success png_bg"><div><?php echo $this->session->flashdata('custom_success'); ?></div></div>
<?php } ?>

<?php if (isset($static_error) and $static_error) { ?>
<div class="notification error png_bg"><div><?php echo $static_error; ?></div></div>
<?php } ?>