<div class="row">
    <div class="col-sm-12">

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                    <th class="sortable" sort-by="title">{{ __('Name') }}</th>
                    <th>{{ __('WebSocket URL') }}</th>
                    <th>{{ __('Forwarding Number') }}</th>
                    <th>{{ __('Max Call Durations') }}</th>
                    <th>{{ __('Record') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($streams as $stream)
                    <tr>
                        <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $stream->id }}"></td>

                        <td>{{ optional($stream)->name }}</td>
                        <td>{{ optional($stream)->ws_url }}</td>
                        <td>{{ optional($stream)->forwarding_number }}</td>
                        <td>{{ optional($stream)->max_call_duration }}</td>
                        
                        <td>
                            {{ ($stream->record) ? 'Enable' : 'Disabled' }}

                            <form method="POST" action="{!! route('streams.stream.updateField', $stream->id) !!}" class="editableForm" accept-charset="UTF-8">
                            <input type="hidden" name="record" type="hidden" value="0">
                            @csrf
                            @method('PUT')
                                <div class="toggle">
                                <label>
                                <input type="checkbox" name="record" value="1"
                                @if ($stream->record) checked="checked" @endif
                                class="editableField"><span class="button-indecator"></span>
                                </label>
                            </div>
                            </form>

                        </td>

                        <td>
                            <div class="dropdown">
                                <form method="POST" action="{!! route('streams.stream.destroy', $stream->id) !!}" accept-charset="UTF-8"
                                    class="deleteFrm">
                                    @csrf
                                    @method('DELETE')
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i>
                                    </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu"
                                        aria-labelledby="dropdownMenuButton1">
                                        <li><a title="Edit Stream #{{ $stream->id }}" class="dropdown-item btnForm"
                                                href="{{ route('streams.stream.edit', $stream->id) }}">
                                                <i data-feather="edit"></i> {{ __('Edit') }}
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('monitoring.stream_histories.index', ['filter' => 'stream_id:' . $stream->id] ) }}">
                                            <i data-feather="activity"></i> {{ __('Stream History') }}
                                        </a>
                                       </li>
                                        <li>
                                            <button type="submit" class="dropdown-item btn btn-link"
                                                title="Delete Stream"
                                                onclick="return confirm('{{ __('Click Ok to delete Stream.') }}')">
                                                <i data-feather="trash"></i> {{ __('Delete') }}
                                            </button>
                                        </li>
                                    </ul>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div class="row">
    {!! $streams->render() !!}
</div>
