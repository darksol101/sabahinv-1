<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script language="javascript">

var sn;
$(document).ready(function(){
    $('.content-box ul.content-box-tabs li a').click(function(){
        window.location='<?php echo base_url()?>'+this.id;
    });


    $("#received").on('change', function () {
        var rec = $(this).val();
        var tot = $("#grand_total").val();
        var returns = (rec-tot).toFixed(0);
        if(rec >= tot || rec!=0){
            $("#returns").val(returns);
        }
        

    });


    $('a.editpart').live('click',function(event) {
        $('#part_number').attr('readonly','readonly');
        event.preventDefault();
        var ele = $(this).parent().parent();
        $("#add_part").val('Edit');
        $("#cancel_part").show();
        $(this).parent().parent().parent().find('tr').removeClass('editactive');
        $(this).parent().parent().addClass('editactive');
        var order_part_id = ele.find('td:first').next().find('input:first').val();
        //alert(order_part_id);
        $("#hdnsales_details_id").val(order_part_id);
        var part_number = ele.find('td:first').next().find('input:last').val();
        var part_description = ele.find('td:first').next().next().find('input').val();
        var company = ele.find('td:first').next().next().next().find('input').val();
        //alert (company);
        var part_quantity = ele.find('td:first').next().next().next().next().find('input').val();
        var rate = ele.find('td:first').next().next().next().next().next().find('input').val();
        var call_id = ele.find('td:first').next().next().next().next().next().next().find('input').val();
        var part_id = ele.find('td:first').find('input').val();
        //alert (company);
        //alert(rate);
        //alert(part_number+'--'+part_quantity+'--'+part_rate)
// 
        $("#part_number").val(part_number);
        $("#part_id").val(part_id);
        $("#part_description").val(part_description);
        $("#part_quantity").val(part_quantity);
        $("#hdnorder_part_id").val(order_part_id);
        $("#part_rate").val(rate);
        $("#company_name").val(company);
        calculatetotal();

    });
    $('a.deletepart').live('click',function(event) {
        if(confirm("Do you want to delete this Part ?")){
            event.preventDefault();
            var ele = $(this).parent().parent();
            var sales_id = $("#sales_id").val();
            var sales_detail_id = ele.find('td:first').next().find('input:first').val();
            var params="sales_detail_id="+sales_detail_id+"&unq="+ajaxunq()+"&sales_id="+sales_id;
            $.ajax({
                type	:	"POST",
                url		:	"<?php echo site_url();?>sales/deletesalesparts",
                data	:	params,
                success	:	function (data){
                    if (data == 1){
                        $(ele).remove();
                       
                        hideloading(data);
                        calculatetotal();
                        if(sales_id > 0){
                            //fillValue(sales_id);
                        }
                    }
                }
            });//end  ajax
        }
         $("#generateBill").hide();
    });

    $('a.returnpart').live('click',function(event) {
        if(confirm("Do you want to Return this Part ?")){
            event.preventDefault();
            var ele = $(this).parent().parent();
            var sales_detail_id = ele.find('td:first').next().find('input:first').val();
            var part_number = ele.find('td:first').next().find('input:last').val();
            var part_quantity = ele.find('td:first').next().next().next().find('input').val();
            var sc_id = $("#sc_id").val();

            //alert(ele.find('td:first').next().next().next().next().next().find('a.returnpart').html());
            //return false;
            var params = "sales_detail_id="+sales_detail_id+"&unq="+ajaxunq()+"&part_number ="+part_number+"&part_quantity ="+part_quantity+"&sc_id ="+sc_id;
            $.ajax({
                type	:	"POST",
                url		:	"<?php echo site_url();?>sales/returnparts",
                data	:	params,
                success	:	function (data){
                    //ele.find('td:first').next().next().next().next().next().find('a.returnpart').hide();
                    calculatetotal();
                    hideloading(data);
                }
            });//end  ajax
        }

    });





});

