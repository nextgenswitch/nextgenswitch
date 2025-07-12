$(document).ready(function () {
    path = $('.ajaxForm').attr('path');

    console.log(path);

    $('.ajaxForm').on('change', '.function_id', function (e) {
        e.preventDefault();

        var val = $(this).val().trim();
        var functionEl = $(this).closest('.column');
        // console.log(functionEl)

        var nextDestinationEl = functionEl.next('.column').find('.destination_id');
        // console.log(nextDestinationEl)

        if (val != undefined && val != '') {
            nextDestinationEl.prop('required',true);
            route = path.trim().slice(0, -1) + val;
            console.log(route);

            $.get(route, function (res) {
                console.log(res);
                // $(".destination_id").html(res);
                nextDestinationEl.html(res)
            })

        }
        else{
            nextDestinationEl.prop('required',false);
            nextDestinationEl.html('<option value=""> Select destination </option>');
        }
            

    })
})