@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class='text-center'>Create User</h3>
        </div>
        <div class="card-body">

            <div class="col-md-7" style="margin:0px auto">

                <div class="col-12 mb-20">
                    <h6 class="mb-15">Select Type of User:</h6>
                    <div class="checkbox-radio-group inline">
                        <label class="dash-radio"><input type="radio" name="user-type" id="role-based"> <i
                                class="icon"></i>
                            Role-Based User</label>
                        <label class="dash-radio"><input type="radio" name="user-type" id="admin-type"> <i
                                class="icon"></i>
                            Admin</label>
                    </div>
                </div>

                <form class="" action="" enctype="multipart/form-data" method="post" id="create-role-user-form">
                    @csrf
                    <div id="role-based-form" style="display: none;">
                        <div class="form-group pt-3 mb-3">
                            <label for="name">Enter Firstname</label>
                            <input type="text" class="form-control" name="firstname" placeholder="Enter First Name"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="username">Enter Lastname</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Enter Last Name"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email-address"
                                placeholder="Enter users email address" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="mobile">Create User Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Create a password"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                placeholder="Confirm password" required>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-group">
                                <label for="sel1">Select user Role</label>
                                <select name="user-role" id="user-role" class="form-control" required>
                                    <option selected disabled>Select User Role</option>

                                    @foreach ($roles as $index => $roles)
                                        <option value="{{ $roles->role_id }}">{{ $roles->role_name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-form">Create User</button>
                        </div>
                    </div>
                </form>

                <form class="" action="" enctype="multipart/form-data" method="post"
                    id="create-admin-user-form">
                    @csrf
                    <div id="admin-type-form" style="display: none;">
                        <div class="form-group pt-3">
                            <label for="name">Enter Firstname</label>
                            <input type="text" class="form-control" name="firstname" placeholder="Enter First Name"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="username">Enter Lastname</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Enter Last Name"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email-address"
                                placeholder="Enter users email address" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Create User Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Create a password"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="password">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                placeholder="Confirm password" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-form">Create Admin User</button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var roleBasedRadio = document.getElementById("role-based");
            var otherUserRadio = document.getElementById("admin-type");
            var roleBasedForm = document.getElementById("role-based-form");
            var otherUserForm = document.getElementById("admin-type-form");

            function toggleDetails() {
                if (roleBasedRadio.checked) {
                    roleBasedForm.style.display = "block";
                    otherUserForm.style.display = "none";
                } else if (otherUserRadio.checked) {
                    roleBasedForm.style.display = "none";
                    otherUserForm.style.display = "block";
                }
            }

            roleBasedRadio.addEventListener("change", toggleDetails);
            otherUserRadio.addEventListener("change", toggleDetails);

            toggleDetails();

            $('#create-role-user-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.createUser', ['type' => 'role']) }}',
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

            $('#create-admin-user-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.createUser', ['type' => 'admin']) }}',
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
