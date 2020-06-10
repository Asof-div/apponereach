<form id="rate_form" name="13" class="form-horizontal" method="POST" action="{{ route('operator.call.rate.update') }}">
    {{ csrf_field() }}


    <div class="p-20 form-group bg-white m-b-15 clearfix">
        <div class="clearfix m-b-sm m-l-sm"> <span class="h4 text-success"> Call Rate </span> </div>
        {{csrf_field()}}
        <input type="hidden" name="id" value="{{ $rate->id }}" >
        <div class="col-md-6 col-sm-12 m-auto ">

            <div class="form-group">
                <label class="form-label col-md-4"> Phonecode </label>
                <div class="col-md-8">
                    <select class="form-control" name="phonecode">
                        @foreach($countries as $country)

                            <option {{ $rate->country_id == $country->id ? 'selected' : '' }}
                                value="{{ $country->phonecode }}">{{ $country->phonecode .' - '. $country->iso3 }}</option>

                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Rate </label>
                <div class="col-md-8">
                    <input type="text" name="rate" class="form-control" value="{{ $rate->rate }}">
                </div>
            </div>

        </div>

    </div>

    <div class="col-md-12 bg-white m-t-20 clearfix p-15 m-r-0 m-l-0 clearfix">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Update Rate </button>
        </div>
    </div>

</form>

