document.addEventListener("DOMContentLoaded", function () {
    $("#create-category-form").submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        $.ajax({
            type: "POST",
            url: "./add-category",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Command: toastr["success"](response.message, "Success");
                    toastr.options = {
                        closeButton: true,
                        positionClass: "toast-top-right",
                        preventDuplicates: false,
                        onclick: null,
                        showDuration: 300,
                        hideDuration: 1000,
                        timeOut: 3000,
                        extendedTimeOut: 1000,
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                    };

                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An unexpected error occurred.",
                });
            },
        });
    });

    $("#create-status-form").submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        $.ajax({
            type: "POST",
            url: "./add-status",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Command: toastr["success"](response.message, "Success");
                    toastr.options = {
                        closeButton: true,
                        positionClass: "toast-top-right",
                        preventDuplicates: false,
                        onclick: null,
                        showDuration: 300,
                        hideDuration: 1000,
                        timeOut: 3000,
                        extendedTimeOut: 1000,
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                    };

                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An unexpected error occurred.",
                });
            },
        });
    });

    $("#update-password-form").submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "./update-password",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Command: toastr["success"](response.message, "Success");
                    toastr.options = {
                        closeButton: true,
                        positionClass: "toast-top-right",
                        preventDuplicates: false,
                        onclick: null,
                        showDuration: 300,
                        hideDuration: 1000,
                        timeOut: 3000,
                        extendedTimeOut: 1000,
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                    };

                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An unexpected error occurred.",
                });
            },
        });
    });
});
