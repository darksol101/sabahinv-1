(function(a){var b={init:function(c){var d=this;if(!d.data("jqv")||d.data("jqv")==null){b._saveOptions(d,c);a(".formError").live("click",function(){a(this).fadeOut(150,function(){a(this).remove()})})}},attach:function(a){var c=this;var d;if(a)d=b._saveOptions(c,a);else d=c.data("jqv");var e=c.find("[data-validation-engine*=validate]")?"data-validation-engine":"class";if(!d.binded){if(d.bindMethod=="bind"){c.find("[class*=validate]:not([type=checkbox])").bind(d.validationEventTrigger,b._onFieldEvent);c.find("[class*=validate][type=checkbox]").bind("click",b._onFieldEvent);c.bind("submit",b._onSubmitEvent)}else if(d.bindMethod=="live"){c.find("[class*=validate]:not([type=checkbox])").live(d.validationEventTrigger,b._onFieldEvent);c.find("[class*=validate][type=checkbox]").live("click",b._onFieldEvent);c.live("submit",b._onSubmitEvent)}d.binded=true}},detach:function(){var a=this;var c=a.data("jqv");if(c.binded){a.find("[class*=validate]").not("[type=checkbox]").unbind(c.validationEventTrigger,b._onFieldEvent);a.find("[class*=validate][type=checkbox]").unbind("click",b._onFieldEvent);a.unbind("submit",b.onAjaxFormComplete);a.find("[class*=validate]").not("[type=checkbox]").die(c.validationEventTrigger,b._onFieldEvent);a.find("[class*=validate][type=checkbox]").die("click",b._onFieldEvent);a.die("submit",b.onAjaxFormComplete);a.removeData("jqv")}},validate:function(){return b._validateFields(this)},validateField:function(c){var d=a(this).data("jqv");return b._validateField(a(c),d)},validateform:function(){return b._onSubmitEvent.call(this)},showPrompt:function(a,c,d,e){var f=this.closest("form");var g=f.data("jqv");if(!g)g=b._saveOptions(this,g);if(d)g.promptPosition=d;g.showArrow=e==true;b._showPrompt(this,a,c,false,g)},hidePrompt:function(){var c="."+b._getClassName(a(this).attr("id"))+"formError";a(c).fadeTo("fast",.3,function(){a(this).remove()})},hide:function(){var b;if(a(this).is("form")){b="parentForm"+a(this).attr("id")}else{b=a(this).attr("id")+"formError"}a("."+b).fadeTo("fast",.3,function(){a(this).remove()})},hideAll:function(){a(".formError").fadeTo("fast",.3,function(){a(this).remove()})},_onFieldEvent:function(){var c=a(this);var d=c.closest("form");var e=d.data("jqv");b._validateField(c,e)},_onSubmitEvent:function(){var c=a(this);var d=c.data("jqv");var e=b._validateFields(c,true);if(e&&d.ajaxFormValidation){b._validateFormWithAjax(c,d);return false}if(d.onValidationComplete){d.onValidationComplete(c,e);return false}return e},_checkAjaxStatus:function(b){var c=true;a.each(b.ajaxValidCache,function(a,b){if(!b){c=false;return false}});return c},_validateFields:function(c,d){var e=c.data("jqv");var f=false;c.trigger("jqv.form.validating");c.find("[class*=validate]").not(":hidden").each(function(){var c=a(this);f|=b._validateField(c,e,d)});c.trigger("jqv.form.result",[f]);if(f){if(e.scroll){var g=Number.MAX_VALUE;var h=0;var i=a(".formError:not('.greenPopup')");for(var j=0;j<i.length;j++){var k=a(i[j]).offset().top;if(k<g){g=k;h=a(i[j]).offset().left}}if(!e.isOverflown)a("html:not(:animated),body:not(:animated)").animate({scrollTop:g,scrollLeft:h},1100);else{var l=a(e.overflownDIV);var m=l.scrollTop();var n=-parseInt(l.offset().top);g+=m+n-5;var o=a(e.overflownDIV+":not(:animated)");o.animate({scrollTop:g},1100);a("html:not(:animated),body:not(:animated)").animate({scrollTop:l.offset().top,scrollLeft:h},1100)}}return false}return true},_validateFormWithAjax:function(c,d){var e=c.serialize();var f=d.ajaxFormValidationURL?d.ajaxFormValidationURL:c.attr("action");a.ajax({type:"GET",url:f,cache:false,dataType:"json",data:e,form:c,methods:b,options:d,beforeSend:function(){return d.onBeforeAjaxFormValidation(c,d)},error:function(a,c){b._ajaxError(a,c)},success:function(e){if(e!==true){var f=false;for(var g=0;g<e.length;g++){var h=e[g];var i=h[0];var j=a(a("#"+i)[0]);if(j.length==1){var k=h[2];if(h[1]==true){if(k==""||!k){b._closePrompt(j)}else{if(d.allrules[k]){var l=d.allrules[k].alertTextOk;if(l)k=l}b._showPrompt(j,k,"pass",false,d,true)}}else{f|=true;if(d.allrules[k]){var l=d.allrules[k].alertText;if(l)k=l}b._showPrompt(j,k,"",false,d,true)}}}d.onAjaxFormComplete(!f,c,e,d)}else d.onAjaxFormComplete(true,c,"",d)}})},_validateField:function(c,d,e){if(!c.attr("id"))a.error("jQueryValidate: an ID attribute is required for this field: "+c.attr("name")+" class:"+c.attr("class"));var f=c.attr("class");var g=/validate\[(.*)\]/.exec(f);if(!g)return false;var h=g[1];var i=h.split(/\[|,|\]/);var j=false;var k=c.attr("name");var l="";var m=false;d.isError=false;d.showArrow=true;for(var n=0;n<i.length;n++){var o=undefined;switch(i[n]){case"required":m=true;o=b._required(c,i,n,d);break;case"custom":o=b._customRegex(c,i,n,d);break;case"ajax":if(!e){b._ajax(c,i,n,d);j=true}break;case"minSize":o=b._minSize(c,i,n,d);break;case"maxSize":o=b._maxSize(c,i,n,d);break;case"min":o=b._min(c,i,n,d);break;case"max":o=b._max(c,i,n,d);break;case"past":o=b._past(c,i,n,d);break;case"future":o=b._future(c,i,n,d);break;case"maxCheckbox":o=b._maxCheckbox(c,i,n,d);c=a(a("input[name='"+k+"']"));break;case"minCheckbox":o=b._minCheckbox(c,i,n,d);c=a(a("input[name='"+k+"']"));break;case"equals":o=b._equals(c,i,n,d);break;case"funcCall":o=b._funcCall(c,i,n,d);break;default:}if(o!==undefined){l+=o+"<br/>";d.isError=true}}if(!m){if(c.val()=="")d.isError=false}var p=c.attr("type");if((p=="radio"||p=="checkbox")&&a("input[name='"+k+"']").size()>1){c=a(a("input[name='"+k+"'][type!=hidden]:first"));d.showArrow=false}if(d.isError){b._showPrompt(c,l,"",false,d)}else{if(!j)b._closePrompt(c)}c.trigger("jqv.field.result",[c,d.isError,l]);return d.isError},_required:function(b,c,d,e){switch(b.attr("type")){case"text":case"password":case"textarea":case"file":default:if(!b.val())return e.allrules[c[d]].alertText;break;case"radio":case"checkbox":var f=b.attr("name");if(a("input[name='"+f+"']:checked").size()==0){if(a("input[name='"+f+"']").size()==1)return e.allrules[c[d]].alertTextCheckboxe;else return e.allrules[c[d]].alertTextCheckboxMultiple}break;case"select-one":if(!b.val())return e.allrules[c[d]].alertText;break;case"select-multiple":if(!b.find("option:selected").val())return e.allrules[c[d]].alertText;break}},_customRegex:function(a,b,c,d){var e=b[c+1];var f=d.allrules[e];if(!f){alert("jqv:custom rule not found "+e);return}var g=f.regex;if(!g){alert("jqv:custom regex not found "+e);return}var h=new RegExp(g);if(!h.test(a.val()))return d.allrules[e].alertText},_funcCall:function(a,b,c,d){var e=b[c+1];var f=window[e];if(typeof f=="function")return f(a,b,c,d)},_equals:function(b,c,d,e){var f=c[d+1];if(b.val()!=a("#"+f).val())return e.allrules.equals.alertText},_maxSize:function(a,b,c,d){var e=b[c+1];var f=a.val().length;if(f>e){var g=d.allrules.maxSize;return g.alertText+e+g.alertText2}},_minSize:function(a,b,c,d){var e=b[c+1];var f=a.val().length;if(f<e){var g=d.allrules.minSize;return g.alertText+e+g.alertText2}},_min:function(a,b,c,d){var e=parseFloat(b[c+1]);var f=parseFloat(a.val());if(f<e){var g=d.allrules.min;if(g.alertText2)return g.alertText+e+g.alertText2;return g.alertText+e}},_max:function(a,b,c,d){var e=parseFloat(b[c+1]);var f=parseFloat(a.val());if(f>e){var g=d.allrules.max;if(g.alertText2)return g.alertText+e+g.alertText2;return g.alertText+e}},_past:function(a,c,d,e){var f=c[d+1];var g=f.toLowerCase()=="now"?new Date:b._parseDate(f);var h=b._parseDate(a.val());if(h<g){var i=e.allrules.past;if(i.alertText2)return i.alertText+b._dateToString(g)+i.alertText2;return i.alertText+b._dateToString(g)}},_future:function(a,c,d,e){var f=c[d+1];var g=f.toLowerCase()=="now"?new Date:b._parseDate(f);var h=b._parseDate(a.val());if(h>g){var i=e.allrules.future;if(i.alertText2)return i.alertText+b._dateToString(g)+i.alertText2;return i.alertText+b._dateToString(g)}},_maxCheckbox:function(b,c,d,e){var f=c[d+1];var g=b.attr("name");var h=a("input[name='"+g+"']:checked").size();if(h>f){e.showArrow=false;if(e.allrules.maxCheckbox.alertText2)return e.allrules.maxCheckbox.alertText+" "+f+" "+e.allrules.maxCheckbox.alertText2;return e.allrules.maxCheckbox.alertText}},_minCheckbox:function(b,c,d,e){var f=c[d+1];var g=b.attr("name");var h=a("input[name='"+g+"']:checked").size();if(h<f){e.showArrow=false;return e.allrules.minCheckbox.alertText+" "+f+" "+e.allrules.minCheckbox.alertText2}},_ajax:function(c,d,e,f){var g=d[e+1];var h=f.allrules[g];var i=h.extraData;var j=h.extraDataDynamic;if(!i)i="";if(j){var k=[];var l=String(j).split(",");for(var e=0;e<l.length;e++){var m=l[e];if(a(m).length){var n=c.closest("form").find(m).val();var o=m.replace("#","")+"="+escape(n);k.push(o)}}j=k.join("&")}else{j=""}if(!f.isError){a.ajax({type:"GET",url:h.url,cache:false,dataType:"json",data:"fieldId="+c.attr("id")+"&fieldValue="+c.val()+"&extraData="+i+"&"+j,field:c,rule:h,methods:b,options:f,beforeSend:function(){var a=h.alertTextLoad;if(a)b._showPrompt(c,a,"load",true,f)},error:function(a,c){b._ajaxError(a,c)},success:function(c){var d=c[0];var e=a(a("#"+d)[0]);if(e.length==1){var g=c[1];var i=c[2];if(!g){f.ajaxValidCache[d]=false;f.isError=true;if(i){if(f.allrules[i]){var j=f.allrules[i].alertText;if(j)i=j}}else i=h.alertText;b._showPrompt(e,i,"",true,f)}else{if(f.ajaxValidCache[d]!==undefined)f.ajaxValidCache[d]=true;if(i){if(f.allrules[i]){var j=f.allrules[i].alertTextOk;if(j)i=j}}else i=h.alertTextOk;if(i)b._showPrompt(e,i,"pass",true,f);else b._closePrompt(e)}}}})}},_ajaxError:function(a,b){if(a.status==0&&b==null)alert("The page is not served from a server! ajax call failed");else if(typeof console!="undefined")console.log("Ajax error: "+a.status+" "+b)},_dateToString:function(a){return a.getFullYear()+"-"+(a.getMonth()+1)+"-"+a.getDate()},_parseDate:function(a){var b=a.split("-");if(b==a)b=a.split("/");return new Date(b[0],b[1]-1,b[2])},_showPrompt:function(a,c,d,e,f,g){var h=b._getPrompt(a);if(g)h=false;if(h)b._updatePrompt(a,h,c,d,e,f);else b._buildPrompt(a,c,d,e,f)},_buildPrompt:function(c,d,e,f,g){var h=a("<div>");h.addClass(b._getClassName(c.attr("id"))+"formError");if(c.is(":input"))h.addClass("parentForm"+b._getClassName(c.parents("form").attr("id")));h.addClass("formError");switch(e){case"pass":h.addClass("greenPopup");break;case"load":h.addClass("blackPopup")}if(f)h.addClass("ajaxed");var i=a("<div>").addClass("formErrorContent").html(d).appendTo(h);if(g.showArrow){var j=a("<div>").addClass("formErrorArrow");switch(g.promptPosition){case"bottomLeft":case"bottomRight":h.find(".formErrorContent").before(j);j.addClass("formErrorArrowBottom").html('<div class="line1"><!-- --></div><div class="line2"><!-- --></div><div class="line3"><!-- --></div><div class="line4"><!-- --></div><div class="line5"><!-- --></div><div class="line6"><!-- --></div><div class="line7"><!-- --></div><div class="line8"><!-- --></div><div class="line9"><!-- --></div><div class="line10"><!-- --></div>');break;case"topLeft":case"topRight":j.html('<div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div>');h.append(j);break}}if(g.isOverflown)c.before(h);else a("body").append(h);var k=b._calculatePosition(c,h,g);h.css({top:k.callerTopPosition,left:k.callerleftPosition,marginTop:k.marginTopSize,opacity:0});return h.animate({opacity:.87})},_updatePrompt:function(a,c,d,e,f,g){if(c){if(e=="pass")c.addClass("greenPopup");else c.removeClass("greenPopup");if(e=="load")c.addClass("blackPopup");else c.removeClass("blackPopup");if(f)c.addClass("ajaxed");else c.removeClass("ajaxed");c.find(".formErrorContent").html(d);var h=b._calculatePosition(a,c,g);c.animate({top:h.callerTopPosition,marginTop:h.marginTopSize})}},_closePrompt:function(a){var c=b._getPrompt(a);if(c)c.fadeTo("fast",0,function(){c.remove()})},closePrompt:function(a){return b._closePrompt(a)},_getPrompt:function(c){var d="."+b._getClassName(c.attr("id"))+"formError";var e=a(d)[0];if(e)return a(e)},_calculatePosition:function(a,b,c){var d,e,f;var g=a.width();var h=b.height();var i=c.isOverflown;if(i){d=e=0;f=-h}else{var j=a.offset();d=j.top;e=j.left;f=0}switch(c.promptPosition){default:case"topRight":if(i)e+=g-30;else{e+=g-30;d+=-h}break;case"topLeft":d+=-h-10;break;case"centerRight":e+=g+13;break;case"bottomLeft":d=d+a.height()+15;break;case"bottomRight":e+=g-30;d+=a.height()+5}return{callerTopPosition:d+"px",callerleftPosition:e+"px",marginTopSize:f+"px"}},_saveOptions:function(b,c){if(a.validationEngineLanguage)var d=a.validationEngineLanguage.allRules;else a.error("jQuery.validationEngine rules are not loaded, plz add localization files to the page");var e=a.extend({validationEventTrigger:"blur",scroll:true,promptPosition:"topRight",bindMethod:"bind",inlineAjax:false,ajaxFormValidation:false,ajaxFormValidationURL:false,onAjaxFormComplete:a.noop,onBeforeAjaxFormValidation:a.noop,onValidationComplete:false,isOverflown:false,overflownDIV:"",allrules:d,binded:false,showArrow:true,isError:false,ajaxValidCache:{}},c);b.data("jqv",e);return e},_getClassName:function(a){return a.replace(":","_").replace(".","_")}};a.fn.validationEngine=function(c){var d=a(this);if(!d[0])return false;if(typeof c=="string"&&c.charAt(0)!="_"&&b[c]){if(c!="showPrompt"&&c!="hidePrompt"&&c!="hide"&&c!="hideAll")b.init.apply(d);return b[c].apply(d,Array.prototype.slice.call(arguments,1))}else if(typeof c=="object"||!c){b.init.apply(d,arguments);return b.attach.apply(d)}else{a.error("Method "+c+" does not exist in jQuery.validationEngine")}}})(jQuery)