<?php $this->load->view('dashboard/header', array( "title"=>$this->lang->line('brands'))); ?>
<div class="content-box-header">
</div>
<div class="content-box-content">
<form>
<table width="20%">
<tr><td>
<label><input type="text" name="nepali_calendar" value="" class="text-input" style="width:70%" /><?php $this->load->view('calendar/calendar/nepali_calendar');?></label>
</td></tr>
</table>
</form>
</div>
<?php $this->load->view('dashboard/footer'); ?>