function cancelrow()
{
    $("#part_number").val('');
    $('#part_number').removeAttr('readonly');
    $("#part_quantity").val('');
    $("#part_description").val('');
    $("#cancel_part").hide();
    $("#add_part").val('Add');
    $("#company_name").val('');
    $('#part_rate').val('');
    $("#part_id").val(0);

}
function closeform()
{
    window.location='<?php echo base_url();?>sales';
}



function checkpart(){
    $("#add_part").attr('disabled','disabled');
    $('#part_number').removeAttr('readonly');
    var part_number = $("#part_number").val();
    var part_quantity= $("#part_quantity").val();
    var company ='Default'
    var purchase_details_id = $("#hdnpurchase_details_id").val();
    var status = 1;
    if(company==''){
        $('#select_company').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
        status = 0;
        $("#add_part").removeAttr('disabled');
    }
    if(part_number==''){
        $('#part_number').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
        status = 0;
        $("#add_part").removeAttr('disabled');
    }
    if(part_quantity==''){
        $('#part_quantity').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
        status = 0;
        $("#add_part").removeAttr('disabled');
    }
    if(part_quantity<= 0){
        $('#part_quantity').validationEngine('showPrompt', '* Invalid quantity', 'error', 'topRight', true);
        status = 0;
        $("#add_part").removeAttr('disabled');
    }



    var params="part_number="+part_number+"&part_quantity="+part_quantity+"&company="+company+"&unq="+ajaxunq();
    //console.log(params);
    if (status == 1){
        $.ajax({
            type	:	"POST",
            url		:	"<?php echo site_url();?>purchase/checkpart_sale",
            data	:	params,
            dataType: 'json',
            success	:	function (data){
                if (data==3){
                    alert ('Invalid Item Number!!');
                }
                else if (data ==2){
                    alert('Invalid Item Quantity');
                }
                else if (data ==4){
                    alert('Invalid part Quantity & Item Number');
                }
                else{
                    //console.log(data[0]);
                    checkOffer(data[0].part_id);
                    hideloading();
                }

            }
        });
    }
}

function checkOffer (part_id) {
    $.ajax({
        type	:	"POST",
        url		:	"<?php echo site_url();?>sales/checkOffer",
        data	:	{part_id:part_id},
        dataType: 'json',
        success	:	function (data){
            addpart(data);
            $("#add_part").removeAttr('disabled');
            calculatetotal();
        }
    });

}

