<style type="text/css">
table td img.ui-datepicker-trigger { padding-left:5px;}
form label{ padding:5px 0!important;}
#product_box,#servicemanualslist{position:relative;}
#product_box .loading,#servicemanualslist .loading{position:absolute; left:0px; top:0; width:100%; height:30px; margin:0 auto; text-align:center;}
</style>
<div class="toolbar1">
<form onsubmit="return false" id="frmcity" name="frmcity">
    <table width="100%" cellpadding="0" cellspacing="0">
    	<col width="10%" /><col width="10%" /><col width="10%" /><col width="30%" /><col width="10%" /><col width="30%" />
        <tr>
        	<th><label><?php echo $this->lang->line('search');?>:</label></th>
            <td><input type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) {$('#currentpage').val(0);getservicemanualslist();}" /></td>
            <th><label><?php echo $this->lang->line('brand'); ?>: </label></th>
            <td><?php echo $brand_select;?></td>
            <th><label><?php echo $this->lang->line('product'); ?>: </label></th>
            <td><span id="product_box"><?php echo $product_select;?></span></td>
            <td><input type="button" name="show" id="show" onclick="javascript:$('#currentpage').val(0);getservicemanualslist();" value="Show" class="button"/> <span class="message"><span class="message_text"></span></span></td>
        </tr>
    </table>
    <input type="hidden" name="currentpage" id="currentpage" value="0" />
</form>
</div>
<div id="servicemanualslist" style="display:none;"></div>