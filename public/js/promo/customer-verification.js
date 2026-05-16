$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function isValidPromoCode(){
    $.LoadingOverlay("show");
    $('#promoSubmitter').prop('disabled', true);
    var promoCode = $("#inputPassword2").val();
    var discountPrice=0;
    if(promoCode==''){
        $.LoadingOverlay("hide");
        $('#discountValue').html('0%');
        $('#displayDiscount').html(0)
        $('#totalOrderPrice').html(subtotalValue)
    }else{
        $.ajax({
            type: "POST",
            url: base_url+'/customer/promo-code/'+promoCode,
            dataType: "json",
            success: function(res) {
                if(res.status){
                    var  data=res.data;
                    var discountValue=data.discountValue;

                    $('#errorPromo').html('').hide();
                    $('#successPromo').html(res.message).show();
                    $('#discountValue').html(discountValue+'%');
                    $('#discountCode').val(data.code);

                    var discountedPrice=parseFloat(parseFloat(discountValue)/100)*parseFloat(subtotalValue);
                    var afterDiscountPrice=parseFloat(subtotalValue)-parseFloat(discountedPrice.toFixed(2));

                    $('#displayDiscount').html(discountedPrice.toFixed(2))
                    $('#totalOrderPrice').html(afterDiscountPrice.toFixed(2))
                }else{
                    $('#discountCode').val('');
                    $('#discountValue').html('0%');
                    $('#displayDiscount').html(0)
                    $('#totalOrderPrice').html(subtotalValue)
                    $('#errorPromo').html(res.message).show();
                    $('#successPromo').html('').hide();
                }
                $.LoadingOverlay("hide");
                setTimeout(function() {
                    $("#successPromo").hide(500)
                    $("#errorPromo").hide(500)
                }, 5000);
            },
            error: function (res) {
                $('#errorPromo').append(res.message).show();
                $.LoadingOverlay("hide");
                setTimeout(function() {
                    $("#successPromo").hide(500)
                    $("#errorPromo").hide(500)
                }, 5000);
            }
        });
    }

}
