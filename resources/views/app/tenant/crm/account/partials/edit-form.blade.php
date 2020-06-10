
<form id="edit_account_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>
    
    <div class="form-group bg-white clearfix p-15 m-r-0 m-l-0">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset" data-dismiss="modal"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Update Account </button>
        </div>
    </div>     
    
    <div class="p-20 col-md-12 bg-white">

        {{csrf_field()}}
        <input type="hidden" name="account_id" value="{{ $account->id }}">
        <div class="col-md-6 col-sm-6">
            <div class="form-group">
                <label class="form-label col-md-4">Account Name <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <input type="text" name="name" class="form-control" value="{{ $account->name }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Address Line</label>
                <div class="col-md-8">
                    <textarea class="form-control" rows="3">{{ $account->address }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">City</label>
                <div class="col-md-8">
                    <input type="text" name="city" value="{{ $account->city }}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">State</label>
                <div class="col-md-8">
                    <input type="text" name="state" value="{{ $account->state }}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Postcode/Zip</label>
                <div class="col-md-8">
                    <input type="text" name="postcode" value="{{ $account->postcode }}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Country <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <select name="country" class="form-control">
                        @foreach($countries as $country)
                            <option value="{{ $country['name'] }}" {{ $account->country == $country['name'] ? 'selected' : ''}}>{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group clearfix p-l-15"> 
                <h4>CONTACT INFO  <span class="text-danger"> (At least one of the Email/Phone No. Must be filled)</span></h4>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Email</label>
                <div class="col-md-8">
                    <input type="text" name="email" value="{{ $account->email }}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Phone</label>
                <div class="col-md-8">
                    <input type="text" name="phone" value="{{ $account->phone }}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Website</label>
                <div class="col-md-8">
                    <input type="text" name="website" value="{{ $account->website }}" class="form-control">
                </div>
            </div>

            
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="form-group">
                <label class="form-label col-md-4">Vat Number</label>
                <div class="col-md-8">
                    <input type="text" name="vat_number" value="{{ $account->vat_number }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Type</label>
                <div class="col-md-8">
                    <select name="type" class="form-control">
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ $account->category == $type ? 'selected' : '' }} >{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Owner <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <select name="owner" class="form-control">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $account->account_manager == $user->id ? 'selected' : '' }}> {{ $user->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Source</label>
                <div class="col-md-8">
                    <select name="source" class="form-control">
                        @foreach($sources as $source)
                            <option value="{{ $source }}" {{ $account->source == $source ? 'selected' : '' }}>{{ $source }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Industry <i class="fa fa-asterisk text-danger"></i></label>
                <div class="col-md-8">
                    <select name="industry" class="form-control">
                        @foreach($industries as $industry)
                            <option value="{{ $industry->id }}" {{ $account->industry_id == $industry->id  ? 'selected' : ''}}>{{ $industry->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Currency</label>
                <div class="col-md-8">
                    <select name="currency" class="form-control">
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ $account->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
                  <div class="form-group">
                <label class="form-label col-md-4">Payment Terms</label>
                <div class="col-md-8">
                    <select name="payment_terms" class="form-control">
                        @foreach($payment_terms as $term)
                            <option value="{{ $term['name'] }}" {{ $account->payment_terms == $term['name'] ? 'selected' : '' }}>{{ $term['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
      
            <div class="form-group">
                <label class="form-label col-md-4">Notes</label>
                <div class="col-md-8">
                    <textarea class="form-control" rows="5" name="notes">{{ $account->note }}</textarea>
                </div>
            </div>
            
        </div>


    </div>

</form>

