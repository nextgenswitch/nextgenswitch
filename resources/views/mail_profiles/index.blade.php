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



    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Email Profiles') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('mail_profiles.mail_profile.create') }}" class="btn btn-primary"
                    title="{{ __('Create New EMail Profile') }}">
                    <span class="fa fa-plus" aria-hidden="true"></span>{{ __('Create New Email Profile') }}
                </a>
            </div>

        </div>


        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="dataTables_length py-2" id="sampleTable_length">
                                <div class="input-group">
                                    <div class="input-group-prepend"> {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
                                        'id' => 'crud_per_page',
                                        'class' => 'form-control form-control-sm ',
                                        'style' => 'width:70px',
                                    ]) !!}

                                    </div>&nbsp;

                                    <!--  {!! Form::select('filter_group', [], null, [
                                        'placeholder' => 'Any Contact Group',
                                        'id' => 'filter_group',
                                        'class' => 'form-control form-control-sm ',
                                    ]) !!}   -->
                                    <input type="search" name="search" id="search"
                                        value="{{ app('request')->input('q') }}"
                                        class="app-search__input form-control form-control-sm"
                                        placeholder="{{ __('Search') }}">


                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-secondary" type="button" id="btnSearch">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div
                            class="col-sm-12 col-md-8 text-md-right table-toolbar-right justify-content-sm-start justify-content-md-end">
                            <div class="btn-group btn-group-sm py-2" role="group"
                                aria-label="Button group with nested dropdown">

                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" id="printTable"
                                        class="btn btn-outline-secondary ">{{ __('Print') }}</button>
                                    <button type="button" class="btn btn-outline-secondary " data-toggle="modal"
                                        data-target="#bulkActionModal">{{ __('Bulk Actions') }}</button>
                                </div>


                                <div id="sampleTable_filter" class="dataTables_filter btn-group btn-group-sm">

                                    <button id="btnFilter" type="button" class="btn btn-outline-secondary "
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                            data-toggle="tooltip" data-placement="left" title="{{ __('Filter By') }}"><i
                                                class="fa fa-filter"></i></span></button>

                                    <div class="dropdown-menu shadow-dropdown" aria-labelledby="btnFilter">
                                        <a class="dropdown-item" href="{!! route('mail_profiles.mail_profile.index') !!}">{{ __('All') }}</a>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                </div>

                                <button id="btn-refreash" type="button" class="btn btn-outline-secondary "
                                    data-toggle="tooltip" data-placement="top" title="{{ __('Reload') }}"><span><i
                                            class="fa fa-refresh"></i></span></button>

                            </div>

                        </div>

                    </div>

                    <div id="crud_contents">
                        @include ('mail_profiles.table', ['mailProfiles' => $mailProfiles])
                    </div>



                </div>
            </div>




        </div>
    </div>


    <!-- Modal for bulk actions-->
    <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Bulk Action') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{!! route('mail_profiles.mail_profile.bulk') !!}" class="editableForm" id="massActionFrm"
                        accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PUT">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="mass_delete" id="mass_delete"
                                    value="1">{{ __('Mass Delete') }}
                            </label>
                        </div>
                        <div id="bulk_fields">
                            <hr>
                            <!--<div class="form-group">
                                                                                            <label for="user_status">Set status</label>
                                                                                            {!! Form::select('status', [], null, ['class' => 'form-control selectpicker']) !!}

                                                                                    </div>  -->
                        </div>

                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="mass_submit">{{ __('Save changes') }}</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Create New Email Profile') }}</h5>
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



    <!-- Modal for add edit forms-->
    <div class="modal fade" id="testMailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Test Email Provider') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('mail_profiles.mail_profile.test') }}" id="testMailFrm">
                        @csrf
                        <input name="mail_profile_id" type="hidden">
                        <div class="form-group">
                            <label for="test_email">{{ __('To') }}</label>
                            <input class="form-control" type="email" name="to" id="test_email"
                                placeholder="{{ __('Enter email address to test') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="test_subject">{{ __('Subject') }}</label>
                            <input class="form-control" type="text" name="subject" id="test_subject"
                                placeholder="{{ __('Enter subject for test email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="test_message">{{ __('Body') }}</label>
                            <textarea class="form-control" name="body" id="test_message"
                                placeholder="{{ __('Enter message for test email') }}" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Send Test Email') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('js/index.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#crud_contents").on('click', '.send-test-mail', function(e) {
                e.preventDefault();
                var mailProfileId = $(this).data('mid');
                $('#testMailFrm input[name="mail_profile_id"]').val(mailProfileId);
                $('#testMailModal').modal('show');
            });
        })
    </script>
@endpush