function addpart(data){
    $("#cancel_part").hide();
    var part_number = $("#part_number").val();
    var part_quantity = $("#part_quantity").val();
    var part_rate = $('#part_rate').val();
    //console.log(part_rate);
    var part_id = $('#part_id').val();
    //getting the checkoffer data
    var dis_amount = 0;
    var maker_id = 0;


    if(data.length > 0 ){
     var sale_name = data[0].sale_name;
        maker_id = data[0].maker_id;
        console.log(data[0]);
     var sale_deduction_type = data[0].sale_deduction_type;
     var sale_deduction_value = data[0].sale_deduction_value;
         if(sale_deduction_type==1){
         //salesmaker discount %
         if(confirm('This Items is valid for '+sale_name+' Offer with Discount of '+sale_deduction_value+'%')){
            dis_amount =(part_rate*(sale_deduction_value/100));
            $("#discount_type").attr('disabled',true);
            }else{
                 maker_id =0; 
                 $("#discount_type").removeAttr('disabled');
             }
            
         }
    
         else if(sale_deduction_type==2){
         //salesmaker discount amount
         if(confirm('This Items is valid for '+sale_name+' Offer with Discount of Rs.'+sale_deduction_value)){
             dis_amount =sale_deduction_value;
             $("#discount_type").attr('disabled',true);
         }else{
             maker_id =0; 
             $("#discount_type").removeAttr('disabled');
        }
     }
     

     }

    //discounted rate

     var dis_rate = (part_rate - dis_amount).toFixed(2);
     var total_rate = (dis_rate * part_quantity).toFixed(2);


  /*  console.log(data[0]);
    console.log(dis_amount+" "+maker_id+" "+sale_name);*/
    //var part_rate = ((part_rate1*100)/100).toFixed(2);
    
    var part_description = $("#part_description").val();
    var hdnsales_details_id = $("#hdnsales_details_id").val();
    var company = "Default";
    var call_id = $("#call_id").val();
    //alert (part_rate+"------------------"+part_quantity);
    var serial = $("#serial").val();
    //alert(serial);
    var price = (((part_quantity * part_rate)*100)/100);

    $("#purchaseForm").validationEngine('hideAll');
    if( hdnsales_details_id > 0 ){
        //alert('asdf00'); return false;
        var ele = $(".editactive");
        $(ele).find('td:first').next().next().next().next().find('input').val(part_quantity);
        $(ele).find('td:first').next().next().next().next().find('span.lbl').html(part_quantity);
        $(ele).find('td:first').next().next().next().next().next().find('input').val(part_rate);
        $(ele).find('td:first').next().next().next().next().next().find('span.lbl').html(part_rate);
        $(ele).find('td:first').next().next().next().next().next().next().find('input').val(dis_rate);
        $(ele).find('td:first').next().next().next().next().next().next().find('span.lbl').html(dis_rate);
        $(ele).find('td:first').next().next().next().next().next().next().next().find('span.lbl').html((dis_rate*part_quantity).toFixed(2));
        $(ele).find('td:first').next().next().next().find('input').val(company);
        $(ele).find('td:first').next().next().next().find('span.lbl').html(company);
        $(ele).find('td:first').next().next().find('input').val(part_description);
        $(ele).find('td:first').next().next().find('span.lbl').html(part_description);
        $(ele).find('td:first').next().find('input:nth-child(1)').val(hdnsales_details_id);
        $(ele).find('td:first').next().find('span.lbl').html(part_number);
        $(ele).find('td:first').find('input').val(part_id);
        $(ele).find('td:first').find('input:nth-child(2)').val(maker_id);
      /*  var tds = $(ele).children();
        $(tds[5]).find('span.lbl').html(price.toFixed(2));*/
        $("#generateBill").hide();
        $(ele).parent().find('tr').removeClass('editactive');
    }
    else{
        sn = $("#rowdata tr:last td.sn_td span.spn_counter").html();

        sn++;
        var html = '';
        var trclass = (sn%2==0)?'even':'odd';
        html+= '<td class="sn_td"><input type="hidden" name="p_id[]" value="'+part_id+'" class="text-input" /><input type="hidden" name="maker_id[]" value="'+maker_id+'" class="text-input" /><span class="spn_counter">'+sn+'</span></td>';
        html+='<td><input type="hidden" value="" class="sales_details_id" name="sales_details_id[]" /><input type="hidden" name="pnum[]" value="'+part_number+'" class="text-input" /><span class="lbl">'+part_number+'</span></td>';
        html+='<td style="text-align: left;"><input type="hidden" name="pdesc[]" value="'+part_description+'" class="text-input" /><span class="lbl">'+part_description+'</span></td>';
        html+='<td style="text-align: center;  display:none;"><input type="hidden" name="comp[]" value="'+company+'" class="text-input" /><span class="lbl">'+company+'</span></td>';
        html+='<td style="text-align: center;"><input type="hidden" name="pqty[]" value="'+
            part_quantity+'" class="text-input" /><span class="lbl">'+part_quantity+'</span></td>';
        html+='<td style="text-align: right;"><input type="hidden" name="prate[]" value="'+part_rate+'" class="text-input" /><span id="'+part_number+'" class="lbl">'+part_rate+'</span></td>';
        html+='<td style="text-align: right;"><input type="hidden" name="drate[]" value="'+dis_rate+'" class="text-input" /><span class="lbl">'+dis_rate+'</span></td>';
        html+='<td style="text-align: right;"><span class="lbl">'+total_rate+'</span><input type="hidden" name="call_ids[]" value="'+call_id+'" class="text-input" /></td>';
        html+='<td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>';
        html+='<td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td>';
        html='<tr class="'+trclass+'">'+html+'</tr>';
        var add=1;
        //add case
        $("#rowdata tr").each(function(index) {
            var ptn = $(this).find('td:first').next().find('input:last').val();
            var sn = $(this).find("td.sn_td").html();
            // alert('loop'+sn+'----------'+serial);
            if (serial == sn){

                if(ptn == part_number ){

                    if(confirm('Item number '+part_number+' already exists,Do you want to update it ?')){
                        $(this).find('td:first').next().next().next().next().find('input').val(part_quantity);
                        $(this).find('td:first').next().next().next().next().find('span.lbl').html(part_quantity);
                        $(this).find('td:first').next().next().next().next().next().find('input').val(part_rate);
                        $(this).find('td:first').next().next().next().next().next().find('span.lbl').html(part_rate);
                        $(this).find('td:first').next().next().next().next().next().next().find('input').val(dis_rate);
                        $(this).find('td:first').next().next().next().next().next().next().find('span.lbl').html(dis_rate);
                        $(this).find('td:first').next().next().next().next().next().next().next().find('span.lbl').html((dis_rate*part_quantity).toFixed(2));

                        $(this).find('td:first').next().next().next().find('input').val(company);
                        $(this).find('td:first').next().next().next().find('span.lbl').html(company);

                        $(this).find('td:first').next().next().find('input').val(part_description);
                        $(this).find('td:first').next().next().find('span.lbl').html(part_description);
                        $(this).find('td:first').next().find('input:nth-child(2)').val(part_number);
                        $(this).find('td:first').next().find('span.lbl').html(part_number);
                        $(this).find('td:last').find('input').val(part_id);
                        $(this).find('td:first').find('input:nth-child(2)').val(maker_id);
                        //$("#serial").val('');
                        add = 0;
                    }
                    else{

                        add = 0;
                        $("#serial").val('');
                        return;
                    }
                }else{
                    if(confirm('Do you Want to Update this this row ?')){

                        $(this).find('td:first').next().next().next().next().find('input').val(part_quantity);
                        $(this).find('td:first').next().next().next().next().find('span.lbl').html(part_quantity);
                        $(this).find('td:first').next().next().next().next().next().find('input').val(part_rate);
                        $(this).find('td:first').next().next().next().next().next().find('span.lbl').html(part_rate);
                        $(this).find('td:first').next().next().next().next().next().next().find('input').val(dis_rate);
                        $(this).find('td:first').next().next().next().next().next().next().find('span.lbl').html(dis_rate);
                        $(this).find('td:first').next().next().next().next().next().next().next().find('span.lbl').html((dis_rate*part_quantity).toFixed(2));
                        $(this).find('td:first').next().next().next().find('input').val(company);
                        $(this).find('td:first').next().next().next().find('span.lbl').html(company);
                        $(this).find('td:first').next().next().find('input').val(part_description);
                        $(this).find('td:first').next().next().find('span.lbl').html(part_description);
                        $(this).find('td:first').next().find('input').val(part_number);
                        $(this).find('td:first').next().find('span.lbl').html(part_number);
                        $(this).find('td:last').find('input').val(part_id);
                        $(this).find('td:first').find('input:nth-child(2)').val(maker_id);
                        //$("#serial").val('');
                        add = 0;
                    }
                    else{

                        add = 0;
                        $("#serial").val('');
                        return;
                    }
                }
            }

            else{ if(ptn == part_number ){
                if(confirm('Item number '+part_number+' already exists,Do you want to update it ?')){
                    $(this).find('td:first').next().next().next().next().find('input').val(part_quantity);
                    $(this).find('td:first').next().next().next().next().find('span.lbl').html(part_quantity);
                    $(this).find('td:first').next().next().next().next().next().find('input').val(part_rate);
                    $(this).find('td:first').next().next().next().next().next().find('span.lbl').html(part_rate);
                    $(this).find('td:first').next().next().next().next().next().next().find('input').val(dis_rate);
                    $(this).find('td:first').next().next().next().next().next().next().find('span.lbl').html(dis_rate);
                    $(this).find('td:first').next().next().next().next().next().next().next().find('span.lbl').html((dis_rate*part_quantity).toFixed(2));


                    $(this).find('td:first').next().next().next().find('input').val(company);
                    $(this).find('td:first').next().next().next().find('span.lbl').html(company);

                    $(this).find('td:first').next().next().find('input').val(part_description);
                    $(this).find('td:first').next().next().find('span.lbl').html(part_description);
                    $(this).find('td:first').next().find('input').val(part_number);
                    $(this).find('td:first').next().find('span.lbl').html(part_number);
                    $(this).find('td:first').find('input:nth-child(2)').val(maker_id);
                    //$("#serial").val('');
                    add = 0;
                }
                else
                    add = 0;
                return true;
            }}


        });

    }
    if(add==1){
        $("#rowdata").append(html);
        $("#serial").val('');
    }

   //console.log($("#rowdata tr:nth-child(2)"));
   showGenerateBill();
  

    $("#hdnpurchase_details_id").val(0);
    //$("#sno").val('');
    $("#part_number").val('');
    $("#part_quantity").val('');
    $("#part_description").val('');
    $("#select_company").val('');
    $('#part_rate').val('');
    $('#part_id').val(0);
    $('#call_id').val('');
    $('#maker_id').val(0);
    $("#add_part").val('Add');
    calculatetotal();

}

 function showGenerateBill() {
        $("#purchaseForm input.sales_details_id").each(function () {
            if($(this).val() ==""){
                $("#generateBill").hide();
            }
        });
    }

