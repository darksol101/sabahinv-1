<style type="text/css">
.tool-icon a{padding:5px;}
#main-content .tblbox table.tbl tfoot tr td{ border-top: 1px solid #00689C;}
</style>

<form onsubmit="return false" name="fname" id="fname" method="post">
	<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
		<col /><col /><col /><col /><col /><col />
        <thead>
            <tr>
                <th colspan="6"><?php echo sprintf($this->lang->line('reminder_report'));?></th>
                <th style="text-align:right">
                    <div class="tool-icon">
                        <a class="btn" onclick="export_exl();" title="Download as excel"><?php echo icon('excel-download','Download as excel','png');?></a><a class="btn" onclick="email_pop();" title="E-mail"><?php echo icon('mail','E-mail','png');?></a>
                    </div>
                </th>
            </tr>
        </thead>
	</table>
  <div class="tblbox">
    <table width="100%" cellpadding="0" cellspacing="0" class="tbl tblgrid" id="tbldata">
      <col width="1%" />
      <col />
      <col />
      <col />
      <thead>
        <tr>
          <th style="text-align:left"><?php echo $this->lang->line('S.No.');?></th>
          <th style="text-align:center"><?php echo $this->lang->line('call_id');?></th>
          <th style="text-align:center"><?php echo $this->lang->line('servicecenter');?></th>
          <th style="text-align:center"><?php echo $this->lang->line('reminder_remark');?></th>
          <th style="text-align:center"><?php echo $this->lang->line('reminder_dt');?></th>
        </tr>
      </thead>
      <tbody id="tbldata">
        <?php $i=1; foreach($reports as $report){ $trstyle=$i%2==0?" class='even' ": " class='odd' ";?>
       	  <tr<?php echo $trstyle;?>>
          <td style="text-align:left"><?php   echo $i++;?></td>
          <td style="text-align:center"><?php echo $report->call_uid;?></td>
          <td style="text-align:center"><?php echo $report->sc_name;?></td>
          <td style="text-align:center"><?php echo $report->reminder_remarks;?></td>
          <td style="text-align:center"><?php echo $report->reminder_dt;?></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </div>
<input type="hidden" name="content" id="content" value="" />
 <input type="hidden" name="json" id="json" value="" />
  <input type="hidden" name="report_dt" id="report_dt" value="<?php echo format_date(strtotime(date('Y-m-d')));?>" />
</form>