<style>
#facebox .body{
	width: 800px;
}
</style>			

			<?php $barcodelink="uploads/barcodes/".$this->data['part_id'].".png"; ?>

			<div id="barcode_<?php echo $this->data['part_id']?>" >

						<div style="float:left;" class="bar_img">
							<img src="<?php echo site_url().$barcodelink;?>" class="bar_img">
							<p>MRP: <?php echo $this->data['part_price'];?></p>
							</div>
							<div class="barcode_list" id="barcode_list_all">
							</div>
						<br>

						<div class="takein">
						<input type="text" name="noof" id="print_no_of" value="1" onkeyup="appendImages(this.value,'<?php echo $barcodelink;?>','<?php echo $this->data['part_price'];?>')"/>
						<button onclick="print_no_barcodes('barcode_list_all');">Print</button>
						</div>
			</div>