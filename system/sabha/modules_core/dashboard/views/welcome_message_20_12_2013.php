<?php /*?><div class="topwelcome">
<div id="greetingheader"><span>Welcome <?php echo $session_details['username'];?></span>,</div>
<div id="header_scname"><?php echo $session_details['sc_name'];?>,</div>
<div id="header_user_address"><span><?php echo $session_details['zone_name'];?></span>, <span><?php echo $session_details['district_name'];?></span>, <span><?php echo $session_details['city_name'];?></span></div>
</div><?php */?>


<div class="topwelcome">
<span class="topwelcomeleft">
<div id="greetingheader"><span>Welcome <?php echo $session_details['username'];?></span>,</div>
<div id="header_scname"><?php echo $session_details['sc_name'];?></div>
<?php /*?><div id="header_user_address"><span><?php echo $session_details['zone_name'];?></span>, <span><?php echo $session_details['district_name'];?></span>, <span><?php echo $session_details['city_name'];?></span></div><?php */?>
</span>
</div>
<div class="topnav">
<ul>
<li class="menuid12"><a class="no-submenu" href="<?php echo site_url();?>userprofile/changepassword">Change Password</a></li>
<li class="menuid5"><a class="no-submenu" href="<?php echo site_url();?>sessions/logout">Log Out</a></li>
</ul>
</div>