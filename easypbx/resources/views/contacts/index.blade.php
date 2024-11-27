@extends('layouts.app')

@section('title', __('All Contacts'))

@push('css')
    
    <link rel="stylesheet" href="{{ asset('js/jquery-ui/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap4.min.css') }}">
@endpush

@section('content')

    @include('partials.message')



    <div class="panel panel-default">


        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="tile-title">{{ __('Contacts') }}</h4>
            </div>
            
            
                <div class="btn-group btn-group-sm pull-right" role="group">
                    <a href="{{ route('contacts.contact.create') }}" class="btn btn-primary btnForm"
                        title="{{ __('Create new contact') }}">
                        <span class="fa fa-plus" aria-hidden="true"></span>{{ __('Create new contact') }}
                    </a>
                    &nbsp;
          
                    <div class="btn btn-primary" data-toggle="modal" data-target="#importContactModal">
                        <span class="fa fa-upload"aria-hidden="true"></span>
                            {{ __('Import Contact') }}
                    </div>

                     &nbsp;
                    <a href="{{ route('contact_groups.contact_group.index') }}" class="btn btn-primary"
                        title="{{ __('Contact groups') }}">
                        <span class="fa fa-list" aria-hidden="true"></span>{{ __('Contact Groups') }}
                    </a> 
                </div>
            

        </div>


        <div class="panel-body panel-body-with-table">


            <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length py-2" id="sampleTable_length">
                            <div class="input-group">
                                <div class="input-group-prepend"> {!! Form::select('crud_per_page', config('enums.pagination_count'), app('request')->input('per_page'), [
                                    'id' => 'crud_per_page',
                                    'class' => 'form-control form-control-sm ',
                                ]) !!}

                                </div>&nbsp;

                                {!! Form::select('filter_group', $contact_groups, null, [
                                    'placeholder' => __('Any Contact Group'),
                                    'id' => 'filter_group',
                                    'class' => 'form-control form-control-sm ',
                                ]) !!}
                                <input type="search" name="search" id="search" value="{{ app('request')->input('q') }}"
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
                        class="col-sm-12 col-md-6 text-md-right table-toolbar-right justify-content-sm-start justify-content-md-end">
                        <div class="btn-group btn-group-sm py-2" role="group"
                            aria-label="Button group with nested dropdown">

                            <div class="btn-group btn-group-sm" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn  dropdown-toggle btn-outline-secondary"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('Export') }}
                                </button>
                                <div class="dropdown-menu shadow-dropdown" aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item" href="#" id="csvD">{{ __('Csv') }}</a>
                                    <a class="dropdown-item" href="#" id="printTable">{{ __('Print') }}</a>
                                </div>
                                <button type="button" class="btn btn-outline-secondary " data-toggle="modal"
                                    data-target="#bulkActionModal">{{ __('Bulk Action') }}</button>
                            </div>


                            <div id="sampleTable_filter" class="dataTables_filter btn-group btn-group-sm">

                                <button id="btnFilter" type="button" class="btn btn-outline-secondary "
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                        data-toggle="tooltip" data-placement="left" title="{{ __('Filter By') }}"><i
                                            class="fa fa-filter"></i></span></button>

                                <div class="dropdown-menu dropdown-menu-right shadow-dropdown" aria-labelledby="btnFilter">
                                    <a class="dropdown-item" href="{!! route('contacts.contact.index') !!}">{{ __('All') }}</a>
                                    <div class="dropdown-divider"></div>
                                    @foreach ($contact_groups as $key => $group)
                                        <a class="dropdown-item"
                                            href="{!! route('contacts.contact.index') !!}?filter=contact_group_id:{{ $key }}">{{ $group }}</a>
                                    @endforeach
                                </div>
                            </div>

                            <button id="btn-refreash" type="button" class="btn btn-outline-secondary "
                                data-toggle="tooltip" data-placement="top" title="{{ __('Reload') }}"><span><i
                                        class="fa fa-refresh"></i></span></button>

                        </div>

                    </div>

                </div>
                <div class="table-responsive">
                    <div id="crud_contents">
                        @include ('contacts.table', [
                            'contacts' => $contacts,
                            'contact_groups' => $contact_groups,
                        ])
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
                    <form method="POST" action="{!! route('contacts.contact.bulk') !!}" class="editableForm" id="massActionFrm"
                        accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PUT">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="mass_delete" id="mass_delete"
                                    value="1">{{ __('Delete') }}
                            </label>
                        </div>
                        <div id="bulk_fields">
                            <hr>
                            <div class="form-group">
                                <label for="user_status"> {{ __('Set Gruop') }}</label>
                                {!! Form::select('contact_groups', $contact_groups, null, [
                                    'multiple' => 'multiple',
                                    'name' => 'contact_groups[]',
                                    'class' => 'form-control',
                                    'maxlength' => '100',
                                    'placeholder' => __('place_group'),
                                ]) !!}

                            </div>
                        </div>

                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> {{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="mass_submit"> {{ __('Save Changes') }}</button>
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
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('Create new contact') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> {{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary btnSave"> {{ __('Save Changes') }}</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="callModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="callModelTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('calling.call') }}" accept-charset="UTF-8" id="callingModalForm"
                    name="create_form" class="form-horizontal">
                    <div class="modal-body" id="callingModalBody">
                        <input type="hidden" name="to" id="callingModalInputTo">
                        @include('contacts.call')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Send Call">

                    </div>

                </form>
            </div>
        </div>
    </div>
    @include('contacts.import')
    @include('contacts.sms_modal')


@endsection

@push('script')

<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/selectize.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {


            $crud = $('#crud_contents').crud();

            destinations = "{{ route('calling.destinations', 0) }}"

            $(document).on('change', '#function_id', function(e) {
                e.preventDefault()

                var val = $(this).val().trim()

                if (val != undefined && val != '') {
                    route = destinations.trim().slice(0, -1) + val
                    console.log(route)

                    $.get(route, function(res) {
                        console.log(res)
                        $("#destination_id").html(res)
                    })

                } else
                    $("#destination_id").html('<option> Select destination </option>')

            })

            $("#callingModalForm").submit(function(e) {
                e.preventDefault()

                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('calling.call') }}',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        // response = JSON.parse(response);

                        if (response['error'] != undefined && response['error'] == true) {

                            $crud.showToast(response['error_message'], false);


                        }

                        if (response['call_id'] != undefined) {
                            $("#callingModalForm").trigger('reset');
                            $crud.showToast('Call sent successfully');
                        }
                    },
                    error: function(error) {
                        console.error('Error occurred:', error);
                    }
                });
            })

            $('.btnGroup').click(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var group = prompt("Please enter your group name", "Support");
                if (group != null) {


                    $.post("{!! route('contact_groups.contact_group.store') !!}", {
                            _token: CSRF_TOKEN,
                            name: group
                        })
                        .done(function(msg) {
                            $crud.showToast('Contact Group added');
                        })
                        .fail(function(xhr, status, error) {
                            $crud.showToast('Contact Group add failed', false);

                        });


                }
            });

            $('#filter_group').change(function() {
                document.location.href = "{!! route('contacts.contact.index') !!}?filter=contact_group_id:" + $(this).val();

            });

            var filter = '{{ app('request')->input('filter') }}';
            if (filter != '') {
                var f = filter.split(':');
                $('#filter_group').val(f[1]);

            }


            $("input:file").change(function() {
                var fileName = $(this).val();

                if (fileName != '') {
                    var ext = fileName.split('.').pop().toLowerCase();
                    if (jQuery.inArray(ext, ['csv', 'txt']) == -1) {
                        $crud.showToast("Invalid File Format!", false);
                        return false;
                    }
                }
            });




        });
    </script>
@endpush
