<?php $this->load->view('dashboard/header', array("title"=>$this->lang->line('dsr_reports')));?>
<script type="text/javascript" src="<?php echo base_url();?>assets/jquery/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple",
		theme_advanced_toolbar_location : "top"
	});
</script>

<form method="post" action="">
      <table width="70%" cellpadding="0" cellspacing="0">
        <col width="20%" /><col />
        <tr>
          <td><label>Call List Email template</label></td>
          <td><textarea cols="2" rows="10" id="call_string" name="call_string"><?php echo $call_string;?></textarea></td>
        </tr>
        <tr>
          <td><label>Daily Service Email template</label></td>
          <td><textarea cols="2" rows="10" id="dsr_string" name="dsr_string"><?php echo $dsr_string;?></textarea></td>
        </tr>
        <tr>
          <td><label>Closed Call Email template</label></td>
          <td><textarea cols="2" rows="10" id="closecall_string" name="closecall_string"><?php echo $closecall_string;?></textarea></td>
        </tr>
        <tr>
          <td style="text-align:right"><input type="submit" name="save" id="save" value="Save" class="button" /></td>
        </tr>
        </tr>
      </table>
    </form>
<?php $this->load->view('dashboard/footer'); ?>
