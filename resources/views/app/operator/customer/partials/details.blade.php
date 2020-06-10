<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-theme" role="tablist">
        <li role="presentation" class="active"><a href="#tab5" aria-controls="home" role="tab" data-toggle="tab">CUSTOMER INFO </a></li>
        @if($customer->activated == 1 &&  strtolower($customer->status) != 'registration' )
        <li role="presentation"><a href="#tab7" aria-controls="messages" role="tab" data-toggle="tab">PILOT NUMBER</a></li>
        <li role="presentation"><a href="#tab8" aria-controls="messages" role="tab" data-toggle="tab">USERS</a></li>
        <li role="presentation"><a href="#tab9" aria-controls="messages" role="tab" data-toggle="tab">SUBSCRIPTION</a></li>
        @endif
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="tab5">
            <div class="col-md-12 clearfix">
                <div>
                    <span class="h3">Customer Information </span>
                    <span class="pull-right">
                        @if($customer->editable() && $customer->activated == 0 && strtolower($customer->status) == 'registration')
                            <a class="btn btn-primary pull-right" href="{{ route('operator.customer.registration', [$customer->id]) }}"> Continue Registration </a>
                        @endif
                        @if($customer->editable() && $customer->activated == 1)
                            <a class="btn " href="{{ route('operator.customer.edit', [$customer->id]) }}"> <i class="fa fa-edit"></i> Edit Customer Info </a>
                        @endif
                        @if ( Gate::check('owns.customer') || Gate::check('admin.access') )
                            <span style="display: inline-block;">
                                <span class="dropdown">
                                    <button class="btn btn-success" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-exchange"></i> Change Status
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        @if( strtolower($customer->status) !== 'registration' &&  strtolower($customer->status) !== 'activated' &&  strtolower($customer->status) !== 'leave')
                                            <li ><a href="javascript:;" data-toggle="modal" data-target='#renew_customer_modal' ><i class="fa fa-folder-open-o"></i> Activate </a></li>
                                        @endif
                                        @if( strtolower($customer->status) !== 'registration' &&  strtolower($customer->status) !== 'suspended' &&  strtolower($customer->status) !== 'leave')
                                            <li ><a href="javascript:;" data-toggle="modal" data-target='#suspend_customer_modal'><i class="fa fa-times-rectangle"></i> Suspend </a></li>
                                        @endif
                                        @if( strtolower($customer->status) == 'leave')
                                            <li ><a href="javascript:;" data-toggle="modal" data-target='#revive_customer_modal'><i class="fa fa-times-rectangle"></i> Revive Account </a></li>
                                        @endif
                                    </ul>
                                </span>
                            </span>
                        @endif
                    </span>
                </div>
                <hr class="horizonal-line-thick">
                <div class="form-group text-center">
                    <img id="logo_img" class="img-rounded" src="{{URL::to( $customer->info->logo)}}" style="width: 100px; height: 100px;" alt="Logo">
                </div>

            </div>
            <div class="col-md-6 col-sm-12">

                <div class="table-responsive">
                    <table class="table table-condensed table-striped">

                        <tr>
                            <th>ID Type</th>
                            <td>{{$customer->info->id_type}}</td>
                        </tr>
                        <tr>
                            <th>Customer Category</th>
                            <td>{{$customer->info->customer_category}}</td>
                        </tr>
                        <tr>
                            <th>Customer Type</th>
                            <td>{{$customer->info->customer_type}}</td>
                        </tr>
                        <tr>
                            <th>Corporation Name</th>
                            <td>{{$customer->info->corporation_name}}</td>
                        </tr>
                        <tr>
                            <th>Corporation Type</th>
                            <td>{{$customer->info->corporation_type}}</td>
                        </tr>
                        <tr>
                            <th>Industry</th>
                            <td>{{$customer->info->industry}}</td>
                        </tr>
                        <tr>
                            <th>Registered Date </th>
                            <td>{{ $customer->info->registration_date ? $customer->info->registration_date->format('D d, M Y : H:i') : '' }}</td>
                        </tr>
                        <tr>
                            <th>Language</th>
                            <td>{{$customer->info->language}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <th class="clearfix">{{$customer->activation}} </th>
                        </tr>
                        <tr>
                            <th>Last Update At</th>
                            <th> {{ $customer->info->updated_at ? $customer->info->updated_at->format('D d, M Y : H:i') : '' }} </td>
                        </tr>



                    </table>

                </div>

            </div>

            <div class="col-md-6 col-sm-12">
                <div class="table-responsive">

                    <table class="table table-condensed table-striped">

                        <tr>
                            <th>ID No.</th>
                            <td>{{ $customer->info->id_no }}</td>
                        </tr>
                        <tr>
                            <th>Customer Sub Catagory</th>
                            <td>{{ $customer->info->customer_sub_category }}</td>
                        </tr>
                        <tr>
                            <th>Customer Grade</th>
                            <td>{{ $customer->info->customer_grade }}</td>
                        </tr>
                        <tr>
                            <th>Corporation Short Name</th>
                            <td>
                                {{ $customer->info->corporation_short_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>Corporation Link</th>
                            <td> {{ $customer->domain }} </td>
                        </tr>
                        <tr>
                            <th> Size Level </th>
                            <td>
                                {{ $customer->info->size_level }}
                            </td>
                        </tr>

                        <tr>
                            <th> Register Capital</th>
                            <td>
                                {{ $customer->info->register_capital }}
                            </td>
                        </tr>
                        <tr>
                            <th> Nationality</th>
                            <td>
                                {{ $customer->info->nationality }}
                            </td>
                        </tr>

                        <tr>
                            <th>Payment Method.</th>
                            <td>{{ strtoupper($customer->info->billing_method) }}</td>
                        </tr>

                        @if($customer->editable())
                        <tr>
                            <th>Auto Renew</th>
                            <td>{{ $customer->auto_rebill ? 'YES' : 'NO' }}</td>
                        </tr>
                        @endif


                    </table>

                </div>
            </div>

            <div class="col-md-12 clearfix">

                <div class="m-b-0"> <span class="h3">Contact Information </span></div>
                <hr class="horizonal-line-thick">
            </div>

            <div class="col-md-6 col-sm-12">

                <div class="table-responsive">
                    <table class="table table-condensed table-striped">

                        <tr>
                            <th>Email </th>
                            <td>{{$customer->info->email}}</td>
                        </tr>
                        <tr>
                            <th>First Name</th>
                            <td>{{$customer->info->firstname}}</td>
                        </tr>
                        <tr>
                            <th>Last Name</th>
                            <td>{{$customer->info->lastname}}</td>
                        </tr>
                        <tr>
                            <th>Home No.</th>
                            <td>{{$customer->info->home_no}}</td>
                        </tr>
                        <tr>
                            <th>Mobile No.</th>
                            <td>{{$customer->info->mobile_no}}</td>
                        </tr>

                    </table>

                </div>

            </div>

            <div class="col-md-6 col-sm-12">
                <div class="table-responsive">

                    <table class="table table-condensed table-striped">

                        <tr>
                            <th>Middle/Father Name</th>
                            <td>{{ $customer->info->middlename }}</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td>{{ $customer->info->title }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $customer->info->address }}</td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td>
                                {{ $customer->info->state }}
                            </td>
                        </tr>
                        <tr>
                            <th>Office No.</th>
                            <td> {{ $customer->info->office_no }} </td>
                        </tr>
                        <tr>
                            <th> Fax No. </th>
                            <td>
                                {{ $customer->info->fax_no }}
                            </td>
                        </tr>

                    </table>

                </div>
            </div>

            <div class="col-md-12">
                @if( Gate::check('owns.customer') || Gate::check('admin.access') )
                    <button class="btn btn-danger pull-right" type="button" data-toggle="modal" data-target='#delete_customer_modal' > <i class="fa fa-trash"></i> DELETE ACCOUNT </button>
                @endif
            </div>

        </div>


        @if($customer->activated == 1 &&  strtolower($customer->status) != 'registration' )
        <div role="tabpanel" class="tab-pane fade" id="tab7">
            <div class="table-responsive">
                <h3>Pilot Numbers</h3>
                <table class="table table-striped table-hover">

                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Number</th>
                            <th>Type</th>
                            <th>Provisioning Status </th>
                            <th>Configuration Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pilot_lines as $index =>  $pilot_line)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <th>{{ $pilot_line->number }}</th>
                                <td>{{ $pilot_line->type }}</td>
                                <td>{{ $pilot_line->actived_for_service ? 'YES' : 'NO' }}</td>
                                <td>{{ $pilot_line->status ? 'YES' : 'NO' }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab8">
            <div class="table-responsive">

                <h3> USERS ({{ $customer->users->count() }})</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Email</th>
                            <th>Firstname</th>
                            <th>Lastname </th>
                            <th>Credit Limit </th>
                            <th>Used Credit</th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customer->users as $index =>  $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <th>{{ $user->email }}</th>
                                <td>{{ $user->firstname }}</td>
                                <td>{{ $user->lastname }}</td>
                                <td>{{ $user->credit_limit }}</td>
                                <td>{{ $user->used_credit }}</td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab9">


            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>PERIOD</th>
                        <td>{{ $subscription->start_time->format('Y F') }}
                            <span class="pull-right">
                                <a href="{{ route('operator.subscription.show', [$subscription->id]) }}" class="btn btn-success"> <i class="fa fa-eye-slash"></i> DETAILS </a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>START DATE</th>
                        <td>{{ $subscription->start_time->format('d M Y') }}

                        </td>
                    </tr>
                    <tr>
                        <th>EXPIRATION DATE</th>
                        <td>{{ $subscription->end_time->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>CUSTOMER</th>
                        <td>{{ $subscription->tenant->name }}</td>
                    </tr>
                    <tr>
                        <th>BILLING METHOD</th>
                        <td>{{ strtoupper($subscription->billing_method) }}</td>
                    </tr>
                    <tr>
                        <th>DURATION</th>
                        <td>{{ $subscription->duration .' DAYS' }}</td>
                    </tr>
                    <tr>
                        <th>PILOT LINE</th>
                        <td>{{ $subscription->pilot_line }}</td>
                    </tr>
                    <tr>
                        <th>PLAN</th>
                        <td>{{ strtoupper($subscription->package->name) }}</td>
                    </tr>
                    <tr>
                        <th>DESCRIPTION</th>
                        <td>{!! nl2br($subscription->description) !!}</td>
                    </tr>
                    <tr>
                        <th>AMOUNT</th>
                        <td>{{ $subscription->currency ." ". number_format($subscription->total, 2) }}</td>
                    </tr>
                    <tr>
                        <th>EXTRA MSISDN</th>
                        @php
                            $extra_msisdn = json_decode($subscription->extra_msisdn, true);
                        @endphp
                        @if( empty($extra_msisdn))

                            <td> Non</td>
                        @else

                            <td>
                                <div>
                                    <span class="inline-element p-r-30">No.</span>
                                    <span class="inline-element">{{ isset($extra_msisdn['items']) ? $extra_msisdn['items'] : '0' }}</span>
                                </div>
                                <div>
                                    <span class="inline-element p-r-30">Amount</span>
                                    <span class="inline-element">{{ isset($extra_msisdn['price']) ? $subscription->currency.$extra_msisdn['price'] : $subscription->currency.'0.00' }}</span>
                                </div>
                            </td>

                        @endif

                    </tr>
                    <tr>
                        <th>STATUS</th>
                        <td>{!! $subscription->status() !!}</td>
                    </tr>
                    <tr>
                        <th>PAYMENT STATUS</th>
                        <td>{!! $subscription->payment_status() !!}</td>
                    </tr>{{--
                    <tr>
                        <th>ADDONS</th>
                        <td>{!! $subscription->addons !!}</td>
                    </tr> --}}
                    <tr>
                        <th>ACCOUNT MANAGER</th>
                        <td>{{ $subscription->manager ? $subscription->manager->name : '' }}</td>
                    </tr>
                </table>
            </div>


        </div>
        @endif

    </div>
</div>