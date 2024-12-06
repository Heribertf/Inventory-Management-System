@extends('layouts.department')
@section('sidebar')
    @include('cd-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                <h3>Add New Record</h3>
            </div>
        </div>
        {{-- <div class="d-flex col-6 justify-content-end mt-3 mt-md-0">
            <div><a href="#" class="button button-info button-xs" data-toggle="modal"
                    data-target="#submitExcelFileModal">Submit an Excel File Instead</a></div>
        </div> --}}
    </div>

    <div class="row">

        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h3 class="title">Enter Asset Details</h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" enctype="multipart/form-data" id="asset-form">
                        @csrf
                        <div class="row mbn-20">

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Requested Date of Collection:</h6>
                                        <input type="date" class="form-control" name="request-date" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Delivery/Collection Date:</h6>
                                        <input type="date" class="form-control" name="d-c-date" id="d-c-date"
                                            placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Client/Customer Name:</h6>
                                        <input type="text" class="form-control" name="client-name" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Asset Code:</h6>
                                        <input type="text" class="form-control" name="asset-code" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Model:</h6>
                                        <input type="text" class="form-control" name="asset-model" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Serial Number:</h6>
                                        <input type="text" class="form-control" name="serial-number" placeholder=""
                                            required>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Warehouse:</h6>
                                        <input type="text" class="form-control" name="warehouse" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Location:</h6>
                                        <input type="text" class="form-control" name="location" placeholder="">
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Branches:</h6>
                                        <input type="text" class="form-control" name="branches" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Status:</h6>
                                        <select class="form-control" name="asset-status" id="asset-status" require>
                                            <option value="" selected disabled>Select Status</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->status_id }}">{{ $status->status_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Total Color:</h6>
                                        <input type="text" class="form-control" name="total-color" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Total B/W:</h6>
                                        <input type="text" class="form-control" name="total-bw" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Accessories:</h6>
                                        <input type="text" class="form-control" name="accessories" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">IBT Number/Delivery:</h6>
                                        <input type="text" class="form-control" name="ibt-number" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Contact:</h6>
                                        <input type="text" class="form-control" name="contact" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Vehicle:</h6>
                                        <input type="text" class="form-control" name="vehicle" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Messenger:</h6>
                                        <input type="text" class="form-control" name="messenger" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">AC Manager:</h6>
                                        <input type="text" class="form-control" name="ac-manager" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Delivery Note Status:</h6>
                                        <input type="text" class="form-control" name="dn-status" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Comments:</h6>
                                        <input type="text" class="form-control" name="comments" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Company:</h6>
                                    <select class="form-control" name="inventory-company" required>
                                        <option value="" selected disabled>Select Company</option>
                                        @foreach ($companies as $company)
                                            @if (in_array($company->company_id, $companyArray))
                                                <option value="{{ $company->company_id }}">{{ $company->company_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Remarks:</h6>
                                        <textarea name="remarks" id="remarks" cols="30" rows="5"></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-12 mb-20">

                                <div class="row mbn-15">

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Select Files to Upload:</h6>
                                        <input class="file-pond" type="file" name="asset-files[]" id="asset-files"
                                            multiple>
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
@endsection

@push('scripts')
    <script>
        const inputElement = document.querySelector('.file-pond');
        const pond = FilePond.create(inputElement, {
            allowMultiple: true,
            imagePreviewHeight: 140,
        });

        $(document).ready(function() {
            $('#asset-status').change(function() {
                var selectedStatus = $(this).val();
                var assetFilesInput = $('#asset-files');
                var dcDateInput = $('#d-c-date');

                if (selectedStatus == 1 || selectedStatus == 13) {
                    assetFilesInput.prop('required', true);
                    dcDateInput.prop('required', true);
                } else {
                    assetFilesInput.prop('required', false);
                    dcDateInput.prop('required', false);
                }
            });

            $('#asset-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                var filePondFiles = pond.getFiles();

                filePondFiles.forEach(fileItem => {
                    formData.append('asset-files[]', fileItem.file, fileItem.file.name);
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('cd.submit-record') }}',
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
@endpush
