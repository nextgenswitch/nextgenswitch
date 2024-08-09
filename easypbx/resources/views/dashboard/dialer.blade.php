@extends('layouts.app')

@section('content')

<div class="row">

<button type="button" class="btn btn-primary " id="btnlogin">Login</button>



</div>

<div class="row">
<button type="button" class="btn btn-primary " id="btndial">Dial</button>



</div>

@endsection

@push('script')

<script>
    $(document).ready(function() {

       var call_id
       $('#btnlogin').click(function(){
            $.get('{{ route('dashboard.dialer.connect') }}?to=1000', function( data ) {
               // alert( "Data Loaded: " + data );
                call_id = data.call_id;
                alert(call_id);
            });
       });

       $('#btndial').click(function(){

        number = prompt("Please enter a number to dial", "1001");

        $.get('{{ route('dashboard.dialer.dial') }}?call_id=' + call_id + '&to=' + number);
       });


    });
</script>


@endpush