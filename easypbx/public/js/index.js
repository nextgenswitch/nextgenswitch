  $(function() {

        
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
            per_page:$('#crud_per_page').val(),
            q:$('#search').val(),
            filter:getUrlParameter('filter'), 
            sort:getUrlParameter('sort'), 
            page:getUrlParameter('page')
        }   
        
        console.log($.param(uriParams));

        var Url = location.protocol + '//' + location.host + location.pathname + '?'; 
        console.log(Url);
        function showToast(message,success = true){

            let toast = {
                title: (success)?"Success":"Failed",
                message: message,
                status: (success)?TOAST_STATUS.SUCCESS:TOAST_STATUS.DANGER,
                timeout: 5000
            }
            Toast.create(toast);
        }

      var $crud = $('#crud_contents');
      var $sortable = $('.sortable');

      $crud.on('reloadSort',function(event,sortBy){
          console.log("reloading sort here",sortBy)
          if(sortBy === undefined || sortBy == '') return;
          var sort = sortBy.split(":");
          $('.sortable').each(function(){
            var $this = $(this);
            if($this.attr('sort-by') == sort[0]){
                $this.addClass((sort[1]))
            }
          });  

      });
      
      $crud.on('reloadCrud', function(){
         console.log('reloading crud here');
         feather.replace();
        $('.selectpicker').selectpicker();
        if(uriParams.sort !== undefined && uriParams.sort != ''){
            var $sortable = $('.sortable');
            $crud.trigger('reloadSort',uriParams.sort);
                
        }
          
      });

      function reload_data(){
          $('#crud_contents').append('<div class="overlay-spinner text-center"><div class="spinner-grow text-primary"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-secondary"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-success"><span class="sr-only">Loading...</span></div></div>');
           $.get(Url + $.param(uriParams), function(data, status){
             //console.log("Data: " + data + "\nStatus: " + status);
             $crud.html(data);
             $crud.trigger('reloadCrud');
             
          });

      }

      


      
      var sortBy = getUrlParameter ('sort') 

      $crud.trigger('reloadSort',sortBy)

      $crud.on('click','.sortable', function(){
          var $this = $(this);
          var asc = $this.hasClass('asc');
          var desc = $this.hasClass('desc');
          var field = $this.attr('sort-by');
          //console.log(asc,desc,field);
          if(asc || (!asc && !desc))
             sortBy = field + ':desc';
          else 
             sortBy = field + ':asc';

          $sortable.removeClass('asc').removeClass('desc');
          if (desc || (!asc && !desc)) {
            $this.addClass('asc');
          } else {
            $this.addClass('desc');
          }

          uriParams.sort = sortBy;
          reload_data();
              

       });



    $('#crud_per_page').change(function(){
        uriParams.per_page = $(this).val();
        reload_data();
    });


  

    $crud.on('change','.editableForm',function(){
                
        var form = $(this);
        var actionUrl = form.attr('action');
        $.ajax({
            type: "PUT",
            url: actionUrl,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
              console.log(data); // show response from the php script.
              reload_data();
            }
        });

       
    });


    $crud.on('submit','.deleteFrm',function(e) {
        
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var actionUrl = form.attr('action');
        
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
              if(data.success == true){
                showToast("Succesfully deleted");
                reload_data();
                }else
                showToast("Failed to  delete",false);
            }
        });
        
    });

   


    $('#btn-refreash').click(function(){
        reload_data();
    });

    $('#search').keyup(function(event){
       if (event.which === 13) {
        uriParams.q = $(this).val();
        reload_data();
        }
    });

    $('#btnSearch').click(function(event){
      
        uriParams.q = $('#search').val();
        reload_data();
       
    });

    $('#mass_delete').click(function(){
        $('#bulk_fields').toggle();
    });

    $('#crud_contents').on('click','#checkAll',function(){
        $('.idRow').not(this).prop('checked', this.checked);
    });


 

    $('#mass_submit').click(function(){
      
        var form = $('#massActionFrm');
        var actionUrl = form.attr('action');
        var ids = [];
        $('.idRow:checkbox:checked').each(function () {
            ids.push($(this).val()) ;
        });
        
        var data = form.serializeArray();
        data.push({name:'ids',value:ids});
        if($('#massActionFrm').find('input[name="mass_delete"]').is(':checked')){
              if(confirm("This will delete all checked data, Are you sure?") == false)
              return;
        }

        $.ajax({
            type: "PUT",
            url: actionUrl,
            data: data, // serializes the form's elements.
            success: function(data)
            {
              console.log(data); // show response from the php script.
              if(data.success == true){
                showToast("Succesfully applied bulk actions");
                reload_data();
                }else
                showToast("Failed to  apply bulk actions",false);

            }
        });

        $('#bulkActionModal').modal('toggle');
    });

    $('#printTable').click(function(){

        $("#crud_contents").printThis();
    });

    $('#csvD').click(function(){
        uriParams.csv = 1;
       
        var a = document.createElement('a');
        a.href = Url + $.param(uriParams);
        a.click();
        delete uriParams.csv;
    });

    $('#FormModal .btnSave').click(function(e){

        $('#FormModal #btnSubmit').click();
    });    

    $('#FormModal').on('submit','form',function(e) {

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
            success: function(data)
            {
               $('#FormModal').modal('toggle');
               showToast("Succesfully saved");
               reload_data();


            },
            error: function(response) {
                $('#FormModal .modal-body').prepend('<div class="alert alert-danger" role="alert">' + response.responseJSON.message + '</div>');
                
                if(response.responseJSON.hasOwnProperty('errors')){
                    $.each(response.responseJSON.errors, function(i, item) {
                        $('#FormModal #' + i).addClass('is-invalid');
                        $('<p class="help-block text-danger">' + item[0] + '</p>').insertAfter('#FormModal #' + i);
                    });
                }

                //console.log(response.responseJSON);
            }    
        });
        

    }); 

    var loadForm = function(e){
        var url = $(this).attr("href"); 
        var title = $(this).attr("title"); 
        e.preventDefault(); // avoid to execute the actual submit of the form.
        $.ajax({
            type: "GET",
            url: url,
            success: function(res) {
                
              
                // update modal content
                
                $('#FormModal .modal-title').text(title);
                $('#FormModal .modal-body').html(res);
                // show modal
                $('#FormModal').modal('show');
                
            },
            error:function(request, status, error) {
                console.log("ajax call went wrong:" + request.responseText);
            }
        });
    };   

    $('.btnForm').click(loadForm);
    $crud.on('click','.btnForm',loadForm);
        


});