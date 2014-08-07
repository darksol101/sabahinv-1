<style type="text/css">
#facebox{width: 10.6cm;}
#facebox .body{
	width: 10.6cm;
}
#barcode_list_all div img{
	/*padding-top:2px;*/
}
</style>

<?php if($this->data['printer']=='bar'): ?>
		<div id="barcode_<?php echo $this->data['part_number']?>" style="width:10.4cm;">

						
						<div class="barcode_list" id="barcode_list_all" style="width:10.4cm;">
							<img src="<?php echo site_url('parts/barcodes?text='.$this->data['part_number']);?>" class="bar_img" style="width: 4.5cm;height: 2cm;">
						</div>
						
						<br>
						<div class="takein">
							<input type="text" name="noof" id="print_no_of" value="1" onkeyup="appendImages(this.value,'<?php echo $this->data['part_number']?>')"/>
						<button onclick="print_no_barcodes('barcode_list_all');">Print</button>
						</div>
			</div>

<?php elseif ($this->data['printer']=='a4'):?>

			<div id="barcode_<?php echo $this->data['part_number']?>" style="width:21.1cm;">
						
						<div class="barcode_list" id="barcode_list_all" style="width:21.1cm;margin-top:1.1cm;">
							<div style="float:left;">
								<img src="<?php echo site_url('parts/barcodes?text='.$this->data['part_number']);?>" class="bar_img" style="width: 9.8cm;height: 3.4cm;">
							</div>
						</div>
						
						<br>
						<div class="takein">
							<input type="text" name="noof" id="print_no_of" value="1" onkeyup="appendImages_a4(this.value,'<?php echo $this->data['part_number']?>')"/>
						<button onclick="print_no_barcodes('barcode_list_all');">Print</button>
						<p><i>Please while priting the codes make sure right printer is set to default. Thank you.</i></p>
						</div>
			</div>


<?php endif; ?>	