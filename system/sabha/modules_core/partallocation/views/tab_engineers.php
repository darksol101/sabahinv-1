<?php $this->load->view('dashboard/header', array("header_insert"=>"partallocation/script", "title"=>$this->lang->line('partallocation'))); ?>
<?php $engineer = $this->mdl_engineers->getEngineerNameById($this->uri->segment('3'));?>
<?php $company = $this->mdl_company->getCompanyNameById($this->uri->segment('5'));?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('partallocation')?> -(<?php echo  $engineer;?>)-(<?php echo $this->uri->segment('4');?>)-(<?php echo $company ;?>)</h3>
<ul class="content-box-tabs">
	
    <li><a class="default-tab" href="#tab2" id="partallocation"><?php echo $this->lang->line('allocate')?></a></li>
</ul>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('partallocation/revoke');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
