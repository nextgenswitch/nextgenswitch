<!DOCTYPE html>
<html lang="en">

<head>

    <title>{{ __('Dialer') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bs.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-toaster.min.css') }}">
    <!-- Font-icon css-->
    
    <link rel="stylesheet" type="text/css" href="{{ asset('js/font-awesome/css/font-awesome.min.css') }}">
    <style>
        .text-required {
            font-size: 18px;
            color: rgb(247, 28, 28);
        }

        .overview-table th,
        .overview-table td {
            padding: 0 !important;
            border: none !important;
        }

        .dialer-input {
            height: calc(1.5em + 0.5rem + 4px);
            padding: 0.25rem 0.5rem;
            font-size: 0.765625rem;
            line-height: 1.5;
            border-radius: 4px;
        }
        .tab-button:focus,
        .tab-button:focus-visible{
            border: none !important;
            outline: none !important;
        }
        .custom-fields-table td{
            padding: 5px 0px !important;
            vertical-align: top;
            border-top: none !important;
            
        }
        .custom-fields-table tr td:first-child{
            padding-right: 5px !important;
        }
        
        
    </style>

</head>

<body class="app sidebar-mini">


    <div class="container-fluid text-center bg-primary">
        <h6 class="text-light py-2">You are now connected</h6>
    </div>

    <div class="row px-3">
        <div class="col-md-6">
            <h4 class="brand-logo">EasyPBX</h4>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-danger btn-sm">
                    <i class="fa fa-stop"></i>
                    <span>Stop Recording</span>
                </button>
                <button type="button" class="btn btn-secondary btn-sm">
                    <i class="fa fa-pause"></i>
                    <span> Pause Dialing</span>
                </button>
                <button type="button" class="btn btn-secondary btn-sm">
                    <i class="fa fa-stop"></i>
                    <span> End Dialing</span>
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table overview-table">
            <tr>
                <th class="text-center">Caller ID</th>
                <th class="text-center">Duration</th>
                <th class="text-center">Contacts</th>
                <th class="text-center">Calls</th>
                <th class="text-center">Talks</th>
                <th class="text-center">Emails</th>
                <th class="text-center">Voicemails</th>
            </tr>
            <tr>
                <td class="text-center">96-6545128</td>
                <td class="text-center">1min</td>
                <td class="text-center">3 of 230</td>
                <td class="text-center">1</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
            </tr>
        </table>
    </div>

    <div class="row px-3">
        <div class="col-6 d-flex">
            <input type="text" class="form-control dialer-input" placeholder="965842815">
            <button class="btn btn-primary btn-sm ml-1" type="button" id="button-addon2">Dial</button>
            <button class="btn btn-primary btn-sm ml-3"><i class="fa fa-plus"></i></button>
        </div>
        <div class="col-6">
            <div class="pull-right" role="group">
                <div class="dropdown">
                    <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                        {{ __('Actions') }}
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="">{{ __('Action 1') }}</a>
                        <a class="dropdown-item" href="">{{ __('Action 2') }}</a>
                        <a class="dropdown-item" href="">{{ __('Action 3') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row pt-3 px-3">
        <div class="col-md-6">
            <div class="customer">
                <div class="title pb-1">
                    <i class="fa fa-map-marker"></i>
                    {{ __('House # 38 Road-1, Dhaka 1230') }}
                </div>
                <div class="row pt-1 pb-2">
                    <div class="col-md-6">
                        <input type="text" name="first_name" id="first_name" class="form-control dialer-input"
                            placeholder="First name">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="last_name" id="last_name" class="form-control dialer-input"
                            placeholder="Last name">
                    </div>
                </div>

                <div class="pb-2">
                    <input type="email" name="email" id="email" placeholder="Email address"
                        class="form-control dialer-input">
                </div>
                <div class="pb-2">
                    <input type="text" name="address" id="address" class="form-control dialer-input"
                        placeholder="Address">
                </div>
                <div class="row">
                    <div class="col-md-4 pb-2">
                        <input type="text" name="city" id="city" class="form-control dialer-input"
                            placeholder="City">
                    </div>
                    <div class="col-md-4 pb-2">
                        <input type="text" name="state" id="state" class="form-control dialer-input"
                            placeholder="State">
                    </div>
                    <div class="col-md-4 pb-2">
                        <input type="text" name="post_code" id="post_code" class="form-control dialer-input"
                            placeholder="Post code">
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="5" class="form-control dialer-input"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active btn-sm tab-button" id="nav-custom-fields-tab" data-toggle="tab" data-target="#nav-custom-fields"
                        type="button" role="tab" aria-controls="nav-custom-fields" aria-selected="true">Custom Fields</button>
                    <button class="nav-link btn-sm tab-button" id="nav-phone-scripts-tab" data-toggle="tab" data-target="#nav-phone-scripts"
                        type="button" role="tab" aria-controls="nav-phone-scripts"
                        aria-selected="false">Phone Scripts</button>
                    <button class="nav-link btn-sm tab-button" id="nav-sms-tab" data-toggle="tab" data-target="#nav-sms"
                        type="button" role="tab" aria-controls="nav-sms"
                        aria-selected="false">SMS</button>
                </div>
            </nav>
            <div class="tab-content pt-2" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-custom-fields">
                    <table class="table custom-fields-table"> 
                        <tr>
                            <td class="text-right" width="30%"><label for="followup">Follow Up Date</label></td>
                            <td width="70%"><input type="text" name="followup" class="form-control dialer-input" id="followup" placeholder="mm-dd-yyyy"></td>
                        </tr>
                        <tr>
                            <td class="text-right"><label for="followup">Todays check ins</label></td>
                            <td><input type="text" name="followup" class="form-control dialer-input" id="followup" placeholder="mm-dd-yyyy"></td>
                        </tr>
                        <tr>
                            <td class="text-right"><label for="followup">Appointment Time</label></td>
                            <td><input type="text" name="followup" class="form-control dialer-input" id="followup" placeholder="Appointment time"></td>
                        </tr>

                        <tr>
                            <td class="text-right"><label for="followup">Appointment Date</label></td>
                            <td><input type="text" name="followup" class="form-control dialer-input" id="followup" placeholder="mm-dd-yyyy"></td>
                        </tr>

                        <tr>
                            <td class="text-right"><label for="followup">Custom Content for the Email</label></td>
                            <td><input type="text" name="followup" class="form-control dialer-input" id="followup" placeholder="Custom content for the email"></td>
                        </tr>

                    </table>

                </div>
                <div class="tab-pane fade" id="nav-phone-scripts">
                    <div class="card">
                        <div class="card-body">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus id dolores necessitatibus voluptas sed velit repellat rerum mollitia vero, iste sint error! Velit temporibus harum sit facilis non itaque error
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-sms">
                    <form action="" method="post">
                        <textarea name="body" id="body" class="form-control" rows="7"></textarea>
                        <button class="btn btn-primary mt-2" type="submit">Send SMS</button>
                    </form>
                </div>
            </div>

        </div>
    </div>


    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    <script src="{{ asset('js/plugins/bootstrap-toaster.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-select.min.js') }}"></script>
</body>

</html>