function printbill(sales_id){

    $.facebox(function() {
        $.post('<?php echo site_url();?>sales/printbill',{sales_id:sales_id},
            function(data) { $.facebox(data) });
    })

}

function calculatetotal(){
    var total = 0 ;
    var grand_total = 0 ;
    var dis_amount = 0;
    var final_amount = 0;
    $("#rowdata tr").each(function(index){

      /* *//* var rate = $(this).find('td:first').next().next().next().next().next().find('input:first').val();
        var quantity = $(this).find('td:first').next().next().next().next().find('input:last').val();*//*


        total = rate * quantity;
        grand_total = grand_total + total;
*/
        var rate =$(this).find('td:nth-child(7) input').val();
        var quantity =$(this).find('td:nth-child(5) input').val();

        //console.log(rate+' '+quantity);
        total = rate * quantity;
        grand_total = grand_total + total;
    });

    $("#total_price").val(((grand_total*100)/100).toFixed(2));
    var discount_type = $('#discount_type').val();
    var discount = $('#discount').val();
    var tax = 13;

    if (discount_type ==1){
        dis_amount =  (discount / 100)*grand_total ;
    }
    else if (discount_type ==2){
        dis_amount = discount ;
    }
    else {
        dis_amount = 0 ;
    }
    
    var pricex= (grand_total - dis_amount);
    $("#discounted_total").val(((pricex*100)/100).toFixed(2));

    /*var taxamount = (grand_total - dis_amount) * 0.13;

    var tot = (grand_total - dis_amount) + ((tax / 100)*(grand_total - dis_amount));

    totl = ((tot*100)/100).toFixed(2); //numberRound(tot)
    taxamountl = ((taxamount*100)/100).toFixed(2);          //numberRound(taxamount);
    $("#grand_total").val(totl);

    $("#tax").val(taxamountl);*/

    var tot = (grand_total - dis_amount).toFixed(2);
    var g_tot = grand_total.toFixed(2);

    $("#grand_total").val(tot);

    //check if received

   /* var rec = $("#received").val();
    if(rec!="" && rec > tot){
        var returns = (rec-tot).toFixed(2);
        $("#returns").val(returns);
    }
*/
    abb_show();

}


