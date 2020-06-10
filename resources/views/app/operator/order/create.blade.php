@extends('layouts.operator_sidebar')

@section('title')
    
    CUSTOMER ORDER MANAGEMENT

@endsection

@section('breadcrumb')

    <li ><a href="{{ route('operator.dashboard') }}"> Dashboard </a></li>
    <li><a href="{{ route('operator.customer.index') }}"> Customer </a></li>
    <li class="active"> New Order </li>

@endsection

@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12 no-p">
        <div class="panel ">
            <div class="panel-heading"> 
                <div class="panel-title pt-2">
                    <span class="h3"> &nbsp; New Order  
                    </span>
                </div> 
                <span class="pull-right mr-2">
                    <a href="{{ route('operator.customer.index') }}" class="btn btn-success"> <i class="fa fa-list"></i> Customer List </a>
                </span>

            </div>

            <div class="panel-body" style="min-height: 400px;">

                <hr class="horizonal-line-thick" />

                <div class="col-md-12 col-sm-12 col-xs-12 ">

                    @include('app.operator.order.partials.form')

                </div>

            </div>



        </div>
    </div>


@endsection


@section('extra-script')    

    <script src="{{ asset('assets/plugins/pricing-tables/js/main.js') }}"></script>
    <script>
       
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.customer');
        $mn_list.addClass('active open');
        $mn_list.find('.sub-menu > .order').addClass('active');


        $('body').on('click', '.add-addon-item', function(){
            let table = $('.addon-items').parent();
            let selectedproduct = $('.addon-product').find(":selected");
            let product = $('.addon-product').val();
            let price = selectedproduct.attr('data-price');
            let qty = $('.addon-qty').val();
            if($.isNumeric(qty)){

                if($('.addon-items').find(`tr.${product}`).exists()){
                    $('.addon-items').find(`tr.${product}`).remove();
                    $('.addon-items').append(`
                        <tr class="${product}">
                            <td> ${selectedproduct.attr('data-title')} </td>
                            <td> ${qty} </td>
                            <td> 1 Month </td>
                            <td> ${(price * qty).toLocaleString()} </td>
                            <td> ${(price * qty).toLocaleString()} </td>
                            <td> 
                                <span class="text-danger remove-item">&#10007;</span> 
                                <span> 
                                    <input type='hidden' name='product[]' value='${product}' /> 
                                    <input type='hidden' name='qty[]' value='${qty}' />
                                    <input type='hidden' name='price[]' value='${price}' />
                                    <input type='hidden' name='amount[]' value='${price * qty}' />

                                </span> 
                            </td>
                        </tr>
                    `);
                    calculate(table);

                }else{
                    $('.addon-items').append(`
                        <tr class="${product}">
                            <td> ${selectedproduct.attr('data-title')} </td>
                            <td> ${qty} </td>
                            <td> 1 Month </td>
                            <td> ${(price * qty).toLocaleString()} </td>
                            <td> ${(price * qty).toLocaleString()} </td>
                            <td> 
                                <span class="text-danger remove-item">&#10007;</span> 
                                <span> 
                                    <input type='hidden' name='product[]' value='${product}' /> 
                                    <input type='hidden' name='qty[]' value='${qty}' />
                                    <input type='hidden' name='price[]' value='${price}' />
                                    <input type='hidden' name='amount[]' value='${price * qty}' />
                                     
                                </span>
                            </td>
                        </tr>
                    `);
                    calculate(table);

                }
            }else{

                printErrorMsg(['Quantity is not a Number']);
            }

        });
        $('body').on('submit', '#msisdn_order_form', function(e){
            e.preventDefault();      
    
            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true);
            let formobject = $(this);
            let length = formobject.find('.addon-items tr').length;
            if(length > 0){

                $('#system_overlay').removeClass('hidden');

                formobject.find('input[name="products"]').val(length);

                let form = document.getElementById('msisdn_order_form');
                formData = new FormData(form);

                url = "{{ route('operator.customer.billing.order.store', [$customer->id]) }}"; 

                let success = function(data){
                    printFlashMsg(data.success);
                    setTimeout(function(){ 

                        window.location = data.url;
                    }, 3000);  
                    $('.modal').hide();
              
                    form.reset();
                    button.attr('disabled', true);
                    
                }
                let failed = function(data){

                    button.attr('disabled', false);
                    $('#system_overlay').addClass('hidden');
                    printErrorMsg(data.error);
                }

                ajaxCall(url, formData, success, failed);  
                

            }else{
                button.attr('disabled', false);

                printErrorMsg(['No product added']);
            }


        });

        $('body').on('click', '.remove-item', function(){

            let tbody = $(this).closest('tbody');
            let table = tbody.parent('table');
            $(this).closest('tr').remove();
            calculate(table);


            
        });

        $('body').on('submit', '#upgrade_order_form', function(e){
            e.preventDefault();      
    
            let button = $(this).find('button[type="submit"]');
            button.attr('disabled', true);
            let formobject = $(this);
            if( $('input[name="plan"]:checked').val() != undefined ){

                $('#system_overlay').removeClass('hidden');

                let form = document.getElementById('upgrade_order_form');
                formData = new FormData(form);

                url = "{{ route('operator.customer.billing.order.store', [$customer->id]) }}"; 

                let success = function(data){
                    printFlashMsg(data.success);
                    setTimeout(function(){ 

                        window.location = data.url;
                    }, 3000);  
              
                    form.reset();
                    button.attr('disabled', true);
                    
                }
                let failed = function(data){

                    button.attr('disabled', false);
                    $('#system_overlay').addClass('hidden');
                }

                ajaxCall(url, formData, success, failed);  
                

            }else{
                button.attr('disabled', false);

                printErrorMsg(['You must select a package you wish to upgrade to. ']);
            }


        });


        function calculate(table){
            let element = table.find('tbody tr');
            let trs_count = element.length;
            let tr = element;
            let cost = 0.0;
            let amount = parseFloat('0.00');
            amount = amount + cost;
            for(let i = 1; i <= trs_count; i++){
                cost = parseFloat( tr.find("input[name='amount[]']").val() );
                if(!isNaN(cost)){

                    tr = tr.next();
                    amount = parseFloat(amount) +parseFloat(cost);


                }
            }

            amount = parseFloat( amount.toFixed(2) );

            // var discount = $('.table-sum').find('tr:nth-child(2) > th:nth-child(2) > span:nth-child(1)').text().replace(/,/g,'');
            table.find('tfoot tr th:nth-child(2) ').text(amount.toLocaleString());
            table.find('tfoot tr th:nth-child(3) ').text(amount.toLocaleString());
            // calDiscount();
        
        }

    </script>


@endsection

@section('extra-css')

    <link href="{{ asset('assets/plugins/pricing-tables/css/style.css') }}" rel="stylesheet" type="text/css"/> 
    <style>
       
        .cd-pricing-wrapper{font-weight: bold;}
        .cd-pricing-wrapper > li::before{
            background-color: #fff !important;
        }
        ul.nav.nav-pills li.active a{
            background: #51BB8D !important;
            color: #fff !important;
            font-weight: bold;
        }
        .p-l-15{
            padding-left: 15px !important;
        }
        .m-5{
            margin:5px;
        }
        .border{
            border: 1px black solid;
        }
        .display-no{
            display: none;

        }
        span.dropdown > ul > li:hover, span.dropdown > ul > li:hover a:hover{
            background-color: #b4c404;
        }
        

    </style>

@endsection