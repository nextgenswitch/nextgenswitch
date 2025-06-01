$(document).ready(function(){
    $('#crud_contents').on('click', '.show-pass', function(e) {
        var pass = $(this).attr('pass');

        var td = $(this).closest('td');

        var el_pass = td.find('.pass-val')

        if(el_pass.length > 0){
            el_pass.text(pass)
            $(this).addClass('hide-pass')
            $(this).removeClass('show-pass')

            var itag = $(this).find('i');

            itag.addClass('fa-eye-slash');
            itag.removeClass('fa-eye');
        }

    })

    $('#crud_contents').on('click', '.hide-pass', function(e) {
        
        var td = $(this).closest('td');
        var el_pass = td.find('.pass-val')

        if(el_pass.length > 0){
            el_pass.text("*****")
            $(this).removeClass('hide-pass')
            $(this).addClass('show-pass')

            var itag = $(this).find('i');

            itag.removeClass('fa-eye-slash');
            itag.addClass('fa-eye');
        }

    });
})