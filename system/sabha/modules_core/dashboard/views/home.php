<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php
if(isset($title)){ $page_title = ' - '.$title; }else{ $page_title = '';}
echo application_title().$page_title; ?>
</title>
<link href="<?php echo base_url(); ?>assets/style/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo base_url(); ?>assets/style/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link type="text/css" href="<?php echo base_url(); ?>assets/style/css/invalid.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/style/images/favicon.png" rel="shortcut icon" type="image/x-icon" />
<script src="<?php echo base_url(); ?>assets/jquery/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/facebox.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/admin.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/ccms.javascript.js" type="text/javascript"></script>
<?php if (isset($header_insert)) { if (!is_array($header_insert)) { $this->load->view($header_insert); } else { foreach ($header_insert as $insert) { $this->load->view($insert); } } } ?>
</head>
<body>
<div id="body-wrapper">
<div class="header"> <img id="logo" src="<?php echo base_url(); ?>assets/style/images/cglogo.png" alt="" width="203px"> 
</div>
<!-- header div ends here -->
<div id="sidebar">
  <div id="sidebar-wrapper">
    <!-- Sidebar with logo and menu -->
    <h1 id="sidebar-title"><a href="#">Customer Service Management </a></h1>
    <!-- Logo (221px wide) -->
    <a href="<?php echo base_url(); ?>"><img id="logo" src="<?php echo base_url(); ?>assets/style/images/logo.png" alt="" width="200px"></a>
    <?php if($this->session->userdata('user_id')){$this->load->view('dashboard/menu');}?>
    <!-- End #main-nav -->
  </div>
</div>
<div id="main-content">
<div class="content-box">
