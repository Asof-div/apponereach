@extends('layouts.tenant_sidebar')

@section('title')
    
    GROUP CONFIGURATION

@endsection

@section('breadcrumb')

    <li><a href="{{ route('tenant.dashboard', [$tenant->domain]) }}"> Dashboard </a></li>
    <li><a href="{{ route('tenant.media-service.index', [$tenant->domain]) }}"> Media Services </a></li>
    <li><a href="{{ route('tenant.media-service.group-ring.index', [$tenant->domain]) }}"> Group Rings </a></li>
    <li class="active"> Gtoup Ring Configuration </li>

@endsection

@section('content')

   
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="panel-title">
                    <span class="h3"> &nbsp; View Group Ring  &nbsp;  <span class="text-primary pull-left">
                        <a href="{{ route('tenant.media-service.group-ring.create', [$tenant->domain]) }}" class="btn btn-default"> <i class="fa fa-plus-circle"></i> </a> </span>
                    </span> 
                    <span class="pull-right m-r-10">
                        <a href="{{ route('tenant.media-service.group-ring.index', [$tenant->domain]) }}" class="btn btn-lg btn-outline-default"> <i class="fa fa-phone"></i> Total Group Ring &nbsp; <span class="text-primary"> {{ $groups->count() }} </span> </a>
                    </span>
                </div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="panel-body" style="min-height: 400px;">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    @include('app.tenant.media-services.group_ring.partials.details')

                </div>

            </div>



        </div>
    </div>


    <div class="modal fade edit-group-ring-configuration-modal" tabindex="1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                    <h5 class="modal-title"> <span class="h4 text-primary"> EDIT GROUP RING CONFIGURATION </span> </h5> 
                </div>
                <div class="modal-body clearfix">
                    
                    @include('partials.validation')
                    @include('partials.flash_message')

                    @include('app.tenant.media-services.group_ring.partials.edit_form')
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade delete-group-ring-modal" tabindex="1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <form method="post" id="group_ring_delete_form" action="{{ route('tenant.media-service.group-ring.delete', [$tenant->domain]) }}">
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                        <h5 class="modal-title"> <span class="h4 text-primary"> DELETE GROUP RING </span> </h5> 
                    </div>
                    <div class="modal-body clearfix">
                        @include('partials.flash_message')
                        @include('partials.validation')
                        {{ csrf_field() }}
                        <p class="f-s-15"> Are you sure you want to delete this ? </p>
                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                        <div class=" clearfix m-t-10">
                            <span class="selected-number f-s-16"></span>
                        </div> 
                    </div>

                    <div class="modal-footer">
                        <div class="form-inline">
                            <div class="form-group m-r-10">
                                <button type="button" class="btn btn-warning" data-dismiss="modal"> NO </button>
                                <button type="submit" class="btn btn-primary"> YES </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('app.tenant.media-services.group_ring.modal.member')
   
@endsection


