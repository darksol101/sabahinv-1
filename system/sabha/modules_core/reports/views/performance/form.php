<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="toolbar1">
  <form onsubmit="return false" id="frmperformancereport" name="frmperformancereport">
    <table width="60%">
      <col width="20%" /><col width="30%" /><col width="20%" /><col width="30%" />
      <tr>
        <th><label><?php echo $this->lang->line('from'); ?>: </label></th>
        <td><input type="text" readonly="readonly" name="from_date" id="from_date" class="text-input datepicker" value="<?php echo format_date((strtotime(date("Y-m-d"))-strtotime(date("m"))-30*24*60*60));?>" style="width:70%;" /></td>
        <th><label><?php echo $this->lang->line('to'); ?>: </label></th>
        <td><input type="text" readonly="readonly" name="to_date" id="to_date" class="text-input datepicker" value="<?php echo format_date(strtotime(date("Y-m-d")));?>" style="width:70%;" /></td>
      </tr>
      <tr>
        <td colspan="5"><input class="button" type="submit" value="<?php echo $this->lang->line('generate_report'); ?>" name="btn_submit" id="btn_submit" onClick="getcallcenter_reportlist();" /></td>
      </tr>
    </table>
  </form>
</div>
