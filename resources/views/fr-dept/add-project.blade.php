@extends('layouts.department')

@section('sidebar')
    @include('fr-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                <h3>Add New Project</h3>
            </div>
        </div>
        <div class="d-flex col-6 justify-content-end mt-3 mt-md-0">
            <div>
                <input type="text" class="form-control" id="serial-number-search" name="serial-number-search"
                    placeholder="Search serial number">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h3 class="title">Enter Project Details</h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" enctype="multipart/form-data" id="project-form">
                        @csrf
                        <div class="row mbn-20">
                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Request Date:</h6>
                                        <input type="date" class="form-control" name="request_date" required>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Client:</h6>
                                        <input type="text" class="form-control" id="pclient" name="client"
                                            placeholder="" readonly required>
                                    </div>

                                    <div class="col-12 mb-20">
                                        <h6 class="mb-5">Category:</h6>
                                        <select class="form-control" name="machine_category_select" disabled>
                                            <option value="" selected disabled>Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->category_id }}">
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="current-category" name="machine_category" value=""
                                            required>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Model:</h6>
                                        <input type="text" class="form-control" id="machine-model" name="machine_model"
                                            placeholder="Enter model" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Serial Number:</h6>
                                        <input type="text" class="form-control" id="serial-number" name="serial_number"
                                            placeholder="Enter serial number" required readonly>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Total Counter:</h6>
                                        <input type="text" class="form-control" id="total-counter" name="total_counter"
                                            placeholder="Enter total counter" readonly>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">A/C Manager:</h6>
                                        <input type="text" class="form-control" name="ac_manager" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Priority:</h6>
                                        <input type="text" class="form-control" name="priority" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Tech Name:</h6>
                                        <input type="text" class="form-control" name="tech_name" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Deadline:</h6>
                                        <input type="date" class="form-control" name="deadline" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Status:</h6>
                                        <select class="form-control" id="current-machine-status" name="status-select"
                                            disabled>
                                            <option value="" selected disabled>Select Status</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->status_id }}">
                                                    {{ $status->status_name }}</option>
                                            @endforeach
                                        </select>

                                        <input type="hidden" id="current-status"name="status" value="" required>

                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-20">
                                <div class="col-12 row mbn-15 justify-content-center">
                                    <button type="submit" class="button button-primary"
                                        id="submit-form"><span>Submit</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $("#serial-number-search").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "{{ route('search-serial-number') }}",
                            type: "GET",
                            dataType: "json",
                            data: {
                                term: request.term
                            },
                            success: function(data) {
                                response(data);
                            }
                        });
                    },
                    minLength: 2,
                    select: function(event, ui) {
                        $("#serial-number").val(ui.item.value);
                        $("#pclient").val(ui.item.client);
                        $("select[name='machine_category_select']").val(ui.item.category_id);
                        $("#current-category").val(ui.item.category_id);
                        $("#machine-model").val(ui.item.model);
                        $("select[name='status-select']").val(ui.item.status);
                        $("#current-status").val(ui.item.status);
                        $("#total-counter").val(ui.item.total_counter);
                    }
                });

                $('#project-form').submit(function(e) {
                    e.preventDefault();

                    var formData = new FormData($(this)[0]);

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('submit.project') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, "Success");
                                setTimeout(function() {
                                    location.reload();
                                }, 4000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
