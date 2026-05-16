$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// define the function
var popupShowOnChange = function () {
    var productId=$('#productAdded').val();
    $('#productid-'+productId).modal('show');
};
// define the function
var popupShowOnChangeOrder = function () {
    var productId=$(this).data('orderdetail');
    $('#orderId-'+productId).modal('show');
};
// set the handler
$('#productAdded').on('change', popupShowOnChange);
$("#removetem").on("click", ".deleted", function(e) {
    var deleted_id=$(this).data("set_id");
    $('#deleted_item').val(deleted_id);
    $("#deleted_model").modal('show');
});
$("#removetem").on("click", ".editItems", popupShowOnChangeOrder);
