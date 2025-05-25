$("#dialer_ajax_content .login-form").submit(function(e) {
    e.preventDefault();

    var form = $("#dialer_ajax_content .login-form");
    var actionUrl = form.attr('action');

    console.log(actionUrl);
    
    $("#dialer_ajax_content .loader").removeClass('v-hidden');

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), // serializes the form's elements.
        success: function(data, message, xhr) {
            console.log(data, message, xhr.status)
            
            if (data.status == 'error') {
                console.log(data.errors)
                clearErrorForm(form);

                $.each(data.errors, (key, item) => {
                    console.log(key, item);

                    var input = form.find('#' + key).addClass('is-invalid');

                    var invalid_feedback = input.closest('.form-group')
                        .find('.invalid-feedback strong');

                    invalid_feedback.text(item);

                })

                $("#dialer_ajax_content .loader").addClass('v-hidden');
            }

            if (data.status == 'success') {
                clearErrorForm(form);
                resetForm(form);
                form.addClass('d-none');
                
                $("#dialer_ajax_content .call-dialoge").removeClass('d-none');
                $("#dialer_ajax_content .loader").addClass('v-hidden');
            }

        }
    });

    function clearErrorForm(activeForm) {
        var is_invalids = activeForm.find('.is-invalid');

        is_invalids.each((index, item) => {
            $(item).removeClass('is-invalid');
        });

        var invalid_feedbacks = activeForm.find('.invalid-feedback strong')
        invalid_feedbacks.each((index, item) => {
            $(item).text('');
        });

    }

    function resetForm(activeForm) {
        activeForm.trigger("reset");
    }

})