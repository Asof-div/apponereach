@extends('layouts.tenant_sidebar')

@section('content')

       
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading"> Provisioning </div>

                <div class="panel-body" style="min-height: 400px;">


                    <button id="load_provisioning_page" class="btn btn-primary"> Add New Provisioning </button>
                    <button id="load_provisioning_list" class="btn btn-primary hidden"> List Provisionings </button>


                    <br>

                        <hr style="background-color: #51BB8D; height: 3px;">

                    <br>


                    <div class="col-md-12 col-sm-12 col-xs-12" id="provisionings_container">

                        @include('app.tenant.provisioning.partials.form')

                    </div>

                </div>
            </div>
        </div>

        



@endsection


@section('extra-script')

    <script>
        var attempt = 20;

        var delay_option = ['5', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55', '60', '65', '70', '75', '80', '85', '90', '95', '100', '105', '110', '115', '120', '125', '130', '135', '140', '145', '150', '155', '160', '165', '170', '175', '180', '185', '190', '195', '200']; 	

        select =document.getElementById('transfer_delay');
        select.innerHTML ='';
        for (var prop in delay_option) {
            var option = document.createElement('option');
            option.innerHTML = delay_option[prop]+ " Seconds";
            option.value = delay_option[prop];
            
            select.append(option)
        }

        $(function(){

            $(".phone-route>span").mask("(000) 9999-9999");


        });

        $('body').on('click','#load_hunt_group_page', function(event){
            $("#hunt_groups_container").load("{{url($tenant->domain, 'hunt-group-form')}}");
            $(this).addClass('hidden');
            $("#load_hunt_group_list").removeClass('hidden');
        });



        $('body').on('submit', '#hunt_group_form_update',  function(event){
            event.preventDefault();

            formData = new FormData(document.getElementById('hunt_group_form_update'));

            url = "{{ url($tenant->domain, 'hunt-group-update') }}"; 

            $.ajax({
                url : url,
                type: "POST",
                data : formData,
                processData: false,
                contentType: false,
                success: function(data)
                {
                    if($.isEmptyObject(data.error)){
                        
                        printFlashMsg(data.success);
                        
                        
                    }else{
        
                        printErrorMsg(data.error);
        
                    }	
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('error');
                }

            });

        });
        

    
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



       .control-label{
            text-align : right;
            padding: 15px 15px 15px 25px;
        }

        .phone-route{
            padding: 7px;
            margin: 7px;
            border-radius: 4px;
            background: #f3f3f4;
            display: inline-block;
        }

    </style>

@endsection