<?php $this->load->view('dashboard/header', array("header_insert"=>"callcenter/script_calls", "title"=>$this->lang->line('calls'))); ?>
<div class="content-box-header">



<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('happy_call_for').' '.$call_uid->call_uid.'  '.'<b>'.$this->lang->line('created_date').'</b>'.'  '.$call_uid->call_dt.'  '.$call_uid->call_tm.'  '.'<b>'.$this->lang->line('closure_date').'</b>'.'  '.$call_uid->closure_dt.'  '.$call_uid->closure_tm;?> </h3>



<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="callcenter/calls"><?php echo $this->lang->line('calls')?></a></li>
	
</ul>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('happycall');?></div>

</div>
<?php $this->load->view('dashboard/footer'); ?>
