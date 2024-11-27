<div class="modal fade" id="importContactModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('contacts.contact.upload') }}" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12" id="voice-file">
                            <div class="form-group @error('file') has-error @enderror">
                            
                            <label class="form-label" for="fileCsv">Upload Contact File 
                                <span class="text-required">*</span>
                                <a href="{{ asset('samples/csv_contact_sample.csv') }}"><span style="color: #004a43;"> {{ __('(CSV Sample Contacts') }} </span></a>
                                <a href="{{ asset('samples/txt_contact_sample.txt') }}"><span style="color: #004a43;"> {{ __('Text Sample Contacts)') }} </span></a>
                            </label>                              

                                    {!! Form::file('file',  ['class' => 'form-control' . ($errors->has('file') ? ' is-invalid' : null), 'required' => true, 'placeholder' => __('Enter your contact file'), 'id' => 'fileCsv', 'accept'=>".csv,.txt" ]) !!}
                                    @error('file') <p class="help-block  text-danger"> {{ $message }} </p> @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group @error('contact_groups') has-error @enderror">
                                <!-- {!! Form::label('contact_groups', __('Contact Group'), ['class' => 'control-label']) !!} -->

                                <label for="contact_groups" class="control-label">{{ __('Contact Group') }} </label>
                                <span class="text-required">*</span>

                                

                                {!! Form::select('contact_groups', $contact_groups, old('contact_groups'), [
                                    'multiple' => 'multiple',
                                    'name' => 'contact_groups[]',
                                    'class' => 'form-control ' . ($errors->has('contact_groups') ? ' is-invalid' : null),
                                    'maxlength' => '100',
                                    'required' => true,
                                    'data-actions-box' => 'true',
                                ]) !!}

                               
                                @error('contact_groups')
                                    <p class="help-block  text-danger"> {{ $message }} </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <input type="submit" class="btn btn-primary btn-sm" value="Import">

                </div>

            </form>
        </div>
    </div>
</div>


@push('script')
<script>
    $(document).ready(function(){
        
        $("#createNewGroup").click(function(event){
            event.preventDefault();
            console.log('Clicked create group btn');

            $("#contact_groups").closest('.bootstrap-select').addClass('d-none');
            $("#contact_group").removeClass('d-none');

            $("#contact_groups").attr('required', false);
            $("#contact_group").attr('required', true);

        })

        $("#contact_groups").selectize({
            delimiter: ",",
            persist: false,
            maxItems: null,
            create: function (input) {
                return {
                    value: input,
                    text: input,
                    };

                
            }
        });

        $("#contact_group").selectize({
            delimiter: ",",
            persist: false,
            create: function (input) {
                return {
                    value: input,
                    text: input,
                };
            },
        });
    })
</script>
@endpush