
<form id="msisdn_form" name="13" class="form-horizontal" method="POST" action="{{ route('operator.pilot_number.store') }}">
    {{ csrf_field() }}
  
    
    <div class="p-20 form-group bg-white m-b-15 clearfix">
        <div class="clearfix m-b-sm m-l-sm"> <span class="h4 text-success"> MSISDN Details </span> </div>
        {{csrf_field()}}
        
        <div class="col-md-6 col-sm-12 m-auto ">


            <div class="form-group">
                <label class="form-label col-md-4">Number </label>
                <div class="col-md-8">
                    <input type="text" name="number" class="form-control" required="required" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Serial No </label>
                <div class="col-md-8">
                    <input type="text" name="serial_no" class="form-control" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Type </label>
                <div class="col-md-8">
                    <select class="form-control" name="type">
                        @foreach($lineTypes as $linetype)

                            <option value="{{ $linetype->id }}">{{ $linetype->label }}</option>
    
                        @endforeach
                    </select>
                </div>
            </div>
            
            
        </div>

    </div>

    <div class="col-md-12 bg-white m-t-20 clearfix p-15 m-r-0 m-l-0 clearfix">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Submit </button>
        </div>
    </div>   

</form>


<form id="import_form" name="13" class="form-horizontal" method="POST" action="{{ route('operator.pilot_number.import') }}" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row clearfix">
        <div style="margin-top: 70px !important; clear: both; display: block;"></div>
        <hr class="horizonal-line-thick">
        <div class="m-b-20"></div>
    </div>


    @if(Session::has('import_status') && count(session()->get('import_status')) > 0) 

        @php
            $import_status = Session::get('import_status');
            $import_success = array_filter($import_status, function($v, $k) {
                return $v['status'] == 'success';
            }, ARRAY_FILTER_USE_BOTH);

            $import_failed = array_filter($import_status, function($v, $k) {
                return $v['status'] == 'failed';
            }, ARRAY_FILTER_USE_BOTH);

        @endphp
        
        @if(count($import_success))
        
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-import-success"> <i class="fa fa-expand"></i> Successfully Imported  ({{ count($import_success) }})</a>
                        </h4>
                

                    </div>
                    <div id="collapse-import-success" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="table-responsive">

                                @foreach($import_success as $status)
                 
                                    <li class="list-group-item text-success"> {{ $status['msg'] }}</li>  
                                 
                                @endforeach

                            </div>
                        </div>
                    </div>
                                        
                </div>

                
            </div>

        @endif

        @if(count($import_failed))
        
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-import-failed"> <i class="fa fa-expand"></i> Failed To Import  ({{ count($import_failed) }}) </a>
                        </h4>
                

                    </div>
                    <div id="collapse-import-failed" class="panel-collapse collapse">
                        <div class="panel-body">

                            <div class="table-responsive">

                                @foreach($import_failed as $status)
                 
                                    <li class="list-group-item text-danger"> {{ $status['msg'] }}</li>  
                                 
                                @endforeach
                                
                            </div>
                        </div>
                    </div>
                                        
                </div>

            </div>

        @endif


    @endif    
    <div class="p-20 col-md-12 bg-white m-b-15">
        <div class="clearfix m-b-sm m-l-sm"> <span class="h4 text-success"> MSISDN File </span> </div>
        {{csrf_field()}}
        
        <div class="col-md-6 col-sm-12 m-auto ">

            <div class="form-group">
                <label class="form-label col-md-4">Number </label>
                <div class="col-md-8">
                    <input type="file" name="file" class="form-control" required="required" >
                </div>
            </div>
            
        </div>

    </div>

    <div class="col-md-12 m-t-20 bg-white clearfix p-15 m-r-0 m-l-0">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Upload Data </button>

            <div class="col-md-12 bg-danger clearfix m-t-30 p-15 m-r-0 m-l-0">
                <p class="f-s-16 p-10"> You do not have the permission to perform this action. Please Contact you Administrator.</p>
            </div>
        </div>
    </div>
</form>
