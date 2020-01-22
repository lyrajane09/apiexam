$(document).ready(function() {
    $('#orderstb').DataTable({
        "pageLength" : 10,
        "bLengthChange": false,
        "searching" : false,
        "order": [[ 4, "desc" ]]
    });

    //on change status
    $('#status').on('change', function(){
        if($(this).val() == 'for_pickup'){
            $('.pickup-address-container').show();
        }
        if($(this).val() == 'pending' || $(this).val() == 'confirmed'){
            $('.pickup-address-container').hide();
        }
    });

    // Add item
    $('#add-item').on('click', function(){
        var item = $('#order-create-form').find('.order-item:last');
        var result = checkFields(item);

        //if success append
        if(result == 1){
            var $newOrderItem = $('.js.order-item-orig .js.order-item').clone(true, true);
            $newOrderItem.appendTo($('.js.order-items'));
            $newOrderItem.find('.js.order-item-remove').removeClass('display-none');
            $newOrderItem.find('.description').val('');
            $newOrderItem.find('.amount').val('');
            $newOrderItem.find('.quantity').val('');
        }

        return false;   
    });

    //check fields
    function checkFields(item){
        var success = 1;
        $('.help-block').html('');
       
        //if description is null
        if(item.find('.description').val() == ''){
            item.find('.description_error').html('The description field is required');
            success = 0;
        }

        //if amount is null
        if(item.find('.amount').val() == '' || $.isNumeric(item.find('.amount').val()) == false){
            item.find('.amount_error').html('The amount field is required or invalid');
            success = 0;
        }

        //if quantity is null
        if(item.find('.quantity').val() == '' || item.find('.quantity').val() <= 0){
            item.find('.quantity_error').html('The quantity field is required');
            success = 0;
        }

        return success;
    }

    // Delete item
    $('.js.order-item-remove').on('click', function() {
        $(this).parent('.js.order-item').remove();
        return false;
    });

    //order create
    $('#order-create-form').on('submit', function(){
        $.ajax({ 
            url: 'orders/create',
            data: $(this).serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(response){
                $('.help-block').html('');
                if(Object.keys(response.errors).length > 0){
                    $.map(response.errors, function(value, key){
                        $('.'+key+'_error').html(value);
                    });
                }else{
                    if(response.status == 200){
                        alert('Successfully Imported');
                        window.location.reload(true);
                    }else{
                        alert('Something went wrong, please check your details and your order items');
                    }
                }
            }
        });
        return false;
    });

    //change country
    $('.country').on('change', function(){
        var $this = $(this);
        $.ajax({ 
            url: 'orders/country',
            data: {country:$(this).val()},
            dataType: 'json',
            type: 'POST',
            success: function(response){
                if(response.cities){

                    //change city dropdown
                    var cityDropDown = $this.parent().parent().find('.city');
                    cityDropDown.html('');
                    $.each(response.cities, function(key, value) {
                        cityDropDown.append($("<option></option>")
                        .attr("value", value.name).text(value.name));
                    });

                    //change state dropdown
                    var stateDropDown = $this.parent().parent().find('.state');
                    stateDropDown.html('');
                    $.each(response.states, function(key, value) {
                        stateDropDown.append($("<option></option>")
                        .attr("value", value.name).text(value.name));
                    });
                }
            }
        });
        return false;
    });

} );