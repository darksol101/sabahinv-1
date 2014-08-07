<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php if($call_details->call_id>0 && $call_details->call_status >= 1){?>
<?php  $this->load->helper('url'); ?>
<?php  $uid= $this->uri->segment(3); ?>
<?php $this->load->model('parts/mdl_parts_defected'); ?>
<?php $res= $this->mdl_parts_defected->getDefectedData($uid); ?>
<!-- Listing of the data of defected parts of respective User -->
<style type="text/css">
table td img.ui-datepicker-trigger {padding-left: 5px;}
.ui-autocomplete {max-height: 200px;overflow-y: auto;overflow-x: hidden;padding-right: 20px;}
* html .ui-autocomplete {height: 200px;}
.editpart,.deletepart,infobtn {	cursor:pointer;}
.editactive {background: none repeat scroll 0 0 #FFF5E7;}
a.infobtn,a.infobtn:hover{ cursor: pointer; color:#F00}
a.close{ visibility:visible;}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet"	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js"	type="text/javascript"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js"	type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		function log( message ) {
			$( "<div/>" ).text( message ).prependTo( "#log" );
			$( "#log" ).scrollTop( 0 );
		}
		var appendTo = $( ".selector" ).autocomplete( "option" );
		$( "#part_defected_no" ).autocomplete({			 
			source: "<?php echo site_url();?>purchase/getjsonparts?pnum="+$("#part_defected_no").val(),
			minLength: 1,
			delay: 300,
			
			select: function( event, ui ) {
				$("#part_defected_no").val(ui.item.label);
				$('#part_defected_desc').val(ui.item.pdesc);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			focus: function(event, ui) {
				$("#part_defected_no").val(ui.item.label);
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
	});
</script>

<?php //if($call_details->call_id>0){?>
<?php
$timeOptions =array();
for($i=1;$i<=24;$i++){
	$timeOptions[$i]['value'] = $i;
	$timeOptions[$i]['text'] = $i;
}
$hr_list = $this->mdl_html->genericlist($timeOptions,'call_visit_hr',array('style'=>'width:50px;'),'value','text','');
$call_details->call_visit_dt = ($call_details->call_visit_dt=='0000-00-00')?'':$call_details->call_visit_dt;
?>
<form action="" method="post" name="orderForm" id="orderForm">
<fieldset><legend><?php echo $this->lang->line('part_defected'); ?></legend>
<table width="100%" id="table_input">
    <tbody>
    	<td><input type="hidden" name="part_defect_id" id="part_defect_id" value="0" class="text-input" />
    	<td><?php echo $this->lang->line('part_defected_no');?></td>
        <td><input type="text" name="part_defected_no" value="" class="text-input" id="part_defected_no"></td>
        <td><?php echo $this->lang->line('part_defected_desc');?></td>
        <td><input type="text" name="part_defected_desc" value="" class="text-input" id="part_defected_desc"></td>
        <td><?php echo $this->lang->line('part_defected_quantity');?></td>
        <td><input type="text" name="part_defected_quantity" value="" class="text-input" id="part_defected_quantity"></td>
        <td><?php echo $this->lang->line('sn.');?></td>
        <td><input type="text" name="sn." value="" class="text-input" id="part_sn"></td>
        <td><input type="button" id="add_part" name="add_part" onclick="checkpart();" value="Add" class="button" /></td>
    </tbody>
</table>
<table width="100%" cellpadding="0" cellspacing="0"	class="tblgrid">
     <col width="3%" /><col width="20%" /><col width="30%" /><col width="10%" /><col width="20%" />
      
      <thead>
      <tr>
        	<td colspan="5" style="text-align:right"><!--<input type="button" name="partial_delivery" id="partial_delivery" value="Partial Delivery" class="button" onclick="getPartialDelevery();"  />--></td>
        </tr>
        <tr>
        	<th>No.</th>
           <th>Item Number</th>
           <th>Item description</th>
          <th style="text-align:center">Quantity</th>
          <th style="text-align:center">S.No.</th>
           <?php //if($result->order_status<1){?>
         
          <?php //}?>
       
       <th> </th> </tr>
      </thead>
      <tbody id="rowdata">
      
        <?php
	$i=1;
    foreach($res as $data){ 
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
        <tr<?php echo $trstyle;?> class="add_row" id="<?php echo $i; ?>">
          <td class="sn_td"><?php echo $i;?></td>    
                  
            <td>
            <input type="hidden" id="" name="part_defect_no[]" value="<?php echo $data->part_number;?>" />
            <input type="hidden" id="" name="part_defect_id[]" value="<?php echo $data->part_defect_id;?>" class="part_defect_id" />       
            <span class="lbl"><?php echo $data->part_number;?></span></td>
            
             <td style="text-center: left;"><input type="hidden" name="pdesc[]" value="<?php echo $data->part_desc;?>" class="text-input" /><span class="lbl_desc"><?php echo $data->part_desc;?></span></td>
            
            
            <td style="text-align: center;"><input type="hidden" name="pqty[]" value="<?php echo $data->part_quantity;?>" class="text-input" />
            <span class="lbl_qty"><?php echo $data->part_quantity;?></span></td>
            
            <td style="text-align: center;"><input type="hidden" name="psn[]" value="<?php echo $data->part_serial_no;?>" class="text-input" /><span class="lbl_sn"><?php echo $data->part_serial_no;?></span></td>
             <?php //if($result->order_status<1){?>
            <!-- <td><a class="editpart"><?php //echo icon('edit','edit','png');?></a></td> -->
           <!-- <td><a class="deletedefectedpart"><?php //echo icon("delete","Delete","png");?></a></td>-->
           <td><a onclick="deletedefect('<?php echo $data->part_number; ?>', '<?php echo $i; ?>' );"><?php echo icon("delete","Delete","png");?></a></td>
			<?php //} ?>
            
        </tr>
         <?php $i++; }?>
      </tbody>
    </table>
</div>
</fieldset>
</form>
<div style="clear:both"></div>
</div>
<?php }?>