function printordercard()
{
    var bill_id = $('#bill_id').val();
    confirmprint(bill_id);
    var content = document.getElementById("cardContent");
    var pri = document.getElementById("ifmcontentstoprint").contentWindow;
    pri.document.open();
    pri.document.write(content.innerHTML);
    pri.document.close();
    pri.focus();
    pri.print();
    $(document).trigger('close.facebox');
}
function generateBill(){
    var t = confirm('Do you Want to generatebill? Bill once generated cannot be reversed');
    if (t == true){
        var total_price = $("#total_price").val();
        var tax = $("#tax").val();
        var grand_total = $("#grand_total").val();
        var discount = $("#discount").val();
        var discount_type = $("#discount_type").val();
        var sales_id = $("#sales_id").val();
        var bill_id =$("#bill_number").val();
        var params="total_price="+total_price+"&bill_id="+bill_id+"&tax="+tax+"&sales_id="+sales_id+"&grand_total="+grand_total+"&discount="+discount+"&discount_type="+discount_type+"&unq="+ajaxunq();

        $.ajax({
            type	:	"POST",
            url		:	"<?php echo site_url();?>bills/generateBill",
            data	:	params,
            success	:	function (data){
                if(data == 1){
                    printbill(sales_id);
                }
            }});

    }
}

