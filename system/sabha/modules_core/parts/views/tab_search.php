
<?php $this->load->view('dashboard/header', array("header_insert"=>"parts/script", "title"=>$this->lang->line('parts'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('parts')?></h3>
<ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab2" id="parts/search"><?php echo $this->lang->line('model_association')?></a></li>
</ul>
</div>
<div class="content-box-content">

<div id="tab2" class="tab-content default-tab"><?php $this->load->view('parts/search_modelpartreport');?></div>

</div>
<?php $this->load->view('dashboard/footer'); ?>
