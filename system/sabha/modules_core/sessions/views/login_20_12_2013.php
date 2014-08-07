<?php
if($this->input->is_ajax_request()==1)
{
	//die('test');
	$user_id = $this->session->userdata('user_id');
	if((int)$user_id==0){
		$this->load->view('sessions/check_login_session');
		return '';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo application_title().' - '.$this->lang->line('log_in'); ?></title>
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
    <h1><?php echo application_title() . '-' . $this->lang->line('log_in'); ?></h1>
    <img id="logo" src="<?php echo base_url(); ?>assets/style/images/cglogo.png" alt="<?php echo application_title();?>"> 
    <p class="login-text">Customer Service Management</p>
    </div>
  <?php $this->load->view('dashboard/system_messages'); ?>
  <div id="login-box">
  <div id="login-content">
    <form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">
      <div class="formrow">
        <label><?php echo $this->lang->line('username'); ?>: </label>
        <input type="text" class="text-input" value="" id="username" name="username" />
      </div>
      <div class="formrow">
        <label><?php echo $this->lang->line('password'); ?>: </label>
        <input type="password" value="" id="password" name="password" class="text-input" />
      </div>
      <div class="clear">&nbsp;</div>
      <input type="submit" value="<?php echo $this->lang->line('log_in'); ?>" name="btn_submit" id="btn_submit" class="button" />
    </form>
    <div style="clear: both;">&nbsp;</div>
  </div>
  </div>
  <div class="main_footer">
    <img width="110px" alt="" src="<?php echo base_url();?>assets/style/images/footer1.png" id="logo">
   
    <div style="clear: both;">&nbsp;</div>
</div>
</div>
</body>
</html>
