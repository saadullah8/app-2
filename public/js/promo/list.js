$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var table = $('.productsTable').DataTable({
    "pageLength": 50,
    "processing": true,
    "stateSave": true,
    "dom": '<"top"fli>rt<"bottom"p><"clear">',
    "language": {
        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading Promo...</span> ',
        "emptyTable": "No data available "
    },
    responsive: {
        details: {
            display: $.fn.dataTable.Responsive.display.childRowImmediate,
            type: 'none',
            target: ''
        }
    },
    "ordering": true,
    ajax: {
        url: url,
        type: "GET"
    },

    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'code', name: 'Promo Code'},
        {data: 'discountValue', name: 'Discount Value'},
        {data: 'createdAt', name: 'Created Date'},
        {data: 'expiredAt', name: 'Expiry Date'},
        {data: 'status', name: 'Status'},
        {data: 'action', name: 'Actions', orderable: true, searchable: true},
    ]

});

function isDuplicatePromoCode(e){
    var promoCode = $(e).val();
    $.ajax({
        type: "POST",
        url: baseUrl+'/promos/'+promoCode,
        dataType: "json",
        success: function(res) {
            if(res.status){
                $('#errorMessage').append(res.message).show();
                $('#promoSubmitter').prop('disabled', true);

            }else{
                $('#errorMessage').html(' ').hide();
                $('#promoSubmitter').prop('disabled', false);
            }
        },
        error: function (res) {
            if(res.status){
                toastr.error(res.message);
            }

        }
    });
}

$("#editpromoModal #EditedexpiryDate").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy/mm/dd',
    minDate: 0
});
;
$("#addpromoModal #expiryDate").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy/mm/dd',
    minDate: 0
});
;

//delete button
$('table').on('click', 'tbody .promo_delete_btn', function () {
    if (!confirm('Are you sure you want to discontinue ?')) return false;
});
//edit
$('table').on('click', 'tbody .promo_edit_btn', function () {
    var id = $(this).attr('data-id');
    $.ajax({
        type: 'get',
        dataType: 'json',
        url: baseUrl + '/promos/' + id + '/edit',
        success: function (data) {
            var promo = data.data
            var timestamp = promo.expiredAt
            var d = new Date(timestamp * 1000)
            var day = ("0" + d.getDate()).slice(-2);
            var month = ("0" + (d.getMonth() + 1)).slice(-2);
            var today = d.getFullYear()+"-"+(month)+"-"+(day) ;
            $('#editpromoModal #EditedpromoCode').val(promo.code)
            $('#editpromoModal #EditeddiscountValue').val(promo.discountValue)
            $('#editpromoModal #EditedexpiryDates').val(today)
            $('#editpromoModal #Editedid').val(promo.id)
            $('#editpromoModal').modal('show')
        },
        error: function (data) {
            toastr.error('Something went wrong!')
        }

    });
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 &&
        (charCode < 48 || charCode > 57))
        return false;

    return true;
}
