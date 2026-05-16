function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#preview_img').attr('src', e.target.result).height(150);

        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#image").change(function () {
    readURL(this);
});

$(document).ready(function(){

    $('.datepicker').datepicker({
        autoclose: true
    });
    if($(document).hasClass('d-table')){
        //$(".d-table").DataTable({responsive: true});
    }



    $('input.float').on('input', function() {

        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    });
    $('input.integer').on('input', function() {

        this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');
    });
    //$(".d-table").DataTable();

})
$('#country').change(function () {
    var id = $(this).val();
    if(id==''){
        id=0;
    }
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });
    $.ajax({url: base_url + '/getcity/' + id}).done(function (response) {
        response = $.parseJSON(response);

        html = '';

        $(response['city']).each(function (key, value) {

            html += '<option value="' + value['id'] + '">' + value['city_name'] + '</option>';
        });
        if(html==""){
            html += '<option value="">' + 'Select' + '</option>';
            $('#city').html(html);
            alert("City Not Found");
        }else{
            $('#city').html(html);
        }


    });

});

$('#category').change(function () {
    var id = $(this).val();
    if(id==''){
        id=0;
    }

    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });

    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });

    $.ajax({url: base_url + '/getSubCategories/' + id}).done(function (response) {
        response = $.parseJSON(response);

        html = '';

        $(response['sub_categories']).each(function (key, value) {

            html += '<option value="' + value['id'] + '">' + value['subcategory'] + '</option>';
        });

        if(html==""){

            html += '<option value="">' + 'Select' + '</option>';
            $('#subcategory').html(html);
            alert("Sub Category Not Found");
        }else{

            $('#subcategory').html(html);
        }


    });

});