function confirmprint(bill_id){
    var params="bill_id="+bill_id;

    $.ajax({
        type	:	"POST",
        url		:	"<?php echo site_url();?>bills/confirmPrint",
        data	:	params,
        success	:	function (data){

        }});



}
function cancelBill(bill_id){
    var params="bill_id="+bill_id;

    $.ajax({
        type	:	"POST",
        url		:	"<?php echo site_url();?>bills/cancelBill",
        data	:	params,
        success	:	function (data){

        }});



}


function getSalesType(){
    var sales_type = $('#sales_type').val();
    var sc_id = $("#service_center").val();
    var warranty_sale = $('#warranty_sale').val();
    if(sc_id==''){
        $('#service_center').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
        $('#sales_type').val('');
        return false;
    }
    var params="sales_type="+sales_type+"&sc_id="+sc_id+"&warranty_claim="+warranty_sale+"&unq="+ajaxunq();
    $.ajax({
        type        :        "POST",
        url                :        "<?php echo site_url();?>sales/getledgersbytype",
        data        :        params,
        success        :        function (data){
            $('#spn_ledger').html(data);
            if(sales_type == 2){
                $('#customer_name').attr('readonly','readonly');
                // $('#customer_name').val('');
            }else{
                $('#customer_name').removeAttr('readonly','readonly');
                //$('#customer_name').val('');
            }

        }});
}
function setAccount(){
    var party_name = $('#ledger_id').find(":selected").text();
    if($('#ledger_id').val()){
        $('#customer_name').val(party_name);
    }else{
        $('#customer_name').val('');
    }
}


function abb_show(){

    if ($("#grand_total").val() > 5000){
        $("#abb_con").hide();

    }
    if ($("#grand_total").val() < 5000){
        $("#abb_con").show();

    }

}
function numberRound(number){
    return (Math.round(number*100)/100);
}
function GenerateBill(){
    var generateBill = document.purchaseForm;
    //console.log(generateBill); return false;
    if(confirm('Are you sure to generate bill.This process can not be reversed.')){
        generateBill.action="<?php echo site_url('sales/create_bill');?>";
        generateBill.submit();
    }else{
    }
}
function cancelGeneratedBill(){
    var cancelbill = document.cancelbill;
    if(confirm('Are you sure to cancel this bill?')){
        cancelbill.submit();
    }else{
    }
}
function getPrice(){
    var part_num = $('#part_number').val();
    if(part_num){
        var params = "part_num="+part_num+"&unq="+ajaxunq();
        $.ajax({
            type	:	"POST",
            url		:	"<?php echo site_url();?>sales/getPartNum",
            data	:	params,
            success	:	function (data){
                $('#part_rate').val(data);
            }});

    }

}
function checkDiscount(){
    var discount  = $('#discount_type').val();

    if(!discount){
        $('#discount').val(0);
        $('#discount').attr('readonly','readonly');
    }
    else{$('#discount').removeAttr('readonly');}
}


function fillValue(sales_id){
    var params = "sales_id="+sales_id;
    $.ajax({
        type : "POST",
        url	 : "<?php echo site_url();?>sales/getValues",
        data : params,
        success : function (data){
            var dt=eval('(' + data + ')')
            $("#discounted_total").val(dt.tot);
            $("#tax").val(dt.taxed_amount);
            $("#grand_total").val(dt.grand_total);
        }
    });
}

</script>
