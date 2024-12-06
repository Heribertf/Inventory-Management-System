@extends('layouts.department')

@section('sidebar')
    @include('fr-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                <h3>Add New Inventory</h3>
            </div>
        </div>
        <div class="d-flex col-6 justify-content-end mt-3 mt-md-0">
            <div><a href="#" class="button button-info button-xs" data-toggle="modal"
                    data-target="#submitExcelFileModal">Submit an Excel File Instead</a></div>
        </div>
    </div>

    <div class="row">

        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h3 class="title">Enter Inventory Details</h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" enctype="multipart/form-data" id="inventory-form">
                        @csrf
                        <div class="row mbn-20">

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Collection Date:</h6>
                                        <input type="date" class="form-control" name="collection-date"
                                            id="collection-date" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Collected From:</h6>
                                        <input type="text" class="form-control" name="collected-from" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Company:</h6>
                                        <select class="form-control" name="inventory-company" required>
                                            <option value="" selected disabled>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->company_id }}">{{ $company->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-20">
                                        <h6 class="mb-">Category:</h6>
                                        <select class="form-control" name="inventory-category">
                                            <option value="" selected disabled>Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->category_id }}">{{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Model:</h6>
                                        <input type="text" class="form-control" name="inventory-model"
                                            placeholder="Enter model">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Serial Number:</h6>
                                        <input type="text" class="form-control" name="serial-number"
                                            placeholder="Enter serial number">
                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-20">
                                        <h6 class="mb-">Status:</h6>
                                        <select class="form-control" name="inventory-status" id="inventory-status">
                                            <option value="" selected disabled>Select Inventory Status</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->status_id }}">{{ $status->status_name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DP Model:</h6>
                                        <input type="text" class="form-control" name="dp-model" placeholder="">
                                    </div>
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DP Serial:</h6>
                                        <input type="text" class="form-control" name="dp-serial" placeholder="">
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">CB:</h6>
                                        <input type="text" class="form-control" name="inventory-cb" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Color:</h6>
                                        <input type="number" class="form-control" name="inventory-color" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Mono Counter:</h6>
                                        <input type="number" class="form-control" name="mono-counter" placeholder="">
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">


                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Total:</h6>
                                        <input type="number" class="form-control" name="inventory-total"
                                            placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">FK:</h6>
                                        <input type="text" class="form-control" name="fk" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DK:</h6>
                                        <input type="text" class="form-control" name="dk" placeholder="">
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DV:</h6>
                                        <input type="text" class="form-control" name="dv" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Belt:</h6>
                                        <input type="text" class="form-control" name="belt" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Feed:</h6>
                                        <input type="text" class="form-control" name="feed" placeholder="">
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Dispatched to:</h6>
                                        <input type="text" class="form-control" name="dispatched-to" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Dispatch Date:</h6>
                                        <input type="date" class="form-control" name="dispatch-date"
                                            id="dispatch-date" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Warehouse:</h6>
                                        <input type="text" class="form-control" name="warehouse" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DP/PF Out:</h6>
                                        <input type="text" class="form-control" name="dp-pf-out" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Life Counter:</h6>
                                        <input type="number" class="form-control" name="life-counter" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Remarks:</h6>
                                        <textarea name="remarks" id="remarks" cols="35" rows=""></textarea>
                                        <!-- <input type="text" class="form-control" name="remarks" placeholder=""> -->
                                    </div>
                                </div>

                            </div>

                            <div class=" col-12 mb-20">
                                <div class="row mbn-15">

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Select Files to Upload:</h6>
                                        <input class="file-pond" type="file" name="inventory-files[]"
                                            id="inventory-files" multiple>
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

    </div>

    <!-- Submit Excel File Modal -->
    <div class="modal fade" id="submitExcelFileModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Excel file</h5>
                    <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="" enctype="multipart/form-data" method="post" id="submit-excel-form">
                        @csrf
                        <div class="col-12 mb-10">
                            <label for="" class="form-label">Choose an excel file(.csv/.xlsx) containing inventory
                                details</label>
                            <input type="file" class="form-control" name="inventory-file" required>
                        </div>


                        <div class="col-12">
                            <button type="submit" class="button button-primary">Submit File</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="button button-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImageExifOrientation, FilePondPluginImagePreview);
        const inputElement = document.querySelector('.file-pond');
        const pond = FilePond.create(inputElement, {
            allowMultiple: true,
            imagePreviewHeight: 140,
        });

        $(document).ready(function() {
            $('#inventory-status').change(function() {
                var selectedStatus = $(this).val();
                var inventoryFilesInput = $('#inventory-files');
                var collectioDateInput = $('#collection-date');
                var dispatchDateInput = $('#dispatch-date');

                if (selectedStatus == 2 || selectedStatus == 1) {
                    inventoryFilesInput.prop('required', true);
                } else {
                    inventoryFilesInput.prop('required', false);
                }

                dispatchDateInput.prop('required', selectedStatus == 2);
                collectionDateInput.prop('required', selectedStatus == 1);
            });

            $('#inventory-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                var filePondFiles = pond.getFiles();

                filePondFiles.forEach(fileItem => {
                    formData.append('inventory-files[]', fileItem.file, fileItem.file.name);
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('fr.submit-inventory') }}',
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
                            }, 4000);
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

            $('#submit-excel-form').submit(function(e) {
                e.preventDefault();

                var loader = document.getElementById("loader");
                loader.style.display = "block";

                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('fr.submit-excel') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            loader.style.display = "none";
                            toastr.success(response.message, "Success", {
                                closeButton: true,
                                positionClass: "toast-top-right",
                                timeOut: 3000
                            });

                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        } else {
                            loader.style.display = "none";
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        loader.style.display = "none";
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred.'
                        });
                    }
                });
            });

            function loadCategories() {
                $.ajax({
                    url: '/fr-dept/get-categories',
                    method: 'GET',
                    success: function(response) {
                        let categoryList = $('#category-list');
                        categoryList.empty();

                        response.categories.forEach(function(category) {
                            categoryList.append('<li class="list-group-item">' + category.name +
                                '</li>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching categories:', error);
                    }
                });
            }

            function loadStatuses() {
                $.ajax({
                    url: '/fr-dept/get-statuses',
                    method: 'GET',
                    success: function(response) {
                        let statusList = $('#status-list');
                        statusList.empty();

                        response.statuses.forEach(function(status) {
                            statusList.append('<li class="list-group-item">' + status.name +
                                '</li>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching status:', error);
                    }
                });
            }
            loadCategories();
            loadStatuses();
        });
    </script>
@endpush
