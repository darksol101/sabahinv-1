<?php $this->load->view('dashboard/header', array("header_insert"=>"events/script_events", "title"=>$this->lang->line('user_accounts'))); ?>
<div class="content-box-content">
     <div id="tab2" class="tab-content  default-tab"><?php $this->load->view('events/tab_events');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>