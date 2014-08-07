<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showstockList();
	})
});
</script>


<div style="float: right; margin-top: -28px;"><?php
$page = $this->input->post('currentpage');
$start = 0;
if($config['total_rows']>0){
	$start = $page+1;
	if($config['total_rows']>($page+$config['per_page'])){
		$end = $page+$config['per_page'];
	}else{
		$end = $config['total_rows'];
	}

	?> <span><strong><?php echo $start;?> - <?php echo $end?></strong></span>
of <span><strong><?php echo $config['total_rows'];?></strong></span> <?php }?>
<input type="button" name="button" class="button" value="Download"
	onclick="excelDownload();" /> <?php /*?><input type="button" name="button"
	class="button" value="Email" onclick="email_pop();" /><?php */?></div>
<?php $this->load->model(array('partallocation/mdl_partallocation'));?>

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="1%" />
	<col width="20%"/>
	<col width="20%"/>
	<col width="10%"/>
		<?php if($this->session->userdata('orderlevel')):?>
			   <col width="10%"/>
			   <col width="15%"/>
		<?php endif;?>
	<col width="15%"/>
	<col width="15%"/>
	<col width="10%" />
	<col width="5%" />
	<col width="20%" />
	<thead>
    	<tr>
        	<th><?php echo $this->lang->line('sn');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('sc_name');?></th>
             <th><?php echo $this->lang->line('part_number');?></th>
              <th><?php echo $this->lang->line('description');?></th>


            <!--  <th><?php //echo $this->lang->line('company_name');?> </th> -->

             	<?php if($this->session->userdata('orderlevel')):?>
			   	<th><?php echo $this->lang->line('threshold');?> </th>
			   	<th><?php echo $this->lang->line('threshold_max');?> </th>
				<?php endif;?>


             <th style="text-align:center"><?php echo $this->lang->line('available_quantity');?></th>
            
              <th><?php  echo $this->lang->line('in_transit');?></th>
             <!--  <th style="text-align : center"> <?php //echo $this->lang->line('allocated_quantity');?></th> -->
             <th style="text-align:center"><?php echo $this->lang->line('total_quantity');?></th>
             
            <th> </th>
        </tr>
    </thead>
    <tbody>
	<?php
	//print_r($stocklist);
	$i=0;
	foreach($stocklist as $stock){
		if($stock->parts_in_transit == '0'){
			$stock->parts_in_transit = "";
		}
		$total_allocated_quantity = $this->mdl_partallocation->allocatedquantity($stock->sc_id,$stock->part_number,$stock->company_id);
		
		
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
			<tr<?php echo $trstyle;?>>
            	<td><?php echo $start+$i;?></td>
                
                <td style="text-align:center"><?php echo $stock->sc_name;?></td>
                <td><a href="<?php echo site_url();?>stocks/stockdetails/<?php echo $stock->sc_id?>/<?php echo $stock->part_id?>/<?php echo $stock->company_id?>/<?php echo $stock->part_number?>"><?php echo $stock->part_number?></a></td>
                <td><?php echo $stock->part_desc;?></td>
             <!--    <td><?php //echo $stock->company_title;?></td> -->

                <?php if($this->session->userdata('orderlevel')):?>
			   <td><?php echo $stock->order_level;?> </td>
			   <td><?php echo $stock->order_level_max;?> </td>
				<?php endif;?>

                 <td style="text-align:center"><?php echo $qty = (($stock->stock_quantity - $stock->parts_in_transit) < 0 ) ? 0 : ($stock->stock_quantity - $stock->parts_in_transit);?></td>
               	 <td ><?php echo $stock->parts_in_transit;?></td>
                   <!-- <td style="text-align:center"><?php //echo $total_allocated_quantity;?> </td> -->
                  <td style="text-align:center"><?php echo $stock->stock_quantity + $total_allocated_quantity;?></td>
                
                <td></td>
            </tr>
	<?php $i++; }?>
    </tbody>
     <tfoot>
		<tr>
			<td colspan="8">
			<div class="pagination"><?php echo $navigation;?></div>
            <input type="hidden" id="currentpage" name="currentpage" value="0" />
			</td>
		</tr>
	</tfoot>
</table>