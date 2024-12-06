document.addEventListener('DOMContentLoaded', function () {

    $('.select2').select2();

    $('#department').change(function () {
        $('#permissions-container').empty();
        const selectedDepartments = $(this).val();
        if (selectedDepartments) {
            selectedDepartments.forEach(department => {
                $('#permissions-container').append(`
            <div class="col-12 mb-15">
                <label for="" class="form-label">Select Permissions for ${department}</label>
                <select name="permissions[${department}][]" class="form-control select2" multiple required>
                    <option value="read">Read</option>
                    <option value="write">Write</option>
                </select>
            </div>
        `);
            });
            $('.select2').select2();
        }
    });

    $('#create-role-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        $.ajax({
            type: 'POST',
            url: './create-role',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {

                    Command: toastr["success"](response.message, "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": 300,
                        "hideDuration": 1000,
                        "timeOut": 3000,
                        "extendedTimeOut": 1000,
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    setTimeout(function () {
                        location.reload();
                    }, 3000);

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }

            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.'
                });
            }
        });
    });

    $('#create-role-user-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        console.log(formData);
        $.ajax({
            type: 'POST',
            url: './create-user?type=role',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {

                    Command: toastr["success"](response.message, "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": 300,
                        "hideDuration": 1000,
                        "timeOut": 3000,
                        "extendedTimeOut": 1000,
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }

            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.'
                });
            }
        });
    });

    $('#create-admin-user-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        console.log(formData);
        $.ajax({
            type: 'POST',
            url: './create-user?type=admin',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {

                    Command: toastr["success"](response.message, "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": 300,
                        "hideDuration": 1000,
                        "timeOut": 3000,
                        "extendedTimeOut": 1000,
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }

            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.'
                });
            }
        });
    });

    $('#update-password-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        console.log(formData);
        $.ajax({
            type: 'POST',
            url: './update-password',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {

                    Command: toastr["success"](response.message, "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": 300,
                        "hideDuration": 1000,
                        "timeOut": 3000,
                        "extendedTimeOut": 1000,
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }

            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.'
                });
            }
        });
    });

});
