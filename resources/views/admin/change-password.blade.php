@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class='text-center'>Update Your Password</h3>
        </div>
        <div class="card-body">

            <div class="col-md-7" style="margin:0px auto">

                <form class="" action="" enctype="multipart/form-data" method="post" id="update-password-form">
                    @csrf
                    <div class=" form-group pt-3">
                        <label for="" class="form-label">Current Password:</label>
                        <input type="password" class="form-control" name="current-password"
                            placeholder="Enter your current password" required>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">New Password:</label>
                        <input type="password" class="form-control" name="new-password"
                            placeholder="Enter your new password" required>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">Confirm New Password:</label>
                        <input type="password" class="form-control" name="new-password_confirmation"
                            placeholder="Confirm your new password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-form">Change Password</button>
                    </div>


                </form>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#update-password-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.updatePassword') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", {
                                closeButton: true,
                                positionClass: "toast-top-right",
                                timeOut: 3000
                            });
                            setTimeout(function() {
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
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred.'
                        });
                    }
                });
            });
        });
    </script>
@endsection
