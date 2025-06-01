@extends('layouts.app')

@section('content')
    @if (Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif


    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ (request('tab') == 'firewall' || !request()->has('tab'))  ? 'active' : '' }}" id="home-tab" data-toggle="tab" data-target="#firewall" type="button"
                        role="tab" aria-controls="home" aria-selected="true">{{ __('Firewall') }}</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ request('tab') == 'ip'  ? 'active' : '' }}" id="profile-tab" data-toggle="tab" data-target="#ip-black-list" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">{{ __('IP Black List') }}</button>
                </li>

            </ul>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show {{ (request('tab') == 'firewall' || !request()->has('tab'))  ? 'active' : '' }}" id="firewall" role="tabpanel" aria-labelledby="home-tab">
                    {!! Form::open([
                        'route' => 'settings.firewall.store',
                        'class' => 'form-horizontal',
                        'name' => 'create_setting_form',
                        'id' => 'create_setting_form',
                    ]) !!}
                    

                    @include ('firewall.general')

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            {!! Form::submit(__('Save Changes'), ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>

                <div class="tab-pane fade show {{ request('tab') == 'ip'  ? 'active' : '' }}" id="ip-black-list" role="tabpanel" aria-labelledby="home-tab">
                    <div class="table-responsive">

                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                            <div id="crud_contents">
                                @include ('firewall.ip.table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Modal for add edit forms-->
    <div class="modal fade drawer right-align" id="FormModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Create New Ip Black List') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary btnSave">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection



@push('script')
    
    <script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $crud = $('#crud_contents').crud();

        });
    </script>
@endpush