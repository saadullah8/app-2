function deleteItem(deletedPath,tableId,tr) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: 'post',
            data: {'_method': 'DELETE'},
            url: deletedPath
        }).done(function (response) {
            if (response.status == true) {

                Swal.fire("Deleted!",response.message, "success");
                var table = $('#'+tableId).DataTable().ajax.reload();

                // table.row(tr).remove().draw();
            }else{
                swal.fire("Cancelled",response.message, "error");
            }

        }).fail(function (response) {
            if (response.status == false) {

                swal.fire("Cancelled",response.message, "error");
            }
        });
    }
})
}

