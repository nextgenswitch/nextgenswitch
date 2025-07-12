(function ($) {
    "use strict";
    $.fn.crud = function (options) {

        // Settings
        var settings = $.extend({
            newText: "Yabadabado"
        }, options);

        var $this = $(this);
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return '';
        };

        var uriParams = {
            per_page: $this.val(),
            q: $('#search').val() != undefined ? $('#search').val() : '',
            filter: getUrlParameter('filter'),
            sort: getUrlParameter('sort'),
        }

        console.log($.param(uriParams));

        var setUrlParam = function setUrlParam(param, val){
            uriParams[param] = val
        }

        function replaceUrlParam(ourl, paramName, paramValue)
        {
            let url = new URL(ourl); 

            if(url.searchParams.has(paramName)){
                if(paramValue) url.searchParams.set(paramName, paramValue);
                else url.searchParams.delete(paramName);
                //if(paramValue) console.log("here" + paramName + ' ' + paramValue);
            }else
                url.searchParams.append(paramName, paramValue);

           return url.toString();
        }
        
        var getUrl = function(){
            var OUrl = window.location.href;
            for (const [key, value] of Object.entries(uriParams)) {
                //console.log(`Key: ${key}, Value: ${value}`);
                //OUrl.replace(/(${key}=).*?(&)/,'$1' + ${value} + '$2');
                OUrl = replaceUrlParam(OUrl,key,value);
            }
            return OUrl

        }

        
  
        var Url = location.protocol + '//' + location.host + location.pathname + '?';
        //console.log(Url);

        function showToast(message, success = true) {

            let toast = {
                title: (success) ? "Success" : "Failed",
                message: message,
                status: (success) ? TOAST_STATUS.SUCCESS : TOAST_STATUS.DANGER,
                timeout: 5000
            }
            Toast.create(toast);
        }


        // var $crud = $('#crud_contents');
        var $sortable = $('.sortable');

        $this.on('reloadSort', function (event, sortBy) {
            console.log("reloading sort here", sortBy)
            if (sortBy === undefined || sortBy == '') return;
            var sort = sortBy.split(":");
            $('.sortable').each(function () {
                var $_this = $(this);
                if ($_this.attr('sort-by') == sort[0]) {
                    $_this.addClass((sort[1]))
                }
            });

        });

        $this.on('reloadCrud', function () {
            console.log('reloading crud here');
            feather.replace();
            $('.selectpicker').selectpicker();
            if (uriParams.sort !== undefined && uriParams.sort != '') {
                var $sortable = $('.sortable');
                $this.trigger('reloadSort', uriParams.sort);

            }

        });


        function reload_data() {
            $this.append('<div class="overlay-spinner text-center"><div class="spinner-grow text-primary"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-secondary"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-success"><span class="sr-only">Loading...</span></div></div>');
            console.log(getUrl());
            $.get(getUrl(), function (data, status) {
                //console.log("Data: " + data + "\nStatus: " + status);
                $this.html(data);
                $this.trigger('reloadCrud');

            });

        }


        var sortBy = getUrlParameter('sort')

        $this.trigger('reloadSort', sortBy)

        $this.on('click', '.sortable', function () {
            var $_this = $(this);
            var asc = $_this.hasClass('asc');
            var desc = $_this.hasClass('desc');
            var field = $_this.attr('sort-by');
            //console.log(asc,desc,field);
            if (asc || (!asc && !desc))
                sortBy = field + ':desc';
            else
                sortBy = field + ':asc';

            $sortable.removeClass('asc').removeClass('desc');
            if (desc || (!asc && !desc)) {
                $_this.addClass('asc');
            } else {
                $_this.addClass('desc');
            }

            uriParams.sort = sortBy;
            reload_data();

        });

        $('#crud_per_page').change(function () {
            uriParams.per_page = $(this).val();
            reload_data();
        });




        $this.on('change', '.editableForm', function () {

            var form = $(this);
            var actionUrl = form.attr('action');
            $.ajax({
                type: "PUT",
                url: actionUrl,
                data: form.serialize(), // serializes the form's elements.
                success: function (data) {
                    console.log(data); // show response from the php script.
                    reload_data();
                }
            });
        });


        $this.on('submit', '.deleteFrm', function (e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(), // serializes the form's elements.
                success: function (data) {
                    if (data.success == true) {
                        showToast("Succesfully deleted");
                        reload_data();
                    } else
                        showToast("Failed to  delete", false);
                }
            });

        });

        $('#btn-refreash').click(function () {
            reload_data();
        });

        $('#search').keyup(function (event) {
            if (event.which === 13) {
                uriParams.q = $(this).val() != undefined ? $(this).val() : '';
                reload_data();
            }
        });

        $('#btnSearch').click(function (event) {

            uriParams.q = $('#search').val() != undefined ? $('#search').val(): '';
            reload_data();

        });

        $('#mass_delete').click(function () {
            $('#bulk_fields').toggle();
        });

        $this.on('click', '#checkAll', function () {
            $('.idRow').not(this).prop('checked', this.checked);
        });




        $('#mass_submit').click(function () {

            var form = $('#massActionFrm');
            var actionUrl = form.attr('action');
            var ids = [];
            $('.idRow:checkbox:checked').each(function () {
                ids.push($(this).val());
            });

            var data = form.serializeArray();
            data.push({ name: 'ids', value: ids });
            if ($('#massActionFrm').find('input[name="mass_delete"]').is(':checked')) {
                if (confirm("This will delete all checked data, Are you sure?") == false)
                    return;
            }

            $.ajax({
                type: "PUT",
                url: actionUrl,
                data: data, // serializes the form's elements.
                success: function (data) {
                    console.log(data); // show response from the php script.
                    if (data.success == true) {
                        showToast("Succesfully applied bulk actions");
                        reload_data();
                    } else
                        showToast("Failed to  apply bulk actions", false);

                }
            });

            $('#bulkActionModal').modal('toggle');
        });

        $('#printTable').click(function () {

            $this.printThis();
        });

        $('#csvD').click(function () {
            uriParams.csv = 1;
			console.log(uriParams);
            var a = document.createElement('a');
            a.href = getUrl();
            a.click();
            delete uriParams.csv;
        });

        $('#FormModal .btnSave').click(function (e) {

            $('#FormModal #btnSubmit').click();
        });

        $('#FormModal').on('submit', 'form', function (e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.
            var form = $('#FormModal form');
            var actionUrl = form.attr('action');
            $('#FormModal .modal-body .alert').remove();
            $('#FormModal input').removeClass('is-invalid');
            $('#FormModal select').removeClass('is-invalid');
            $('#FormModal .modal-body .help-block').remove();
            $.ajax({
                type: form.attr('method'),
                url: actionUrl,
                data: form.serialize(), // serializes the form's elements.
                success: function (data) {
                    $('#FormModal').modal('toggle');
                    showToast("Succesfully saved");
                    reload_data();


                },
                error: function (response) {
                    $('#FormModal .modal-body').prepend('<div class="alert alert-danger" role="alert">' + response.responseJSON.message + '</div>');

                    if (response.responseJSON.hasOwnProperty('errors')) {
                        $.each(response.responseJSON.errors, function (i, item) {
                            $('#FormModal #' + i).addClass('is-invalid');
                            $('<p class="help-block text-danger">' + item[0] + '</p>').insertAfter('#FormModal #' + i);
                        });
                    }

                    //console.log(response.responseJSON);
                }
            });


        });

        var loadForm = function (e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            console.log('Loading form by ajax');
            var url = $(this).attr("href");
            var title = $(this).attr("title");
            
            $.ajax({
                type: "GET",
                url: url,
                success: function (res) {
                    // update modal content

                    $('#FormModal .modal-title').text(title);
                    $('#FormModal .modal-body').html(res);
                    // show modal
                    $('#FormModal').modal('show');

                },
                error: function (request, status, error) {
                    console.log("ajax call went wrong:" + request.responseText);
                }
            });
        };

        $('.btnForm').click(loadForm);
        $this.on('click', '.btnForm', loadForm);



        return {
            setUrlParam: setUrlParam,
            getUrlParameter: getUrlParameter,
            showToast: showToast,
            reload_data: reload_data,
            loadForm: loadForm
        }
    };



    // init plugin.


}(jQuery));

