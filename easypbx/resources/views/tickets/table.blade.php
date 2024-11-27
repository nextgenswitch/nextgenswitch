  <div class="row"><div class="col-sm-12">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th class="sortable" sort-by="created_at">{{ __('Date') }}</th>
                            <th>{{ __('Customer Name') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td><input type="checkbox" name="ids[]" class="idRow" value="{{ $ticket->id }}"></td>
                            <td>{{ date_time_format(optional($ticket)->created_at) }}</td>
                            <td>{{ optional($ticket)->name }}</td>
                            <td>{{ optional($ticket)->phone }}</td>
                            <td>{{ optional($ticket)->subject }}</td>
                            <td>{{ config('enums.ticket_status')[optional($ticket)->status] }}</td>
                            <td>
                                  
                                <div class="dropdown">
                                 <form method="POST" action="{!! route('tickets.ticket.destroy', $ticket->id) !!}" accept-charset="UTF-8" class="deleteFrm">
                                   @csrf
                                   @method('DELETE')   
                                    <a href="#" data-toggle="dropdown"> <i data-feather="more-horizontal"> </i> </a>
                                    <ul class="dropdown-menu shadow-dropdown action-dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('tickets.ticket.show', $ticket->id ) }}">
                                                <i data-feather="bar-chart-2"></i> {{ __('Follow Up') }}
                                            </a>
                                       </li>

                                        <li>
                                            <a title="Edit Ticket #{{ $ticket->id }}" class="dropdown-item btnForm" href="{{ route('tickets.ticket.edit', $ticket->id ) }}">
                                                <i data-feather="edit"></i> {{ __('Edit') }}
                                            </a>
                                       </li>
                                        <li>
                                        <button type="submit" class="dropdown-item btn btn-link" title="Delete User" onclick="return confirm('{{ __('Click Ok to delete Ticket.') }}')">
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
    
{!! $tickets->render() !!}
</div>

