<?php if ($type=='success') { ?>
<div class="notification success png_bg"><div><?php echo $message; ?></div></div>
<?php } ?>

<?php if ($type=='delete') { ?>
<div class="notification attention png_bg"><div><?php echo $message;?></div></div>
<?php } ?>

<?php if ($type=='warning') { ?>
<div class="notification attention png_bg"><div><?php echo $message; ?></div></div>
<?php } ?>

<?php if ($type=='failure') { ?>
<div class="notification error png_bg"><div><?php echo $message; ?></div></div>
<?php } ?>

<?php if ($type=='information') { ?>
<div class="notification information png_bg"><div><?php echo $message; ?></div></div>
<?php } ?>