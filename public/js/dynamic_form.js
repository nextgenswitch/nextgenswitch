$(document).ready(function() {
    $crud = $('#crud_contents').crud();
    
    $('.dynamicForm').on('click', '.btn-plus', function(e) {
        e.preventDefault();
        const originalRow = $(this).closest('tr');
        const copiedRow = originalRow.clone();
        // copiedRow.find('.btn-minus').remove();
    
        originalRow.find('td:last').html('<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button>');

    
        copiedRow.find('input').val('');

    
        copiedRow.find('select').prop('selectedIndex', 0);
        copiedRow.find('input[type="checkbox"]').prop('checked', false);

    
        originalRow.after(copiedRow);
    });

    
    $('.dynamicForm').on('click', '.btn-minus', function(e) {
        e.preventDefault();
        console.log($(".dynamicForm tr").length)

        if($(".dynamicForm tr").length > 2){
            const selectedRow = $(this).closest('tr');

            if(selectedRow.find('.btn-plus').length > 0){
                const prevRow = selectedRow.prev();
                console.log(prevRow)
                prevRow.find('td:last').html('<button class="btn btn-danger btn-minus"> <i class="fa fa-minus"></i> </button><button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button>');
            }

            selectedRow.remove();
        }
        
    });

    
    $(".ajaxForm").on('change', '.extension_id', function(e){
        const _this = this;
        const current_item_digit = $(_this).val().trim();

        console.log(current_item_digit);

        console.log($('.extension_id').not(_this))

        $('.extension_id').not(_this).each((index, item) => {

            if( $(item).val().trim() == current_item_digit){
                // $(_this).val('');
                $(_this).prop('selectedIndex', 0);
                $crud.showToast("The extension already selected");
            }
        });
        
    })  

});