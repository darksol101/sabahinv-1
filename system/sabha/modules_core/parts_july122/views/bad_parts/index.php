<?php $this->load->view('dashboard/header', array("header_insert"=>"parts/bad_parts/script", "title"=>$this->lang->line('bad_parts')))?>
<div class="content-box-header">
<h3 style="cursor: s-resize;">Bad Parts</h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="users"><?php echo $this->lang->line('badparts')?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('parts/bad_parts/tab_badparts');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
