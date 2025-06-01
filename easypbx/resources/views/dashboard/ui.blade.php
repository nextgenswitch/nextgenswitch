@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
    <!-- Load c3.css -->
    <link href="{{ asset('c3/c3.min.css') }}" rel="stylesheet" />

    <style>

        .calls_box
        {
        position: relative;
        background-color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        .calls_icon
        {
        background-color: #1c1c489e;
        padding: 10px;
        color: white;
        border-radius: 5px;
        }
        .total_calls
        {
        display: flex;
        flex-direction: column;
        line-height: 20px;
        }
        .total_calls h2
        {
        color: #576574;
        font-size: 14px;
        font-weight: 500;
        }
        .total_calls span
        {
        font-size: 18px;
        font-weight: 600;
        color: #72728e;
        }


        .dash-card{
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .dash-card .dropdown-menu{
            min-width: 6rem !important;
        }

        .dash-card-header {
            background: #1c1c489e;
            padding: 2px 10px;
            border-radius: 4px 4px 0px 0px;
            border: none;
        }
        
        .dash-card-title h3{
            padding: 0px !important;
            font-size: 15px;
            font-weight: 550;
            color: #dbdbdb;
            margin-top: 10px !important;
            margin-bottom: 5px !important;
        }

        .dash-card-dropdown .btn-group{
            margin-top: 5px !important;
        }

        .dash-card-dropdown .btn-group .btn{
            background: #585876;
            border-color: #585876;
            color: #efefef;
            padding: 3px 0.5rem;
        }

        .dash-card-body .table tr td{
            color: #576574;
            font-size: 14px;
            font-weight: 500;
        }

        .agent-calling-perfomance .dash-card-body{
            padding: 5px;
        }
    </style>

@endpush

@section('content')
<section class="mx-2 py-3">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="calls">
                <div class="calls_box">
                    <span class="calls_icon"><i class="fa fa-phone"></i></span>
                    <div class="total_calls">
                        <h2>Total Calls</h2>
                        <span>{{ $total_calls }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="calls">
                <div class="calls_box">
                    <span class="calls_icon"><i class="fa fa-hourglass-start"></i></span>
                    <div class="total_calls">
                        <h2>Total Calls duration</h2>
                        <span>{{ duration_format($total_duration) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="calls">
                <div class="calls_box">
                    <span class="calls_icon"><i class="fa fa-thumbs-o-up"></i></span>
                    <div class="total_calls">
                        <h2>Avg Sucess Rate</h2>
                        <span>{{ number_format($avg_success_rate, 2)  }}%</span>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="calls">
                <div class="calls_box">
                    <span class="calls_icon"><i class="fa fa-coffee"></i></span>
                    <div class="total_calls">
                        <h2>Avg Call Duration</h2>
                        <span>{{ duration_format($avg_call_duration) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</section>

<section class="mx-2 py-3">
    <div class="row">
        <!-- <div class="col-12 col-md-4">
            <div class="dash-card">
                <div class="dash-card-header d-flex justify-content-between">
                    <div class="dash-card-title">
                        <h3>15 Dec 2024</h3>
                    </div>
                    <div class="dash-card-dropdown">
                        <div class="btn-group">
                                <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Today
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Yesterday</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="dash-card-body">
                    <table class="table table-striped">
                    <tr>
                        <td>9:00</td>
                        <td>Lila Harper</td>
                        <td>9:00 AM - 11:00 AM</td>
                    </tr>
                    <tr>
                        <td>8:00</td>
                        <td>Alisha</td>
                        <td>8:00 AM - 9:00 AM</td>
                    </tr>
                    <tr>
                        <td>7:00</td>
                        <td>Jonson Miller</td>
                        <td>7:00 AM - 8:00 AM</td>
                    </tr>
                    <tr>
                        <td>5:00</td>
                        <td>John Doe</td>
                        <td>5:00 AM - 5:30 AM</td>
                    </tr>
                    <tr>
                        <td>5:10</td>
                        <td>Jerry</td>
                        <td>5:10 AM - 5:20 AM</td>
                    </tr>
                    </table>
                </div>
            </div>
        </div> -->
        <div class="col-12 col-md-8">
            <div class="dash-card agent-calling-perfomance">
                <div class="dash-card-header d-flex justify-content-between">
                    <div class="dash-card-title">
                        <h3>Bridge Call Statistics</h3>
                    </div>
                    <!-- <div class="dash-card-dropdown">
                        <div class="btn-group">
                                <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Last Week
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Yesterday</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                </div>
                        </div>
                    </div> -->
                </div>
                <div class="dash-card-body">
                    <canvas height="100" id="lineChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="dash-card agent-calling-perfomance">
                <div class="dash-card-header d-flex justify-content-between">
                    <div class="dash-card-title">
                        <h3>Extension Stats</h3>
                    </div>
                    <!-- <div class="dash-card-dropdown">
                        <div class="btn-group">
                                <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Yesterday
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Yesterday</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                </div>
                        </div>
                    </div> -->
                </div>
                <div class="dash-card-body">
                <canvas id="doughnutChartExtensionStats" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-2 py-3">
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="dash-card agent-calling-perfomance">
                <div class="dash-card-header d-flex justify-content-between">
                    <div class="dash-card-title">
                        <h3>Call Stats</h3>
                    </div>
                    <div class="dash-card-dropdown">
                        <div class="btn-group">
                            <button class="btn btn-sm dropdown-toggle" type="button" data-stats="call-stats" data-toggle="dropdown" >
                                Today
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" data-filter="today">Today</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-filter="yesterday">Yesterday</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-filter="last_week">Last Week</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dash-card-body">
                    <canvas id="pieChartCallStats" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="dash-card agent-calling-perfomance">
                <div class="dash-card-header d-flex justify-content-between">
                    <div class="dash-card-title">
                        <h3>Trunk Stats</h3>
                    </div>
                    <div class="dash-card-dropdown">
                        <div class="btn-group">
                            <button class="btn btn-sm dropdown-toggle" type="button" data-stats="trunk-stats" data-toggle="dropdown" >
                                Today
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" data-filter="today">Today</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-filter="yesterday">Yesterday</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-filter="last_week">Last Week</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dash-card-body">
                    <canvas id="doughnutChartTrunkStats" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="dash-card agent-calling-perfomance">
                <div class="dash-card-header d-flex justify-content-between">
                    <div class="dash-card-title">
                        <h3>Queue Stats</h3>
                    </div>
                    <div class="dash-card-dropdown">
                        <div class="btn-group">
                            <button class="btn btn-sm dropdown-toggle" type="button" data-stats="queue-stats" data-toggle="dropdown" >
                                Today
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" data-filter="today">Today</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-filter="yesterday">Yesterday</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-filter="last_week">Last Week</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dash-card-body">
                    <canvas id="pieChartQueueStats" height="150"></canvas>
                </div>
            </div>
        </div>


        

    </div>

</section>
@endsection

@include('dashboard.script')
