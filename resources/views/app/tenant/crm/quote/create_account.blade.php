@extends('layouts.tenant_sidebar')

@section('title')
    
    Quote

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.crm.index', [$tenant->domain]) }}"> CRM </a></li>
    <li><a href="{{ route('tenant.crm.quote.index', [$tenant->domain]) }}"> Quote </a></li>
    <li class="active"> New Quote </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">

            <div class="panel-body" style="min-height: 400px;">

                @include('app.tenant.crm.quote.partials.account_form')

            </div>
        </div>
    </div>



@endsection


@section('extra-script')
    <script type="text/javascript" src="{{ URL::to('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script>
       
        $mn_list = $('.sidebar ul.nav > li.nav-crm');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-quote').addClass('active');
        let accounts = <?= json_encode($accounts); ?>;
        let account = <?= json_encode($account); ?>;
        let currency = account.currency;

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
    

        $('body').on('submit', '#edit_quote_form',  function(event){
            event.preventDefault();
            let form = document.getElementById('edit_quote_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.quote.store', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.quote.edit', [$tenant->domain, $quote->id]) }}";
                }, 5000);  

                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });


        $('body').on('submit', '#delete_quote_form',  function(event){
            $('.modal').hide();
            event.preventDefault();
            let form = document.getElementById('delete_quote_form');
            let formData = new FormData(form);

            url = "{{ route('tenant.crm.quote.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.quote.index', [$tenant->domain]) }}";
                }, 5000);  

                form.reset();
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('body').on('click', '.change-status',  function(event){
            event.preventDefault();
            let _token = "{{ csrf_token() }}"
            let quote_id = $(this).attr('data-quote_id');
            let status = $(this).attr('data-status');
            let formData = new FormData();
            formData.append('_token', _token);
            formData.append('quote_id', quote_id);
            formData.append('status', status);

            url = "{{ route('tenant.crm.quote.status', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.crm.quote.edit', [$tenant->domain, $quote->id]) }}";
                }, 5000);  
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('body').on('click', '.convert-quote',  function(event){
            event.preventDefault();
            let _token = "{{ csrf_token() }}"
            let quote_id = $(this).attr('data-quote_id');
            let formData = new FormData();
            formData.append('_token', _token);
            formData.append('quote_id', quote_id);

            url = "{{ route('tenant.crm.invoice.convert', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                }, 5000);  
                
            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed);  

        });

        $('body').on('click', '.add-item', function(e){
            e.preventDefault();

            let name = $('.add-item-to-form .item_name').val();
            let desc = $('.add-item-to-form .description').val();
            let quantity = $('.add-item-to-form .quantity').val();
            let optional = $('.add-item-to-form .optional:checked').val() ? true : false;
            let uprice = $('.add-item-to-form .unit-price').val();

            $('.quote-currency').text($("select[name='currency'] option:selected").attr('data-symbol') );

            if( (desc.length > 5) & optional & ($.isNumeric(uprice) & parseInt(uprice) >= 0 ) & ($.isNumeric(quantity) & parseInt(quantity) >= 1 ) ){


                $('.table .quote-items').append(`<tr> 
                    <td>  
                        <button type='button' class='btn btn-xs btn-hover'> <i class='fa fa-arrows-v'></i> </button>
                        <span class="hidden"> 
                            <input type='hidden' name='name[]' value="${name}" >
                            <input type='hidden' name='quantity[]' value="${parseInt(quantity)}" >
                            <input type='hidden' name='uprice[]' value="${parseFloat(uprice)}" >
                            <input type='hidden' name='price[]' value="0" >
                            <textarea name='description[]' class='hidden'>${desc}</textarea>
                        </span>
                    </td> 
                    <td> ${name} </td>
                    <td> ${desc} </td>
                    <td> ${ (parseInt(quantity)).toLocaleString() } </td>
                    <td> <span class='quote-currency'></span> ${ (parseFloat(uprice)).toLocaleString()} </td>
                    <td> Optional </td>
                    <td><button type='button' class='btn btn-xs btn-danger remove-item btn-hover'> <i class='fa fa-minus'></i> </button>
                    </td>
                    </tr>`);

                $('.add-item-to-form .item_name').val('');
                $('.add-item-to-form .description').val('');
                $('.add-item-to-form .quantity').val('');
                $('.add-item-to-form .unit-price').val('');

                cal($('.table .quote-items').find('tr'));

           

            }else if( (desc.length > 5) &  ($.isNumeric(uprice) & parseInt(uprice) >= 0 ) & ($.isNumeric(quantity) & parseInt(quantity) >= 1 ) ){

                $('.table .quote-items').append(`<tr> 
                    <td> 
                        <button type='button' class='btn btn-xs btn-hover'> <i class='fa fa-arrows-v'></i> </button>
                        <span class="hidden"> 
                            <input type='hidden' name='name[]' value="${name}" >
                            <input type='hidden' name='quantity[]' value="${parseInt(quantity)}" >
                            <input type='hidden' name='uprice[]' value="${parseFloat(uprice)}" >
                            <input type='hidden' name='price[]' value="${parseInt(quantity) * uprice}" >
                            <textarea name='description[]' class='hidden'>${desc}</textarea>
                        </span>
                    </td>
                    <td> ${name} </td>
                    <td> ${desc} </td>
                    <td> ${ (parseInt(quantity)).toLocaleString() } </td>
                    <td> <span class='quote-currency'></span> ${ (parseFloat(uprice)).toLocaleString()} </td>
                    <td> <span class='quote-currency'></span> ${ (parseInt(quantity) * uprice).toLocaleString() } </td>

                    <td> <button type='button' class='btn btn-xs btn-danger remove-item btn-hover'> <i class='fa fa-minus'></i> </button>
                    </td>
                    </tr>`);

                $('.add-item-to-form .item_name').val('');
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

            if(currency){
                $('.quote-currency').html(currency.symbol);
                $("input[name='currency_id']").val(currency.id);
            }


        });
        
        $('body').on('click', '.remove-item', function(e){

            $(this).closest('tr').remove();
            cal($('.table .quote-items').find('tr'));

        });

        $('body').on('click', "select[name='account']", function(e){
            let account_id = $(this).val();
            let match = $.map(accounts, function(entry) {
                let result = entry.id == account_id;
                if(result) return entry;

                return null; 
            });

            if(match.length < 1){
                currency = null;
                contacts = [];

                $('.quote-currency').html('');
                $('.payment-terms').val('');
                $("input[name='currency_id']").val("");
                $('.contact-list').empty();
                
            }else{
                currency = match[0].currency;
                contacts = match[0].contacts;

                $('.quote-currency').html(currency.symbol);
                $("input[name='currency_id']").val(currency.id);
                $('.payment-terms').val(match[0].payment_terms);
                $('.contact-list').empty();
                contacts.forEach(function(element){
                    $('.contact-list').append(`<div> <label><input name='contacts[]' value="${element.id}" type='checkbox' > 
                            ${element.name} -- ${element.email} 
                            </label> </div>`);
                        

                });
            }
        });

        $('body').on('change', "input[name='deposit']", function(e){
        
            cal($('.table .quote-items').find('tr'));

        });

        $('body').on('change', ".quote-summary select[name='vat_type']", function(e){

            calVat();

        });

        $('body').on('change', ".quote-summary input[name='vat_rate']", function(e){

            calVat();


        });

        $('body').on('change', ".quote-summary select[name='discount_type']", function(e){

            calDiscount();

        });

        $('body').on('change', ".quote-summary input[name='discount_rate']", function(e){

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
                calBalance(gtotal);

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

        function calBalance(gtotal){
            deposit = $("input[name='deposit']").val();

            if( $.isNumeric(deposit) & ( parseInt(deposit) <= gtotal) ){
                deposit = parseFloat(deposit);
                balance = gtotal - deposit;
                $('.quote-summary .balance-due').text(balance.toLocaleString());
                $('.quote-summary .deposit-total').text(deposit.toLocaleString());
                if(parseInt(deposit) > 0){
                    $(".partial-payment").removeClass('hidden');
                }else{
                    $(".partial-payment").addClass('hidden');
                }
            }else{
                $("input[name='deposit']").val('0.0');                
                $('.quote-summary .deposit-total').text('0.00');
                let fail = new Array();
                if($.isNumeric(deposit)){
                    fail.push('Deposit must be a number');
                }
                if( parseInt(deposit) > gtotal ){
                    fail.push('Deposit can not be greater than Grand total');
                }
                $(".partial-payment").addClass('hidden');

                printErrorMsg(fail, 5000);
            }
        }

        $('.quote-items').sortable();
        
        function search(number) {
            
            let nResult = false;
            if(accounts.length > 0){
                type = type.toUpperCase();
                let match = $.map(accounts, function(entry) {
                    let result = entry.id == number;
                    if(result) return entry; 
                });
                if(match.length > 0){
                    if(match[0].number == number) nResult = true;
                }
            }
            return  nResult;
       
        }





    </script>


@endsection

@section('extra-css')
        
    <style>

        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }

        .switch input {display:none;}

        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: #2196F3;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
        border-radius: 34px;
        }

        .slider.round:before {
        border-radius: 50%;
        }

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