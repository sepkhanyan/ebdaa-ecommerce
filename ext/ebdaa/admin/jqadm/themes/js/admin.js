$("#customCsvInput").on('change',function(){
    //set file name on input placeholder
    $('#import_csv_label').html(this.files[0].name)

    let url = '/product/validate/csv';
    let file_data = this.files[0];
    let formData = new FormData();
    formData.append('csv_file', file_data);

    //hide error message
    $('.csv_error_message').hide()

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (result) {
            if(result.success){
                $('#send_csv').css('opacity','1')
                $('#import_csv_label').removeClass("invalid-input").addClass("valid-input");
            }else{
                $('#import_csv_label').removeClass("valid-input").addClass("invalid-input");
                $('#send_csv').css('opacity','0.5')
            }
        },
        error: function (data) {

        }
    });
});

$('#send_csv').on('click',function () {

    let file_is_exist = $('#import_csv_label').hasClass('valid-input')

    if(file_is_exist){
        //show loader button
        $('#send_csv_loader').show();
        $('#send_csv').hide();

        let url = '/product/import-csv';
        let file_data = $('#customCsvInput').prop('files')[0];
        let current_site_name = $('#site_name').val();
        let formData = new FormData();
        formData.append('csv_file', file_data);
        formData.append('site', current_site_name);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if(result.success){
                    //refresh page
                    location.reload();
                }else {
                    //set and show error message
                    if(result.status === 'validation_error'){
                        let errorMessages = [];
                        $(result.message).each(function(i, m) {
                            errorMessages.push('<div> ' + m + '</div>');
                        });
                        $(".csv_error_message").html(errorMessages).show()
                    }else {
                        $('.csv_error_message').html(result.message).show()
                    }

                    //disable import button
                    $('#import_csv_label').removeClass("valid-input").addClass("invalid-input");
                    $('#send_csv').css('opacity','0.5')

                    //hide loading
                    $('#send_csv_loader').hide();
                    $('#send_csv').show();
                }
            },
            error: function (data) {

            }
        });
    }


})


$('#media_scale').on('click',function (){

    let current_site_name = $('#site_name').val();
    let url = '/product/media-scale/'+current_site_name;


    $.ajax({
        url: url,
        method: 'GET',
        contentType: false,
        processData: false,
        success: function (result) {
            if(result.success){
                location.reload();
            }else {
                alert('ERROR show in log');
            }
        },
        error: function (data) {
            alert('ERROR show in log');
        }
    });
})

