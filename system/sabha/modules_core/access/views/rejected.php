
<?php $this->load->view('dashboard/header', array("header_insert"=>"access/script", "title"=>$this->lang->line('access_management'))); ?>

<div style= "margin: 0 auto; width:40%; height:150px; text-align:center;margin-top:150px;color:red;font-size:16px"> <?php echo $this->lang->line('no_access');?> </div>

<?php $this->load->view('dashboard/footer'); ?>
