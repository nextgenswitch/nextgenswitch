<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bs.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/font-awesome/css/font-awesome.min.css') }}">

</head>
<body>
    <div class="row pt-5">
        @if(isset($authenticationRequired) && $authenticationRequired)
            <div class="col-lg-6 col-md-12 offset-lg-3">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="media align-items-stretch">
                                <div class="media-body">
                                    <h4>Authentication Required</h4>
                                    <span>Please login to dialer first</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(isset($campaignCompleted) && $campaignCompleted)
        
        <div class="col-lg-6 col-md-12 offset-lg-3">
            <div class="card">
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="media-body">
                                <h4>Complated</h4>
                                <span>The campaign has been completed ! Thank you.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif(isset($scheduleNotMatch) && $scheduleNotMatch)
        <div class="col-lg-6 col-md-12 offset-lg-3">
            <div class="card">
                <div class="card-content">
                    <div class="card-body cleartfix">
                        <div class="media align-items-stretch">
                            <div class="media-body">
                                <h4>Schedule</h4>
                                <span>The current time is not within the specified range.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>
