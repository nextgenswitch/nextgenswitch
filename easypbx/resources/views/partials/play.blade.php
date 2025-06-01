
<input type="hidden" id="preview_route" value="{{ route('voice_files.voice.preview', 0) }}";
@push('script')
<script src="{{ asset('js/play.js') }}"> </script>
<script>
$(document).ready(function(){
    if($("#function_id").val() !=  undefined && $("#function_id").val() == 'play_voice'){
$(".btn-play").removeClass('d-none')
    }

    $(document).on('change', '#function_id', function(){
        if($("#function_id").val() !=  undefined && $("#function_id").val() == 'play_voice'){
$(".btn-play").removeClass('d-none')
    }
    else{

        $(".btn-play").addClass('d-none')
    }
    });


  $(document).on('change', '#destination_id', function(){
    $(this).parent().attr('data-vid', $(this).val())
    $(this).parent().addClass('vid_' + $(this).val())
  })  
})
</script>
@endpush