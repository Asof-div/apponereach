@extends('layouts.tenant_sidebar')

@section('title')

    ACCOUNT DEACTIVATION

@endsection

@section('breadcrumb')
    
    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}">Dashboard</a></li>
    <li class="active"> DEACTIVATE </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12 p-0">
        <div class="panel panel-default">
            <div class="panel-heading"> 
            
                <div class="panel-title"> 
                    <span class="h3"> Deactivation Form &nbsp; <span class="text-primary">  </span> </span> 
                </div> 
                <hr class="horizonal-line-thick" />
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12 bg-silver" >

                    <form>
                        <div class="form-group">
                            
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

        
@endsection

@section('extra-script')
 
    <script type="text/javascript">

    </script>

@endsection