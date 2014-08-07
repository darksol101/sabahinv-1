<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo application_title().' - '.$this->lang->line('password_recovery'); ?></title>
<link href="<?php echo base_url(); ?>assets/style/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo base_url(); ?>assets/style/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo base_url(); ?>assets/style/images/favicon.png" rel="shortcut icon" type="image/x-icon" />
<script src="<?php echo base_url(); ?>assets/jquery/jquery-1.7.1.min.js" type="text/javascript"></script>

<link type="text/css" href="<?php echo base_url(); ?>assets/style/css/invalid.css" rel="stylesheet" />
</head>
<body id="login">
<?php $this->load->view('dashboard/jquery_set_focus', array('id'=>'username')); ?>
<div id="login-wrapper" class="png_bg">
  <div id="login-top">
    <h1><?php echo application_title() . '-' . $this->lang->line('password_recovery'); ?></h1>
    <img id="logo" src="<?php echo base_url(); ?>assets/style/images/cglogo.png" alt="<?php echo application_title();?>"> 
    <p class="login-text">Customer Service Management - Password Recovery</p>
    <p style="width:300px; text-align:center; color:#999; margin:0 auto;"><?php echo $this->lang->line('recover_text');?></p>
    </div>
  <span class="message" style="height:auto;float:none;"><div style="width:40%; margin:0 auto; text-align:center;" class="message_text"><?php $this->load->view('dashboard/system_messages'); ?></div></span>
  <div id="login-box">
  <div id="login-content">
  <style>
  .error{
	  padding-left:0px!important;
	  font-weight:normal;
  }
  </style>
            <form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">
                <div class="formrow"><label><?php echo $this->lang->line('username'); ?>: </label><input type="text" value="" id="username" name="username" class="text-input" /></div>
                <div style="clear: both;">&nbsp;</div>
                <input type="submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" class="button" id="btn_submit" />
            </form>
    <div style="clear: both;">&nbsp;</div>
  </div>
  </div>
  <div class="main_footer">
    <img width="110px" alt="" src="<?php echo base_url();?>assets/style/images/footer1.png" id="logo">
    <img width="110px" alt="" src="<?php echo base_url();?>assets/style/images/footer2.png" id="logo">
    <img width="110px" alt="" src="<?php echo base_url();?>assets/style/images/footer3.png" id="logo">
    <img width="155px" alt="" src="<?php echo base_url();?>assets/style/images/footer4.png" id="logo">
    <div style="clear: both;">&nbsp;</div>
</div>
</div>
</body>
</html>
