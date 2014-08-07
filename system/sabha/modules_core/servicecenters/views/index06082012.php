<?php $this->load->view('dashboard/header', array("header_insert"=>"servicecenters/script", "title"=>$this->lang->line('user_accounts'))); ?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;">Store</h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab1" id="servicecenters">Store</a></li>
    <li><a href="#tab2" id="cities">City</a></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab"><?php $this->load->view('servicecenters/tab_servicecenter');?></div>
  <div id="tab2" class="tab-content"></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
