@extends('layouts.department')

@section('sidebar')
    @include('fr-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                <h3>Edit Project</h3>
            </div>
        </div>
        <div class="d-flex col-6 justify-content-end mt-3 mt-md-0">
            <div><a href="{{ route('get.projects') }}" class="button button-info button-xs">Back</a></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h3 class="title">Update Projects Details Below</h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" enctype="multipart/form-data" id="edit-project-form">
                        @csrf
                        <div class="row mbn-20">
                            <input type="hidden" name="project-id"
                                value="{{ old('project-id', $project->project_id) }}">

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Request Date:</h6>
                                        <input type="date" class="form-control" name="request-date"
                                            value="{{ old('request-date', \Carbon\Carbon::parse($project->request_date)->format('Y-m-d')) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Client:</h6>
                                        <input type="text" class="form-control" name="client"
                                            value="{{ old('client', $project->client) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-20">
                                        <h6 class="mb-">Category:</h6>
                                        <select name="machine-category" class="form-control" id="machine-category">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->category_id }}"
                                                    {{ old('machine-category', $project->category) == $category->category_id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Model:</h6>
                                        <input type="text" class="form-control"
                                            value="{{ old('machine-model', $project->model) }}" name="machine-model"
                                            placeholder="Enter model" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Serial Number:</h6>
                                        <input type="text" class="form-control"
                                            value="{{ old('serial-number', $project->serial_number) }}" name="serial-number"
                                            placeholder="Enter serial number" readonly>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Total Counter:</h6>
                                        <input type="text" class="form-control"
                                            value="{{ old('total-counter', $project->total_counter) }}" name="total-counter"
                                            placeholder="Enter total counter">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">A/C Manager:</h6>
                                        <input type="text" class="form-control"
                                            value="{{ old('ac-manager', $project->ac_manager) }}" name="ac-manager"
                                            placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Priority:</h6>
                                        <input type="text" class="form-control"
                                            value="{{ old('priority', $project->priority) }}" name="priority"
                                            placeholder="">
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Tech Name:</h6>
                                        <input type="text" class="form-control"
                                            value="{{ old('tech-name', $project->tech_name) }}" name="tech-name"
                                            placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Deadline:</h6>
                                        <input type="date" class="form-control" name="deadline"
                                            value="{{ old('deadline', \Carbon\Carbon::parse($project->deadline)->format('Y-m-d')) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Status Page:</h6>
                                        <input type="text" class="form-control" name="status-page"
                                            value="{{ old('status-page', $project->status_page) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Status:</h6>
                                        <select name="status" class="form-control" id="status">
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->status_id }}"
                                                    {{ old('status', $project->status) == $status->status_id ? 'selected' : '' }}>
                                                    {{ $status->status_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-20">
                                <div class="col-12 row mbn-15 justify-content-center">
                                    <button type="submit" class="button button-primary" id="submit-form"><span>Update
                                            project</span></button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#edit-project-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('update-project') }}',
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

                        }
                        else {
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
@endpush
