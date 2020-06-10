@extends('layouts.tenant_sidebar')

@section('title')
    
    OPPORTUNITY MANAGEMENT

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.opportunity.show', [$tenant->domain, $opportunity->id] ) }}"> {{ $opportunity->title }} </a></li>
    <li class="active"> Opportunity Line </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title p-t-10"> 
                    <span class="h3"> Line </span> 
                    <span class="pull-right m-r-10">
                        <a href="javascript:;" class="btn btn-lg btn-outline-default"> <i class="fa fa-plus-circle"></i> Add Update </a>
                    </span>

                    <div class="panel-heading-btn m-r-10 m-t-10">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand" data-original-title="" title=""><i class="fa fa-expand"></i></a>
                    </div>
                </div> 

            </div>

            <div class="panel-body" style="min-height: 400px;">

                    <hr style="background-color: #51BB8D; height: 3px;">

                <br>


                <div class="col-md-12 col-sm-12 col-xs-12 bg-silver" >

                    @include('app.tenant.crm.opportunity_line.partials.details')

                </div>

            </div>
        </div>
    </div>

        

    @include('app.tenant.crm.opportunity_line.modal.index')


@endsection


@section('extra-script')

    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-opportunity').addClass('active');

        let currency = <?= json_encode($opportunity_line->currency); ?>; 
        let currency_code = currency.code;
        let currency_symbol = currency.symbol;
        let currency_name = currency.name;

        $(function () {
            let today = new Date();
            $('.datepicker').datepicker({
                format:'yyyy-m-d',
                // rangeLow: today.getFullYear() + today.getMonth() + today.getDay(),
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                minDate: today,
                onSelect: function(selectedDate) {
                    let option = this.id == "from" ? "minDate" : "maxDate",
                        instance = $(this).data("datepicker"),
                        date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                    dates.not(this).datepicker("option", option, date);
                }
            });

        });
    

        $('body').on('submit', '#opportunity_items_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('opportunity_items_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.opportunity.opportunity-line.items', [$tenant->domain, $opportunity->id, $opportunity_line->id]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);

                $.gritter.add({
                    title: 'Opportunity Created',
                    text: data.success,
                    sticky: false,
                    time: '8000'
                });
                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('body').on('click', '.add-item', function(e){
            e.preventDefault();

            let desc = $('.add-item-to-form .description').val();
            let quantity = $('.add-item-to-form .quantity').val();
            let optional = $('.add-item-to-form .optional:checked').val() ? true : false;
            let uprice = $('.add-item-to-form .unit-price').val();

            if( (desc.length > 5) & optional & ($.isNumeric(uprice) & parseInt(uprice) >= 0 ) & ($.isNumeric(quantity) & parseInt(quantity) >= 1 ) ){


                $('.table .quote-items').append(`<tr> 
                    <td> 
                        <button type='button' class='btn btn-xs btn-danger remove-item'> <i class='fa fa-trash'></i> </button> 
                        <button type='button' class='btn btn-xs btn-default edit-item'> <i class='fa fa-edit'></i> </button>
                        <span class="hidden"> 
                            <input type='hidden' name='quantity[]' value="${parseInt(quantity)}" >
                            <input type='hidden' name='uprice[]' value="${parseFloat(uprice)}" >
                            <input type='hidden' name='price[]' value="0" >
                            <input type='hidden' name='optional[]' value="1" >
                            <textarea name='description[]' class='hidden'>${desc}</textarea>
                        </span>
                    </td> 
                    <td> ${desc} </td>
                    <td> ${ (parseInt(quantity)).toLocaleString() } </td>
                    <td> <span class='quote-currency'>${currency_symbol}</span> ${ (parseFloat(uprice)).toLocaleString()} </td>
                    <td> Optional </td>

                    </tr>`);

                $('.add-item-to-form .description').val('');
                $('.add-item-to-form .quantity').val('');
                $('.add-item-to-form .unit-price').val('');

                cal($('.table .quote-items').find('tr'));


            }else if( (desc.length > 5) &  ($.isNumeric(uprice) & parseInt(uprice) >= 0 ) & ($.isNumeric(quantity) & parseInt(quantity) >= 1 ) ){

                $('.table .quote-items').append(`<tr> 
                    <td> 
                        <button type='button' class='btn btn-xs btn-danger remove-item'> <i class='fa fa-trash'></i> </button> 
                        <button type='button' class='btn btn-xs btn-default edit-item'> <i class='fa fa-edit'></i> </button>
                        <span class="hidden"> 
                            <input type='hidden' name='quantity[]' value="${parseInt(quantity)}" >
                            <input type='hidden' name='uprice[]' value="${parseFloat(uprice)}" >
                            <input type='hidden' name='price[]' value="${parseInt(quantity) * uprice}" >
                            <input type='hidden' name='optional[]' value="0" >
                            <textarea name='description[]' class='hidden'>${desc}</textarea>
                        </span>
                    </td>
                    <td> ${desc} </td>
                    <td> ${ (parseInt(quantity)).toLocaleString() } </td>
                    <td> <span class='quote-currency'>${currency_symbol}</span> ${ (parseFloat(uprice)).toLocaleString()} </td>
                    <td> <span class='quote-currency'>${currency_symbol}</span> ${ (parseInt(quantity) * uprice).toLocaleString() } </td>

                    </tr>`);

                $('.add-item-to-form .description').val('');
                $('.add-item-to-form .quantity').val('');
                $('.add-item-to-form .unit-price').val('');

                cal($('.table .quote-items').find('tr'));

               

            }else{
                let fail = new Array();
                if(desc.length < 5){
                    fail.push('Item Description must contain more than 5 Alphabet');
                }
                if(!$.isNumeric(quantity)){
                    fail.push('Quantity must be a number');
                }
                else if( parseInt(quantity) < 1 ){
                    fail.push('Quantity must be a greater than 0');
                }

                if(!$.isNumeric(uprice)){
                    fail.push('Unit Price must be a number');
                }
                else if( parseInt(uprice) < 1 ){
                    fail.push('Unit Price can not be a negative value');
                }

                printErrorMsg(fail, 8000);

            }


        });
        
        $('body').on('click', '.remove-item', function(e){

            $(this).closest('tr').remove();
            cal($('.table .quote-items').find('tr'));

        });

        $('body').on('click', "select[name='currency']", function(e){

            $('.quote-currency').text($("select[name='currency'] option:selected").attr('data-symbol') );
            currency_symbol = $("select[name='currency'] option:selected").attr('data-symbol') ;
            currency_name = $("select[name='currency'] option:selected").attr('data-title') ;
            currency_code = $("select[name='currency'] option:selected").attr('data-code') ;

        });

        $('body').on('change', ".quote-summary select[name='vat_type']", function(e){

            calVat();

        });

        $('body').on('input', ".quote-summary input[name='vat_rate']", function(e){

            calVat();
            

        });

        $('body').on('change', ".quote-summary select[name='discount_type']", function(e){

            calDiscount();
            

        });

        $('body').on('input', ".quote-summary input[name='discount_rate']", function(e){

            calDiscount();
            

        });
        

        /***  Calculate Sub Total Cost Of Items     ***/

        function cal(element){
            
            let trs_count = element.length;
            let tr = element;
            let cost = 0.0;
            let amount = parseFloat('0.00');
            amount = amount + cost;

            for(let i = 1; i <= trs_count; i++){
                cost = parseFloat( tr.find("input[name='price[]']").val() );
                if($.isNumeric(cost)){

                    tr = tr.next();
                    amount = parseFloat(amount) +parseFloat(cost);


                }
            }

            amount = parseFloat( amount.toFixed(2) );

            $('.quote-summary .sub-total').text(amount.toLocaleString());
            let discount = $('.quote-summary .discount-total').text().replace(/,/g,'');
            let gtotal = parseFloat(amount - discount).toFixed(2);
            gtotal = parseFloat(gtotal);
            $('.quote-summary .grand-total').text(gtotal.toLocaleString());
            calDiscount();
           
        }


        /***  Calculate Total Amount For VAT    ***/

        function calVat(){
            let type = $('.quote-summary .vat-type').val();
            let currency = $("select[name='currency'] option:selected").attr('data-symbol');
            if(type == 'percentage'){
                let num = parseFloat($('.quote-summary .vat-val').val());
                if( $.isNumeric( num ) & parseInt(num) >= 0 & parseInt(num) <= 100){
                    let total = $('.quote-summary .sub-total').text().replace(/,/g,'');

                    let discount = $('.quote-summary .discount-total').text().replace(/,/g,'');
                    let sdiscount = total - discount;
                    let vat = parseFloat( sdiscount * parseFloat(num / 100).toFixed(2)).toFixed(2);
                    vat = parseFloat(vat);
                $('.quote-summary .vat-total').text(vat.toLocaleString());
                let gtotal = parseFloat(sdiscount + vat).toFixed(2);
                gtotal = parseFloat(gtotal);

                $('.quote-summary .grant-total').text(gtotal.toLocaleString());

                $('.vat-val').val(num);
                $('.vat-type').val('percentage');
                
                }else{
                    $('.vat-val').val('0.0');
                    let fail = new Array();
                    
                    if(!$.isNumeric(num)){
                        fail.push('Vat Value must be a number');
                    }
                    else if( parseInt(num) < 0 || parseInt(num) > 100 ){
                        fail.push('Vat Value must be a number between 0 - 100 %');
                    }

                    printErrorMsg(fail, 8000);
                }
            }else{
                let num = parseFloat($('.quote-summary .vat-val').val());
                let total = $('.quote-summary .sub-total').text().replace(/,/g,'');
                
                if( $.isNumeric( num ) && num <= total && num >= 0){

                    let discount = $('.quote-summary .discount-total').text().replace(/,/g,'');
                    let sdiscount = total - discount;
                    let vat = parseFloat(parseFloat(num).toFixed(2));
                    $('.quote-summary .vat-total').text(vat.toLocaleString());
                    let gtotal = parseFloat(sdiscount + vat).toFixed(2);
                    gtotal = parseFloat(gtotal);

                    $('.quote-summary .grant-total').text(gtotal.toLocaleString());

                }else{
                    $('.vat-val').val('0.0');
                    let fail = new Array();

                    if(!$.isNumeric(num)){
                        fail.push('Vat Value must be a number');
                    }
                    else if( parseInt(num) < 0){
                        fail.push('Vat Value must not be less than 0 ');
                    }
                    else if( num > total ){
                        fail.push('Vat Value must not be greater than Sub Total '+currency+''+$('.quote-summary .sub-total').text() );
                    }
                    printErrorMsg(fail, 8000);
                }
            }   
        }

        /***  Calculate Amount For Discount    ***/

        function calDiscount(){

            let type = $('.quote-summary .discount-type').val();
            let currency = $("select[name='currency'] option:selected").attr('data-symbol');
            if(type == 'percentage'){
                let num = parseFloat($('.quote-summary .discount-val').val());
                if( $.isNumeric( num ) & parseInt(num) >= 0 & parseInt(num) <= 100 ){
                    let total = $('.quote-summary .sub-total').text().replace(/,/g,'');
                    
                    let discount = parseFloat( total * parseFloat(num / 100).toFixed(2));
                    let sdiscount = total - discount;
                    $('.quote-summary .discount-total').text(discount.toLocaleString());
                    let gtotal = parseFloat(sdiscount).toFixed(2);
                    gtotal = parseFloat(gtotal);
                    
                    $('.quote-summary .grant-total').text(gtotal.toLocaleString());
                    calVat();
                 
                 }else{
                    $('.discount-val').val('0.0');
                     let fail = new Array();
                    
                    if(!$.isNumeric(num)){
                        fail.push('Discount Value must be a number');
                    }
                    else if( parseInt(num) < 0 || parseInt(num) > 100 ){
                        fail.push('Discount Value must be a number between 0 - 100 %');
                    }

                    printErrorMsg(fail, 8000);

                }
            }else{
                let num = parseFloat($('.quote-summary .discount-val').val());
                let total = $('.quote-summary .sub-total').text().replace(/,/g,'');
                if( !isNaN( num ) && num <= total && num >= 0 ){

                    let sdiscount = total - num;
                    $('.quote-summary .discount-total').text(num.toLocaleString());

                    let gtotal = parseFloat(sdiscount).toFixed(2);
                    gtotal = parseFloat(gtotal);
                    $('.quote-total .grant-total').text(gtotal.toLocaleString());
                    calVat();

                }else{
                    $('.discount-val').val('0.0');
                    let fail = new Array();
                    if(!$.isNumeric(num)){
                        fail.push('Discount Value must be a number');
                    }
                    else if( parseInt(num) < 0){
                        fail.push('Discount Value must not be less than 0 ');
                    }
                    else if( num > total ){
                        fail.push('Discount Value must not be greater than Sub Total '+currency+''+$('.quote-summary .sub-total').text());
                    }
                    printErrorMsg(fail, 8000);
                }
            }   
        }

        $('.quote-items').sortable();



    </script>


@endsection

@section('extra-css')
        
    <style>

        

        .form-group-inline{
            max-width: 250px !important;
            width: 250px;
            display: inline-block !important;
            float: left;
            padding: 0px 0px 10px 10px; 
        }

       .form-btn-group-inline{
            /*clear: both;*/
            max-width: 250px !important;
            width: 150px;
            height: 68px;
            display: inline-block;
            float: left;
            padding: 10px 0px 10px 10px; 
        }


    </style>

@endsection