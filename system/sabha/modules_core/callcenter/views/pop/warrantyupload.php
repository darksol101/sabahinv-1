<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
#facebox .footer {
	visibility:visible;
}
</style>
<div style="width:650px;">
    <form id="fileupload" action="<?php echo site_url();?>warranty/upload" method="post" enctype="multipart/form-data">
        <div class="row fileupload-buttonbar">
            <div class="span7">
                <span class="btn btn-success fileinput-button">
                    <span><i class="icon-plus icon-white"></i> Add files...</span>
                    <input type="file" name="files" />
                </span><br />
                <button type="submit" class="btn btn-primary start">
                    <i class="icon-upload icon-white"></i> Start upload
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i> Cancel upload
                </button>
                <!--<button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i> Delete
                </button>-->
                <input type="checkbox" class="toggle">
            </div>
            <div class="span5">
                <div class="progress progress-success progress-striped active fade">
                    <div class="bar" style="width:0%;"></div>
                </div>
            </div>
        </div>
        <div class="fileupload-loading"></div>
        <br>
        <table class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
        <input type="hidden" value="<?php echo $call_id;?>" name="call_id"  />
    </form>
</div>
<div id="modal-gallery" class="modal modal-gallery hide fade">
    <div class="modal-body"><div class="modal-image"></div></div>
</div>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name">{%=file.name%}</td>
        <td class="size">{%=o.formatFileSize(file.size)%}</td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i> {%=locale.fileupload.start%}
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i> {%=locale.fileupload.cancel%}
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<script id="template-download" type="text/x-tmpl">
</script>
<script>
(function(a){"use strict";var b=function(a,c){var d=/[^\w\-\.:]/.test(a)?new Function(b.arg+",tmpl","var _e=tmpl.encode"+b.helper+",_s='"+a.replace(b.regexp,b.func)+"';return _s;"):b.cache[a]=b.cache[a]||b(b.load(a));return c?d(c,b):function(a){return d(a,b)}};b.cache={},b.load=function(a){return document.getElementById(a).innerHTML},b.regexp=/([\s'\\])(?![^%]*%\})|(?:\{%(=|#)([\s\S]+?)%\})|(\{%)|(%\})/g,b.func=function(a,b,c,d,e,f){if(b)return{"\n":"\\n","\r":"\\r","\t":"\\t"," ":" "}[a]||"\\"+a;if(c)return c==="="?"'+_e("+d+")+'":"'+("+d+"||'')+'";if(e)return"';";if(f)return"_s+='"},b.encReg=/[<>&"'\x00]/g,b.encMap={"<":"&lt;",">":"&gt;","&":"&amp;",'"':"&quot;","'":"&#39;"},b.encode=function(a){return String(a||"").replace(b.encReg,function(a){return b.encMap[a]||""})},b.arg="o",b.helper=",print=function(s,e){_s+=e&&(s||'')||_e(s);},include=function(s,d){_s+=tmpl(s,d);}",typeof define=="function"&&define.amd?define(function(){return b}):a.tmpl=b})(this);
</script>

<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.iframe-transport.js"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.fileupload.js"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.fileupload-ui-warranty.js"></script>
<script src="<?php echo base_url();?>assets/jquery/main.js"></script>
<script src="<?php echo base_url();?>assets/jquery/locale.js"></script>
<script>
showWarrantyUploadList('<?php echo $call_id;?>');
function showWarrantyUploadList(call_id){
	
	var params = 'call_id='+call_id+'&ajaxaction=getwarrantyuploadlist&unq='+ajaxunq();
	$.ajax({
		   type:'POST',
		   url:'<?php echo site_url();?>warranty/getwarrantyuploads',
		   data:params,
		   success:function(data){
			  		$("#warrantyuploadlist").html(data);
			   }
		   });
}
function deleteWarrantyUpload(call_id,warranty_upload_id,file_name){
	if(confirm('Are you to delete this warranty')){
		var params = 'call_id='+call_id+"&warranty_upload_id="+warranty_upload_id+"&file_name="+file_name+"&unq"+ajaxunq();
		$.ajax({			
				type	:	"POST",
				url		:	"<?php echo base_url();?>warranty/deletewarrantyuplaod",
				data	:	params,
				success	:	function (data){
					showWarrantyUploadList(call_id);
					}								
		});
	}
}
</script>
<div id="warrantyuploadlist"></div>

