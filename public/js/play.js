$(function () {

    var audio = new Audio();
    function showToast(message, success = true) {

        let toast = {
            title: (success) ? "Success" : "Failed",
            message: message,
            status: (success) ? TOAST_STATUS.SUCCESS : TOAST_STATUS.DANGER,
            timeout: 5000
        }
        Toast.create(toast);
    }


    $(document).on("click", ".voice-preview .play", function (e) {
        e.preventDefault();


        const parent = $(this).closest('.voice-preview');
        //console.log(parent)

       
        if( $(this).attr('src') !== undefined && $(this).attr('src').length > 0 ){          
            var src = $(this).attr('src');
            audio.src = src;
            audio.loop = false;
            audio.play();

            parent.find('.play').addClass('d-none');
            parent.find('.stop').removeClass('d-none');

            return;
        }else{
            var voice_file_id = $(this).attr('voice_file_id');
            if (typeof voice_file_id == 'undefined' || voice_file_id == false) {
                
                if (parent.find('select').length > 0) {
                 
                    voice_file_id = parent.find('select').val();
                }else{
                    showToast("media source not found",false);
                    return;
                }

            }
            
            var path = window.media_play + "?voice_file_id=" + voice_file_id          
            $.get(path, function (res) {
                if (res.status) {
                    btnReset()

                    audio.src = res.path;
                    audio.loop = false;
                    audio.play();

                    parent.find('.play').addClass('d-none');
                    parent.find('.stop').removeClass('d-none');

                } 
                else if (res.status == false) {

                    showToast("Voice file not found for play");

                } else {

                    showToast("There was something went wrong. please try again later.");

                }

            })

        }



    })
	
	
    audio.addEventListener('ended', function () {
        btnReset()
    });

    $(document).on("change", ".voice-preview select", function (e) {
        e.preventDefault();
        audio.pause();
        btnReset();
    })

    $(document).on("click", ".voice-preview .stop", function (e) {
        e.preventDefault();
        audio.pause();

        const parent = $(this).closest('.voice-preview');
        parent.find('.play').removeClass('d-none');
        parent.find('.stop').addClass('d-none');

    });


    function btnReset() {
        $('.voice-preview .play').each((index, item) => {
            $(item).removeClass('d-none');
        })

        $('.voice-preview .stop').each((index, item) => {
            $(item).addClass('d-none');
        })

    }
})