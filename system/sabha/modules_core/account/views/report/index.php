<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); 
?>

<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('reports');?></h3>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab">
    <div class="toolbar1">
      <div id="report-dashboard">
        <div class="settings-container balancesheet"> <a href="<?php echo site_url('account/report/balancesheet');?>">
          <div class="settings-desc"> &nbsp; </div>
          <div class="settings-title"> <?php echo $this->lang->line('balance_sheet'); ?> </div>
          </a> </div>
        <div class="settings-container profitandloss"> <a href="<?php echo site_url('account/report/balancesheet');?>">
          <div class="settings-desc"> &nbsp; </div>
          <div class="settings-title"> <?php echo $this->lang->line('profit_and_loss_account'); ?> </div>
          </a> </div>
        <div class="settings-container trialbalance"> <a href="<?php echo site_url('account/report/balancesheet');?>">
          <div class="settings-desc"> &nbsp; </div>
          <div class="settings-title"> <?php echo $this->lang->line('trial_balance'); ?> </div>
          </a> </div>
        <div class="settings-container ledgerst"> <a href="<?php echo site_url('account/report/balancesheet');?>">
          <div class="settings-desc"> &nbsp; </div>
          <div class="settings-title"> <?php echo $this->lang->line('ledger_statement'); ?> </div>
          </a> </div>
        <div class="settings-container reconciliation"> <a href="<?php echo site_url('account/report/reconciliation/pending');?>">
          <div class="settings-desc"> &nbsp; </div>
          <div class="settings-title"> <?php echo $this->lang->line('reconciliation'); ?> </div>
          </a> </div>  
      </div>
    </div>
    <?php echo modules::run('account/home');?>
    <div class="clear"> </div>
  </div>
</div>
<?php
$this->load->view('dashboard/footer');