@extends('layouts.operator_sidebar')

@section('title')
    
    DASHBOARD

@endsection

@section('breadcrumb')

    <li class=" active"> Dashboard </li>

@endsection

@section('content')

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel info-box panel-white">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p class="counter">{{ number_format(count($number_of_active_subscribers) ) }}</p>
                        <span class="info-box-title">Number Of Active Subscribers </span>
                    </div>
                    <div class="info-box-icon">
                        <i class="icon-users"></i>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel info-box panel-white">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p class="counter"> {{ number_format(count($connected_calls)) }} </p>
                        <span class="info-box-title">Number Of Connected Calls Daily</span>
                    </div>
                    <div class="info-box-icon">
                        <i class="glyphicon glyphicon-phone"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel info-box panel-white">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p> &#x20A6;<span class="counter">{{ number_format($actual_revenue) }}</span></p>
                        <span class="info-box-title">Monthly revenue </span>
                    </div>
                    <div class="info-box-icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel info-box panel-white">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p class="counter">{{ count($open_conversations) }}</p>
                        <span class="info-box-title">Number Of Open Conversation</span>
                    </div>
                    <div class="info-box-icon">
                        <i class="icon-envelope"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading text-center">Total Order Per Week / Month / Customer ( Completed | Uncompleted ) </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Customer</th>
                                <th>Week 1</th>
                                <th>Week 2</th>
                                <th>Week 3</th>
                                <th>Week 4</th>
                                @if(isset($total_order_per_week->first()['week5']))
                                    <th>Week 5</th>
                                @endif
                                <th>Month</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($total_order_per_week as $index => $order)

                                <tr>
                                    <td>{{ $index }}</td>
                                    <th>{{ $order['name'] }}</th>
                                    <td>{{ $order['week1']['completed'] }} | {{ $order['week1']['uncompleted'] }}</td>
                                    <td>{{ $order['week2']['completed'] }} | {{ $order['week2']['uncompleted'] }}</td>
                                    <td>{{ $order['week3']['completed'] }} | {{ $order['week3']['uncompleted'] }}</td>
                                    <td>{{ $order['week4']['completed'] }} | {{ $order['week4']['uncompleted'] }}</td>
                                    @if(isset($total_order_per_week->first()['week5']))
                                        <td>{{ $order['week5']['completed'] }} | {{ $order['week5']['uncompleted'] }}</td>
                                    @endif
                                    <td>{{ $order['month']['completed'] }} | {{ $order['month']['uncompleted'] }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading text-center">Total Value Order Per Week / Month / Customer ( Completed | Uncompleted in &#x20A6; )</div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Customer</th>
                                <th>Week 1</th>
                                <th>Week 2</th>
                                <th>Week 3</th>
                                <th>Week 4</th>
                                @if(isset($total_value_order_per_week->first()['week5']))
                                    <th>Week 5</th>
                                @endif
                                <th>Month</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($total_value_order_per_week as $index => $order)

                                <tr>
                                    <td>{{ $index }}</td>
                                    <th>{{ $order['name'] }}</th>
                                    <td>{{ number_format($order['week1']['completed']) }} | {{ number_format($order['week1']['uncompleted']) }}</td>
                                    <td>{{ number_format($order['week2']['completed']) }} | {{ number_format($order['week2']['uncompleted']) }}</td>
                                    <td>{{ number_format($order['week3']['completed']) }} | {{ number_format($order['week3']['uncompleted']) }}</td>
                                    <td>{{ number_format($order['week4']['completed']) }} | {{ number_format($order['week4']['uncompleted']) }}</td>
                                    @if(isset($total_value_order_per_week->first()['week5']))
                                        <td>{{ number_format($order['week5']['completed']) }} | {{ number_format($order['week5']['uncompleted']) }}</td>
                                    @endif
                                    <td>{{ number_format($order['month']['completed']) }} | {{ number_format($order['month']['uncompleted']) }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">Total Calls Per Day /Week / Month </div>
                <div class="panel-body">
                    
                    <canvas id="callMinsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
{{-- 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">Total Amount Of Airtime Consumed Per Day /Week / Month </div>
                <div class="panel-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                
                            </tr>
                        </tbody>   
                    </table>
                </div>
            </div>
        </div>
    </div>
 --}}
@endsection


@section('extra-script')

    <script type="text/javascript" src="{{asset('js/domain_setting.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/custom_ajax/uploader.js')}}"></script>
    <script type="text/javascript">

        
        $mn_list = $('.page-sidebar-inner ul.sidebar-nav > li.dashboard');
        $mn_list.addClass('active');

        window.onload = loadCharts;

        var callMinsChartOptions = {
            scales: {
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Calls Per day of the month'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Number of Calls'
                    }
                }]
            }
        };

        function loadCharts() {


            var callMinsChart = document.getElementById('callMinsChart').getContext('2d');

            var labels = [], success = [], failed = [], airtime = [];

            @foreach ($calls_report['date'] as $index => $call)
                labels.push("{{$index}}");
                success.push("{{ $call['success'] }}");
                failed.push("{{ $call['failed'] }}");
                airtime.push("{{ $call['airtime'] }}");
            @endforeach

            var callMinsChart = new Chart(callMinsChart, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Successfull Calls',
                        data: success,
                        backgroundColor: '#2a745d',
                        borderColor: [
                            '2a745d2d',
                        ],
                        borderWidth: 1
                    },
                    {
                        label: 'Failed Calls',
                        data: failed,
                        // backgroundColor: '#167F92',
                        // borderColor: [
                        //     'rgba(255,99,132,1)',
                        // ],
                        borderWidth: 1
                    }
                    ]
                },

                options: callMinsChartOptions
            })


        }

    </script>


@endsection