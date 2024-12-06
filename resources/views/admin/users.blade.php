@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class='text-center'>System Users</h3>
        </div>
        <div class="card-body">
            <div class="col-12 mb-30">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered data-table data-table-default">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Date Added</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->fullname }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if (!is_null($user->role_name) && $user->role_name !== '')
                                                {{ $user->role_name }}
                                            @elseif ($user->type == 1)
                                                Admin
                                            @else
                                                N/A
                                            @endif
                                            {{-- (Debug: Type={{ $user->type }}, Role={{ $user->role_name ?? 'null' }}) --}}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-M-Y') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-outline-primary update-user btn-sm"
                                                style="border-radius: 5px;" data-toggle="modal"
                                                data-target="#exampleModalCenter" data-user-id="{{ $user->user_id }}"
                                                data-username="{{ $user->fullname }}">
                                                <h5>Update</h5>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Update User Password</h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editLinks = document.querySelectorAll('.update-user');

            editLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();

                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-username');

                    const editModalTitle = document.getElementById('exampleModalCenterTitle');
                    const editModalBody = document.querySelector('.modal-body');

                    editModalTitle.textContent = "Reset User Password";
                    editModalBody.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                        <h4 class="card-title">Updating password for: ${userName}</h4>
                        <form enctype="multipart/form-data" method="post" id="updateUserForm">
                            @csrf
                            <div class="mb-3">
                            <input type="hidden" name="edit-user-id" id="edit-user-id" value="${userId}" required>
                            <label for="" class="form-label">New Password</label>
                            <input id="user-password" class="form-control" name="user-password" type="password" placeholder="Enter new user password" required>
                            </div>
                            <div class="mb-3">
                            <label for="email" class="form-label">Confirm Password</label>
                            <input id="confirm-password" class="form-control" name="user-password_confirmation" type="password" placeholder="Confirm password" required>
                            </div>
                            <input class="btn btn-primary" type="submit" name="submit" value="Update User">
                        </form>
                        </div>
                    </div>`;

                    const updateUserForm = document.getElementById('updateUserForm');

                    updateUserForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(updateUserForm);

                        $.ajax({
                            type: 'POST',
                            url: '{{ route('admin.updateUser') }}',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message,
                                        "Success", {
                                            closeButton: true,
                                            positionClass: "toast-top-right",
                                            timeOut: 2000
                                        });
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
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
            });
        });
    </script>
@endsection
