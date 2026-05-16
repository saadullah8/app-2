/**
 * Created by umusmani on 5/18/2019.
 */
//------------adding n minus vale in popup-----------
$(document).on('change', '.checkboxCustomizeProduct', function() {
    var price=$(this).data('productprice');
    var id=$(this).data('productid');
    var qtyInputId=$(this).data('qtyinput');
    var qty=$('#'+qtyInputId).val();
    var extraPriceId=$(this).data('extrapriceids');
    var ary=extraPriceId.split(',')
    var  extra_price=0;

    ary.forEach(function (item, index, arr){
        if(item!=''){
            if ($('#'+item).is(":checked")){
                var priceE=$('#'+item).data('extraprice')
                extra_price +=  parseFloat(priceE);
            }
        }
    })
    price +=parseFloat(extra_price);
    var productPrice=parseFloat(formatLimitDecimals(price,2));
    var total=productPrice*qty;
    $('#total_d_price_'+id).text(parseFloat(formatLimitDecimals(total,2)));

});
function formatLimitDecimals(value, decimals) {
    value = value.toString().split('.')

    if (value.length === 2) {
        return Number([value[0], value[1].slice(0, decimals)].join('.'))
    } else {
        return Number(value[0]);
    }
}

$('.add').click(function () {
   var id=$(this).data('id');
   var price=$(this).data('price');
   var extraPriceId=$(this).data('extrapriceid');
   if(extraPriceId){
       var ary=extraPriceId.split(',')
       var  extra_price=0;
       ary.forEach(function (item, index, arr){
           if(item!=''){
               if ($('#'+item).is(":checked")){
                   var priceE=$('#'+item).data('extraprice')
                   extra_price +=  parseFloat(priceE);
               }
           }
       })
       price +=parseFloat(extra_price);
   }

    if ($(this).prev().val() < 50) {
        $(this).prev().val(+$(this).prev().val() + 1);
        var total=price*$(this).prev().val();
        $('#total_price_'+id).val(parseFloat(formatLimitDecimals(total,2)));
        $('#total_d_price_'+id).text(parseFloat(formatLimitDecimals(total,2)));
    }
});

$('.sub').click(function () {
    var id=$(this).data('id');
    var price=$(this).data('price');
    var extraPriceId=$(this).data('extrapriceid');
    if(extraPriceId){
        var ary=extraPriceId.split(',')
        var  extra_price=0;
        ary.forEach(function (item, index, arr){
            if(item!=''){
                if ($('#'+item).is(":checked")){
                    var priceE=$('#'+item).data('extraprice')
                    extra_price +=  parseFloat(priceE);
                }
            }
        })
        price +=parseFloat(extra_price);
    }
    if ($(this).next().val() > 1) {
        if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
        var total=price*$(this).next().val();
        $('#total_price_'+id).val(parseFloat(formatLimitDecimals(total,2)));
        $('#total_d_price_'+id).text(parseFloat(formatLimitDecimals(total,2)));
    }
});


$(document).ready(function() {

    $("#myModal").on("click", ".alert_removed", function(e) {
        e.preventDefault();
        var result= confirm("Are you sure want to remove item?");
        if(result){
            var deleted_id=$(this).data("remove_id");
            window.location.href =base_url+'/remove/meal/'+deleted_id;
        }
    });

});

$( ".alert_cleared" ).on( "click", function(e) {
    e.preventDefault();
    var result= confirm("Are you sure want to Clear Cart?");
    if(result){
        window.location.href =base_url+'/clear/cart';
    }
});

$("#cart_click").click(function (event) {
    if ($(this).hasClass("disabled")) {
        event.preventDefault();
    }
    $(this).addClass("disabled");
});


