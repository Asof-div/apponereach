<form id="contact_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

	 <div class="form-group bg-white clearfix p-15 m-r-0 m-l-0 m-t-15">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Save Contact </button>
        </div>
    </div>     
    
    <div class="p-20 col-md-12 bg-white m-b-15">

        {{csrf_field()}}
        
        <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
            <input type="hidden" name="contact_id" value="{{ $contact->id }}">
            <input type="hidden" name="number" value="{{ $contact->number }}">
            <div class="form-group">
                <label class="form-label col-md-4">Account <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    @if($account == null)
                        <select name="account_id" class="form-control">
                            @foreach($accounts as $acount)
                                <option value="{{ $acount->id }}"> {{ $acount->name }} </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="account_id" value="{{ $account->id }}">
                        <span class="f-s-15"> <i class="fa fa-building"></i> {{ $account->name }} </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Name <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <input type="text" name="name" value="{{ $contact->name }}" class="form-control" required="required">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Job Role <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <input type="text" name="title" value="{{ $contact->title }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                 <h5> CONTACT INFO <span class="text-danger"> (At least one of the Email/Phone No. Must be filled) </span> </h5>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Phone</label>
                <div class="col-md-8">
                    <div class="my-group">
                        <select class="form-control" name="country_code">
                            <option value="">Select Country Code </option>
                            @foreach($countries as $country)
                                <option {{ $contact->country_code == $country['code'] ? 'selected' : ''  }} value="{{ $country['code'] }}">{{ $country['name'] .' - '. $country['code'] }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="phone" value="{{ $contact->phone }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Email </label>
                <div class="col-md-8">
                    <input type="text" name="email" value="{{ $contact->email }}" class="form-control">
                </div>
            </div>


            
        </div>


    </div>

	
</form>