@section('extra-script')

    <script>
       
        var ring_group = { 'members' : @php echo json_encode($group->members); @endphp };

        $mn_list = $('.sidebar ul.nav > li.nav-media-services');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-group-ring').addClass('active');

        $(function(){

            $(".phone-route>span").mask("(000) 9999-9999");

        });

        
        $('#add_cug_member').on('show.bs.modal', function (event) {
            
            let button = $(event.relatedTarget);

            let numbers = <?= json_encode($numbers); ?>;
            let extens = <?= json_encode($extens); ?>;
            let available = [];
           
             $('#available_cug').html('');
             $('#phone-container').html('');

            numbers.forEach(function(element){

                if(search(element.number, 'number')){    

                    return false;
                }
                var list = document.createElement('li');
                var span = '<i class="fa fa-phone"></i>';
                span += "<span style='padding:5px;'> " + element.number +"</span>"; 
                span += "<input class='phone_value' value='"+ element.number +"' name='phone[]' type='hidden' /> ";
                span += "<input class='phone_ids' value='"+ element.id +"' name='ids[]' type='hidden' /> ";
                span += "<input class='phone_type' value='number' name='type[]' type='hidden' /> ";
                span += "<input type='checkbox' class='phone_check'>";

                list.setAttribute('class', 'phone-route');
                list.innerHTML = span;
                $('#available_cug').append(list);
                $(".phone-route>span").mask("(000) 9999-9999");


            });

            extens.forEach(function(element){
                if(search(element.number, 'sip_profile')){    

                    return false;
                }
                let list = document.createElement('li');
                let span = '<i class="fa fa-tty"></i>';
                span += "<span style='padding:5px;'> " + element.number +"</span>"; 
                span += "<input class='phone_value' value='"+ element.number +"' name='phone[]' type='hidden' /> ";
                span += "<input class='phone_ids' value='"+ element.id +"' name='ids[]' type='hidden' /> ";
                span += "<input class='phone_type' value='sip_profile' name='type[]' type='hidden' /> ";
                span += "<input type='checkbox' class='phone_check'>";

                list.setAttribute('class', 'phone-route');
                list.innerHTML = span;
                $('#available_cug').append(list);
                
                
            });
                       

        });


        $('body').on('click', '.phone_check', function(event){
            
            if($(this).prop("checked") == true){
                
                
                let number = $(this).parent('li').find('.phone_value').val();                
                let type = $(this).parent('li').find('.phone_type').val();                
                let ids = $(this).parent('li').find('.phone_ids').val();                
                let list = document.createElement('li');
                let span = '<i class="fa fa-phone"></i>';
                span += "<span style='padding:5px;'> " +number +"</span>"; 
                span += "<input class='phone_value' value='"+ number +"' name='phone[]' type='hidden' /> ";
                span += "<input class='phone_ids' value='"+ ids +"' name='ids[]' type='hidden' /> ";
                span += "<input class='phone_type' value='"+ type +"' name='type[]' type='hidden' /> ";
                span += "<button type='button' class='btn btn-xs btn-danger btn-phone-close'> <i class='fa fa-close'></i> </button>"
                $(this).parent('li').remove();

                list.setAttribute('class', 'phone-route');
                list.innerHTML = span;
                $("#phone-container").append(list);
                $(".phone-route>span").mask("(000) 9999-9999");
            }

        });

        $('body').on('click', '.btn-phone-close', function(event){
                   
            let number = $(this).parent('li').find('.phone_value').val();                
            let type = $(this).parent('li').find('.phone_type').val();                
            let ids = $(this).parent('li').find('.phone_ids').val();                
            let list = document.createElement('li');
            let span = '<i class="fa fa-phone"></i>';
            span += "<span style='padding:5px;'> " +number +"</span>"; 
            span += "<input class='phone_value' value='"+ number +"' name='phone[]' type='hidden' /> ";
            span += "<input class='phone_ids' value='"+ ids +"' name='ids[]' type='hidden' /> ";
            span += "<input class='phone_type' value='"+ type +"' name='phone[]' type='hidden' /> ";
            span += "<input type='checkbox' class='phone_check'>"
            $(this).parent('li').remove();

            list.setAttribute('class', 'phone-route');
            list.innerHTML = span;
            $("#available_cug").append(list);
            $(".phone-route>span").mask("(000) 9999-9999");

        });


        $('body').on('click', '#save_cug_changes', function(event){
                   
            
            let list = $('#phone-container>li');
            
            if(list.length < 1 ) {
                $('#errormessage').find('div').html('You can not submit empty form');
                $("#errormessage").css('display','block');
                autoSetDisplayNone( $("#errormessage"), 4000);                
                return;
            }
            list.find('.btn-phone-close').remove();
            let table_body = $('#table-members>tbody');

            Array.from(list).forEach(function(column){
                
                if(ring_group.members.length >= 7){
                    return false;
                }
                let number = $(column).find('.phone_value').val();
                let ids = $(column).find('.phone_ids').val();               
                let type = $(column).find('.phone_type').val();
                addMember(number, type);
                let icon = type == 'number' ? 'phone' : 'tty';
                let tr = "<tr><td>";
                tr += "<span style='padding:5px;'> <i class='fa fa-"+ icon +"'></i> &nbsp;"  +number +"</span>"; 
                tr += "<input class='phone_value' value='"+ number +"' name='phone[]' type='hidden' /> ";
                tr += "<input class='phone_ids' value='"+ ids +"' name='ids[]' type='hidden' /> ";
                tr += "<input class='phone_type' value='"+ type +"' name='type[]' type='hidden' /> </td> ";
                tr += "<td> <button type='button' class='btn btn-warning btn-xs delete_member' >Delete</button> </td> </tr>";

                table_body.append(tr);
            })



            $('#add_cug_member').modal('hide');
            

        });


        $('body').on('click', '.delete_member', function(event){

            let td = $(this).parent('td');
            let number = td.parent('tr').find('.phone_value').val();

            for(let i = 0; i < ring_group.members.length; i++) {
                if(ring_group.members[i].number == number) {
                    ring_group.members.splice(i, 1);
                    break;
                }
            }
            td.parent('tr').remove();


        });

        function autoSetDisplayNone(target, delay = 4000){
            target.delay(delay).slideUp(200, function() {
                $(this).css('display','none');
            });
        }
        
        function addMember(number, type){

            ring_group.members.push({'number' : number, 'type' : type});  

        }
        function onGeneratedRow(columnsResult)
        {
            rowNum = ring_group.members.length ;
            columnsResult.forEach(function(column) {                  
                var columnName = column.metadata.colName;
                viewData.employees[rowNum][columnName] = column.value; 
                ring_group.members.push({});    

            });
        }
        
        function search(number, type) {
            
            let nResult = false;
            if(ring_group.members.length > 0){
                type = type.toUpperCase();
                let match = $.map(ring_group.members, function(entry) {
                    let result = entry.number.indexOf(number) !== -1;
                    if(result) return entry; 
                });
                if(match.length > 0){
                    if(match[0].number == number) nResult = true;
                }
            }
            return  nResult;
       
        }

        $('body').on('submit', '#group_ring_update_form',  function(event){
            $('#system_overlay').removeClass('hidden');
            event.preventDefault();
            ring_group.members.length > 0 ? $('#member_value').val('not empty') : $('#member_value').val('');

            formData = new FormData(document.getElementById('group_ring_update_form'));

            url = "{{ route('tenant.media-service.group-ring.update', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = data.url;
                }, 3000);  

            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed); 

        }); 

        $('body').on('submit', '#group_ring_delete_form',  function(event){
            $('#system_overlay').removeClass('hidden');
            event.preventDefault();
            ring_group.members.length > 0 ? $('#member_value').val('not empty') : $('#member_value').val('');

            formData = new FormData(document.getElementById('group_ring_delete_form'));

            url = "{{ route('tenant.media-service.group-ring.delete', [$tenant->domain]) }}"; 

            let success = function(data){
                printFlashMsg(data.success);
                setTimeout(function(){ 
                    window.location = "{{ route('tenant.media-service.group-ring.index', [$tenant->domain]) }}";
                }, 3000);  

            }
            let failed = function(data){

            }

            ajaxCall(url, formData, success, failed); 

        }); 

        $('#table-members>tbody').sortable();
    

        $('.call-flow-order').sortable();

       

    
    </script>


@endsection

@section('extra-css')
        
    <style>


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