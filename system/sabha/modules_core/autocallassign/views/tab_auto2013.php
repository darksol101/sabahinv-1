<style type="text/css">
.box_check{ float:left; xwidth:127px;}
.chbx{  vertical-align:middle!important; line-height:10px; font-size:.9em; padding-right:5px; }
form input[type="checkbox"]{vertical-align:middle!important;}
.invalid{background-color:#ffd;border-color:red}
label.invalid{color:red}
#loading{position:absolute;top:0;left:0;width:100%;height:100%;display:none;opacity:0.7;filter:alpha(opacity=70);z-index:999;background:#fff}
.spinner{opacity:0.9;z-index:999;background:#fff;height:100%;width:100%}
.spinner-img{background:url(assets/style/css/images/spinner.gif) no-repeat!important;width:66px;height:66px;text-align:center;margin:15% auto}
#msgUsr{font-size:14px;color:#A61D22}
</style>
	<form method="post">
    <table cellpadding="0" cellspacing="0" width="100%" class="toolbar1">
      <tbody>
        <tr>
          <th width="12%"><label><?php echo $this->lang->line('service_center');?>:</label></th>
          <td width="25%"><?php echo $servicecenter_select;?></td>
          <td>&nbsp;</td>
          <td colspan="3"><span class="message"><span class="message_text"></span></span></td>
        </tr>
      </tbody>
    </table>
    <div id="accordion">
      <h3 id="zones_heading"><a href="#">Zones</a></h3>
      <div id="zone_box"></div>
      <h3><a href="#">Districts</a></h3>
      <div>
      	<span id="district_chkbox" style="display:none" ><input type="checkbox" onclick="selectAllDistrict();" id="chkdistrict"/> All</span><div id="districts"></div>
       </div>
      <h3><a href="#">Cities</a></h3>
      <div>
      	<span id="city_chkbox" style="display: none"><input type="checkbox" onclick="selectAllCity();" id="chkcity"  /> All</span>
      	<div id="citiesbox"></div>
       </div>
      <h3><a href="#">Brands</a></h3>
      <div>
        <span id="brand_chkbox"><input type="checkbox" onclick="selectAllBrand();" id="chkbrand" />All</span>
        <div id="brand_box"></div>
      </div>
      <h3><a href="#">Products</a></h3>
      <div>
      	<span id="product_chkbox" style="display:none" ><input type="checkbox"onclick="selectAllProduct();" id="chkproduct" /> All</span>
      	<div id="products"></div>
      </div>
    </div>
    <table>
      <tfoot>
        <tr>
          <td align="left"><input type="button" name="assign" id="assign" value="Assign" class="button" onclick="saveAssignment();"  /></td>
          <td colspan="5" ></td>
        </tr>
      </tfoot>
    </table>
  </form>
<div id="result"></div>
  <div class="clear"></div>