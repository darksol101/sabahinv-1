<?php $this->load->view('dashboard/header', array("header_insert"=>"productmodel/script", "title"=>$this->lang->line('productmodels'))); ?>

<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('models')?></h3>
  <ul class="content-box-tabs">
    <li><a href="#tab1" id="brands"><?php echo $this->lang->line('brands')?></a></li>
    <li><a href="#tab2" id="products"><?php echo $this->lang->line('products')?></a></li>
    <li><a class="default-tab" href="#tab3" id="productmodel"><?php echo $this->lang->line('models')?></a></li>
    <li><a href="#tab4" id="category"><?php echo $this->lang->line('category')?></a></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
    <div id="tab1" class="tab-content"></div>
    <div id="tab2" class="tab-content" style="display:none;"></div>
    <div id="tab3" class="tab-content default-tab" style="display:none;"><?php $this->load->view('productmodel/tab_productmodel');?></div>
    <div id="tab4" class="tab-content" style="display:none;"></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
