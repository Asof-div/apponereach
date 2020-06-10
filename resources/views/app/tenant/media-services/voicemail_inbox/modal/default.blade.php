
<div class="modal fade" id="add_cug_member" tabindex="-1" role="dialog" aria-labelledby="hunt_memberLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">

            <div class="modal-header">
            <span class="h5 modal-title text-primary"> ADD DESTINATIONS </span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">

                <div class="row" style="padding: 15px 25px;">


                    <div class="alert alert-danger alert-dismissable" id="errormessage" style="display:none">
                        <button type="button" class="close close-alert"  aria-hidden="true">&times;</button>

                        <div></div>

                    </div>
                

                    <div class="col-xs-12 col-md-12 form-group">

                        <label for=""> Select Phone Numbers </label>
                        
                        <ul id="available_cug" style="padding: 3px; list-style:none; border:1px solid #eee; border-radius:3px;">

                                                    
                        </ul>

                        <div class="text-center text-success"> <i class="fa fa-arrow-down"></i></div>
                    </div>

                </div>

                <div class="row" style="padding: 15px;">
                    <ul id="phone-container" style="padding: 3px; list-style:none; border:1px solid #eee; border-radius:3px;"> </ul>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" id="save_cug_changes" class="btn btn-primary">Save changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        
        </div>
    </div>
</div>


<div class="modal fade" id="select_group_modal" tabindex="-1" role="dialog" aria-labelledby="transfer_routeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <span class="modal-title "> <span class="f-s-25"> Select Group Destination </span> </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row" style="padding: 15px 25px;">

                    @include('partials.validation')
                    @include('partials.flash_message')

                    {{csrf_field()}}
      
                    <div class="form-group clearfix">
                        <div class="m-b-15"></div>
                        <span class="f-s-20 m-10" style="border: 2px solid #51BB8D; padding: 15px; border-bottom: 0px solid white; padding-bottom: 2px;"> <i class="fa fa-users"></i> Group Ring </span>
                        
                        <div style="border: 2px solid #51BB8D; padding: 15px; z-index: 100; background-color: #fff; ">
                            @foreach($groups as $group)

                            <label class="radio-inline clearfix phone-destination" style="border-radius: 10px !important; margin: 5px; ">  
                                <span class="f-s-15 destination p-10" style="display: block;" >
                                    <input class="phone-group-dst" value="{{ 'g'.$group->id }}" name="group" type="radio" data-name="{{$group->name}}" data-members="{{$group->numbers}}" >
                                    <i class="fa fa-users"></i>
                                    <span style="padding:5px;">{{$group->name}}</span>
                                    <span style="display: block; border-bottom: 1px solid #fff; padding: 5px;">
                                         {{$group->numbers}}
                                    </span>
                                </span>
                            </label>

                            @endforeach
                        </div>
                    </div>                                

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-group-destination" data-dismiss="modal" data-value="" data-name="" data-members="" >Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        
        </div>
    </div>
</div>


<div class="modal fade" id="select_receptionist_modal" tabindex="-1" role="dialog" aria-labelledby="transfer_routeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

        <form action="" method="post" name="13" id="pilot_line_destination_form">
            <div class="modal-header">
            <span class="modal-title "> <span class="f-s-25"> Select Destination </span> </span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">

                <div class="row" style="padding: 15px 25px;">

                    @include('partials.validation')
                    @include('partials.flash_message')

                    {{csrf_field()}}
              
                    <div class="form-group clearfix">
                        <div class="m-b-15"></div>
                        <span class="f-s-20 m-10" style="border: 2px solid #51BB8D; padding: 15px; border-bottom: 0px solid white; padding-bottom: 2px;"> <i class="fa fa-th-large"></i> Virtual Receptionist </span>
                        
                        <div style="border: 2px solid #51BB8D; padding: 15px; z-index: 100; background-color: #fff; ">
                            @foreach($receptionists as $receptionist)

                            <label class="radio-inline clearfix phone-destination" style="border-radius: 10px !important; margin: 5px; ">  
                                <span class="f-s-15 destination p-10" style="display: block;" >
                                    <input class="phone-value-dst" value="{{ 'm'.$receptionist->id }}" name="destination" type="radio">
                                    <i class="fa fa-th-large"></i>
                                    <span style="padding:5px;" class="m-b-3">{{$receptionist->name}}</span>
                                    <div>
                                        @foreach($receptionist->menus as $menu)
                                            <span style="display: block; border-bottom: 1px solid #fff; padding: 5px;">
                                                PRESS {{ $menu->key }} FOR <i class="{{ $menu->menu_icon }}"></i> &nbsp; {{ $menu->destination }}
                                            </span>
                                        @endforeach
                                    </div>
                                </span>
                            </label>

                            @endforeach
                        </div>
                    </div>    


                            
                        


                </div>

            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary save-pilot-destination">Save changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </form>
        
        </div>
    </div>
</div>