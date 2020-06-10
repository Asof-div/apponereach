@extends('layouts.auth')

@section('content')
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="container">

                <div class="col-md-12 bg-white clearfix">
                    <div class="clearfix m-t-50">
                        <div class="steps-form">
                            <div class="steps-row setup-panel-2 d-flex justify-content-between">
                                <div class="steps-step disabled active">
                                    <a href="javascript:;" type="button" class="btn btn-amber btn-circle waves-effect ml-0" data-toggle="tooltip" data-placement="top" title="Plan Selection"> 1 </a>
                                    <p class="f-s-15"> Plan Selection </p>
                                </div>
                                <div class="steps-step disabled ">
                                    <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Customer Information"> 2 </a>
                                    <p class="f-s-15"> Customer Form </p>
                                </div>
                                <div class="steps-step disabled">
                                    <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Select Number"> 3 </a>
                                    <p class="f-s-15"> Number Selection </p>
                                </div>

                                <div class="steps-step disabled">
                                    <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Check-Out Order"> 4 </a>
                                    <p class="f-s-15"> Payment </p>
                                </div>

                                <div class="steps-step disabled">
                                    <a href="javascript:;" type="button" class="btn btn-blue-grey btn-circle waves-effect" data-toggle="tooltip" data-placement="top" title="Confirmation"> 5 </a>
                                    <p class="f-s-15"> Confirmation </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="H4"> TELVIDA ONEREACH SERVICE. </h4>
                        <P class="f-s-15">CRM, Call Forward, Automatated Voice Response, Follow Me, Chat</P>
                    </div>
                </div>


                @include('auth.signup.partials.plan_selection')
                

            </div>
        </div>
    </div>
@endsection

@section('extra-css')
    

@endsection

@section('extra-script')

    <script type="text/javascript">
        
        let root_url = "{{ request()->root() }}/tenant/";
        
        $('body').on('input', '.domain-input', function(){

            let domain = $(this).val();
            domain = domain.replace(/[#\/\s*$@^&%()\\?\[\]\|\{\}\~\`]/g,'_');
            domain = domain.toLowerCase();

            $('.domain-url-text').text(root_url + domain);
            $('.domain-input').val(domain);

        });

    </script>

@endsection