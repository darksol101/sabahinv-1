<?php $this->load->view('dashboard/header', array("header_insert"=>"bill/script", "title"=>$this->lang->line('bill'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('bill')?></h3>
<ul class="content-box-tabs">
    <li><a class="" href="#tab1" id="bills"><?php echo $this->lang->line('bills')?></a></li>
	<li><a class="default-tab" href="#tab2" id="bills/view/<?php echo $bill->bill_id;?>"><?php echo $this->lang->line('bill').' &ndash; '.$bill->sc_code.($bill->bill_type==1?'SI':'TI').'/'.$bill->bill_number;;?></a></li>	
</ul>
</div>
<div class="content-box-content">

<div id="tab1" class="tab-content default-tab"><?php $this->load->view('bill/tab_bill');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
