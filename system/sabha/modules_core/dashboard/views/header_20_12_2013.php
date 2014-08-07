<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache">  
<meta http-equiv="Cache-Control" content="no-cache">  
<meta http-equiv="Expires" content="">  
<title>
<?php
if(isset($title)){ $page_title = ' - '.$title; }else{ $page_title = '';}
echo application_title().$page_title; ?>
</title>
<link href="<?php echo base_url(); ?>assets/style/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo base_url(); ?>assets/style/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link type="text/css" href="<?php echo base_url(); ?>assets/style/css/invalid.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/style/images/favicon.png" rel="shortcut icon" type="image/x-icon" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/validationEngine.jquery.css" type="text/css" media="all" />
<script src="<?php echo base_url(); ?>assets/jquery/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/facebox.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/admin.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/ccms.javascript.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.validationEngine.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.validationEngine-en.js"></script>
<?php if (isset($header_insert)) { if (!is_array($header_insert)) { $this->load->view($header_insert); } else { foreach ($header_insert as $insert) { $this->load->view($insert); } } } ?>
</head>
<body>
<div id="body-wrapper">
<div class="header"> <img id="logo" src="<?php echo base_url(); ?>assets/style/images/cglogo.png" alt=""> 
<div class="headerright"><?php echo showLoginDetails();?></div>
</div>
<div style="clear:both;"></div>
<!-- header div ends here -->
<div class="contenrwrapper" style="background:#fff;">
<div id="sidebar">
  <div id="sidebar-wrapper">
    <!-- Sidebar with logo and menu -->
    <!--<h1 id="sidebar-title"><a href="#">Customer Service Management </a></h1>-->
    <!-- Logo (221px wide) -->
    <?php /*?><a href="<?php echo base_url(); ?>"><img id="logo" src="<?php echo base_url(); ?>assets/style/images/logo.png" alt="" width="200px"></a><?php */?>
    <?php $this->load->view('dashboard/menu');?>
    <!-- End #main-nav -->
  </div>
</div>
<div id="main-content">
<div>
  <?php //$this->load->view('dashboard/system_messages');?>
</div>
<div id="ajaxErrorMsg"></div>
<div class="content-box">
