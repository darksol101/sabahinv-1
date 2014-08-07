<style type="text/css">
#facebox{width: 10.6cm;}
#facebox .body{
	width: 10.6cm;
}
#barcode_list_all div img{
	padding-top:2px;
}
</style>
			<div id="barcode_<?php echo $this->data['part_number']?>" style="width:2in;">

						<div class="barcode_list" id="barcode_list_all" style="width:2in;">
							<img src="<?php echo site_url('parts/barcodes?text='.$this->data['part_number']);?>" class="bar_img" style="width: 1.99in;height: 0.9in;">
						</div>
						
						<br>
						<div class="takein">
							<input type="text" name="noof" id="print_no_of" value="1" onkeyup="appendImages(this.value,'<?php echo $this->data['part_number']?>')"/>
						<button onclick="print_no_barcodes('barcode_list_all');">Print</button>
						</div>
			</div>


