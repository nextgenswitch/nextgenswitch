@extends('agents.layouts.app')

@section('title', 'Dashboard')

@push('css')
    <!-- Load c3.css -->
    <link href="{{ asset('tour/css/style.css?v=' . rand()) }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.18/c3.min.css" rel="stylesheet" />

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.18/c3.min.js"></script>

@endpush
