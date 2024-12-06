@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class='text-center'>Create New Role</h3>
        </div>
        <div class="card-body">

            <div class="col-md-7" style="margin:0px auto">

                <form class="" action="" enctype="multipart/form-data" method="post" id="create-role-form">
                    @csrf
                    <div class=" form-group pt-3">
                        <label for="" class="form-label">Role Name</label>
                        <input type="text" class="form-control" name="role-name" placeholder="Enter role name" required>

                    </div>
                    <div class="form-group">
                        <label for="">Select Inventory</label>
                        <select name="inventory[]" id="inventory" class="form-control select2" multiple required>

                            @foreach ($companies as $company)
                                <option value="{{ $company->company_id }}">{{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Select Department</label>
                        <select name="department[]" id="department" class="form-control select2" multiple required>
                            <option value="FR">FR</option>
                            <option value="DC">DC</option>
                            <option value="INS">INS</option>
                        </select>
                    </div>
                    <div class="form-group" id="permissions-container">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-form">Create Role</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2();

            $('#department').change(function() {
                $('#permissions-container').empty();
                const selectedDepartments = $(this).val();
                if (selectedDepartments) {
                    selectedDepartments.forEach(department => {
                        $('#permissions-container').append(`
                <div class="form-group">
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

            $('#create-role-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.createRole') }}',
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
