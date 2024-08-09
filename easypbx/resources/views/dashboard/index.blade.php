@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
    <!-- Load c3.css -->
    <link href="{{ asset('tour/css/style.css?v=' . rand()) }}" rel="stylesheet">

    <link href="{{ asset('c3/c3.min.css') }}" rel="stylesheet" />

    <style>
        /* CSS to modify the title font size */
        .c3-chart-arcs-title {
            font-size: 20px;
        }

    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"> {{ __("Today's Call Reports") }} </div>
                <div class="card-body">
                    <div id="chartCall"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header"> {{ __('Extensions Online') }} </div>
                <div class="card-body">
                    <div id="chartExtension"></div>
                </div>
            </div>
        </div>


        <div class="col-md-12 my-3">
            <div class="card">
                <div class="card-header"> {{ __('Hourly Calls Summary') }} </div>
                <div class="card-body">
                    <div id="chartHourlyCalls"></div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-12 py-3">
        </div>
    </div>

@endsection

@push('script')
    
    

    <script src="{{ asset('c3/d3.min.js') }}"></script>
    <script src="{{ asset('c3/c3.min.js') }}"></script>
    

    <script>
        $(document).ready(function() {
            var call_limit = '{{ config('licence.call_limit') }}';
            
            if( ! parseInt(call_limit) > 0 ){
                console.log('Licence not found');
                $("#btn-lc-modal").click()
            }
            
            $.get("{{ route('licence.sync') }}");
            var calls = @json($calls);
            calls = JSON.parse(calls);

            var extensions = @json($extensions);
            extensions = JSON.parse(extensions);


            console.log(calls);
            console.log(extensions);

            var callChartData = {
                columns: [
                    // ['Total Calls', calls.total],
                    ['Succeeded Calls', calls.successed],
                    ['Failed Calls', calls.failed],
                ],
                type: 'donut'
            };

            // Configuration options for the chart
            var callChartOptions = {
                donut: {
                    title: "Total " + calls.total,
                    label: {
                        format: function(value, ratio, id) {
                            return value;
                        }
                    }
                },
            };

            // Generate the chart
            c3.generate({
                bindto: '#chartCall',
                data: callChartData,
                donut: callChartOptions.donut
            });




            var extChartData = {
                columns: [
                    ['Online Extensions', extensions.online],
                    ['Offline Extension', extensions.total - extensions.online],
                ],
                type: 'donut'
            };

            // Configuration options for the chart
            var extChartOptions = {
                donut: {
                    title: "Total " + extensions.total,
                    label: {
                        format: function(value, ratio, id) {
                            return value;
                        }
                    }
                }
            };

            // Generate the chart
            c3.generate({
                bindto: '#chartExtension',
                data: extChartData,
                donut: extChartOptions.donut
            });



            var hourlyReports = @json($hourlyReports);
            hourlyReports = JSON.parse(hourlyReports);
            hourlyReports.total.unshift('Total')
            hourlyReports.successed.unshift('Successed')
            hourlyReports.failed.unshift('Failed')
            hourlyReports.time.unshift('x')
            console.log(hourlyReports);


            var hourlyChartData = {
                columns: [
                    hourlyReports.total,
                    hourlyReports.failed,
                    hourlyReports.successed,
                    hourlyReports.time
                ],
                x: 'x',
                xFormat: '%H:%M' // Adjust xFormat to only include hours and minutes
            };

            // Configuration options for the chart
            var hourlyChartConfig = {
                bindto: '#chartHourlyCalls',
                data: hourlyChartData,
                axis: {
                    x: {
                        type: 'timeseries',
                        tick: {
                            format: '%H:%M' // Format of the x-axis ticks to only show time
                        }
                    }
                }
            };

            // Generate the chart
            c3.generate(hourlyChartConfig);

        })
    </script>
@endpush
