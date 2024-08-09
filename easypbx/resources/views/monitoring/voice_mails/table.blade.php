<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>

                    <th>{{ __('Extension Name') }}</th>
                    <th class='sortable' sort-by="caller_id">{{ __('Caller ID') }}</th>
                    <th>{{ __('Voice') }}</th>
                    <th class="sortable" sort-by="expire">{{ __('Transcript') }}</th>
                    <th>{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mails as $mail)
                    <tr>
                        <td>{{ $mail->extension->name }}</td>
                        <td>{{ $mail->caller_id }}</td>

                    
                        <td class="voice-preview">
                            @if($mail->voice_path)
                            <span class="btn btn-outline-primary btn-sm play" src="{{ url('storage/' . $mail->voice_path) }}"><i class="fa fa-play"></i></span>
                            <span class="btn btn-outline-primary btn-sm stop d-none"><i class="fa fa-stop"></i></span>
                            @endif
                        </td>


                        <td>{{ $mail->transcript }}</td>
                        <td>{{ $mail->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    {!! $mails->render() !!}
</div>
