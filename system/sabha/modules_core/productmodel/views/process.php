<?php
switch (trim($ajaxaction)) {
    case 'getproductmodellist':
        displayModelList($productmodels, $navigation, $page);
        break;
}
function displayModelList($productmodels, $navigation, $page) {
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showProductmodelList();
	})
});
</script>
	<table style="width:100%" class="tblgrid" cellpadding="0" cellspacing="0">
    <col width="1%" /><col /><col width="15%" /><col width="15%" /><col width="15%" /><col width="1%" /><col width="5%" /><col width="5%" />
    	<thead>
        	<tr><th>S.No.</th class="header"><th>Model Number</th><th>Brand</th><th class="">Product</th><th class="">Details</th><!-- <th>Manuals</th> --><th></th><th class="">&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
    $i = 1;
    foreach ($productmodels as $productmodel) {
        $trstyle = $i % 2 == 0 ? " class='even' " : " class='odd' ";
?>
    		<tr <?php
        echo $trstyle; ?>><td><?php
        echo $i + $page['start']; ?></td><td><?php
        echo $productmodel->model_number; ?></td><td><?php
        echo $productmodel->brand_name; ?></td><td><?php
        echo $productmodel->product_name; ?></td><td align="center"><?php
        echo $productmodel->model_desc; ?></td>
       <!--  <td style="text-align:center;"><a title="Click to upload  manuals" class="btn" onclick="showuploadform('<?php echo $productmodel->model_id; ?>')">
       		<?php
       echo icon('upload', 'upload', 'png'); ?></a></td> --><td><a onclick="editModel('<?php
        echo $productmodel->model_id ?>')" class="btn"><?php
        echo icon('edit', 'Click to edit', 'png'); ?></a></td><td><a onclick="deleteModel('<?php
        echo $productmodel->model_id ?>')" class="btn"><?php
        echo icon('delete', 'Delete', 'png'); ?></a></td></tr>
    <?php
        $i++;
    }
?>
		</tbody>
        <tfoot><tr><td colspan="6" style="vertical-align:middle;"><div class="pagination"><?php
    echo $navigation; ?></div></td></tr></tfoot>
    </table>        
<?php
} ?>
<?php
