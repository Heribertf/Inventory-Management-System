@extends('layouts.department')

@section('sidebar')
    @include('ins-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                <h3>Add New Report</h3>
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
                    <h3 class="title">Enter Report Details</h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" enctype="multipart/form-data" id="report-form">
                        @csrf
                        <div class="row mbn-20">

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Customer:</h6>
                                        <input type="text" class="form-control" name="customer" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Model:</h6>
                                        <input type="text" class="form-control" name="model" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Serial Number:</h6>
                                        <input type="text" class="form-control" name="serial-number" placeholder="">
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Asset Code:</h6>
                                        <input type="text" class="form-control" name="asset-code" placeholder="">
                                    </div>
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Location:</h6>
                                        <input type="text" class="form-control" name="location" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Installation/Uninstallation Date:</h6>
                                        <input type="date" class="form-control" name="date" placeholder="">
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">

                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Technician Name:</h6>
                                    <input type="text" class="form-control" name="tech-name" placeholder="">
                                </div>
                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Report Type:</h6>
                                    <select class="form-control" name="report-type" required>
                                        <option value="" selected disabled>Select Type of Report</option>
                                        <option value="INSTALLATION">INSTALLATION</option>
                                        <option value="UNINSTALLATION">UNINSTALLATION</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Company:</h6>
                                    <select class="form-control" name="report-company" required>
                                        <option value="" selected disabled>Select Company</option>
                                        @foreach ($companies as $company)
                                            @if (in_array($company->company_id, $companyArray))
                                                <option value="{{ $company->company_id }}">{{ $company->company_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                            <div class="col-lg-4 col-12 mb-20">

                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Remarks:</h6>
                                    <textarea name="remarks" id="remarks" cols="30" rows="3"></textarea>
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
        $(document).ready(function() {
            $('#report-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('submit-report') }}',
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

            // $('#submit-excel-form').submit(function(e) {
            //     e.preventDefault();

            //     var loader = document.getElementById("loader");
            //     loader.style.display = "block";

            //     var formData = new FormData($(this)[0]);
            //     $.ajax({
            //         type: 'POST',
            //         url: './submit-excel-report',
            //         data: formData,
            //         cache: false,
            //         contentType: false,
            //         processData: false,
            //         dataType: 'json',
            //         success: function(response) {
            //             if (response.success) {
            //                 loader.style.display = "none";
            //                 toastr.success(response.message, "Success", {
            //                     closeButton: true,
            //                     positionClass: "toast-top-right",
            //                     timeOut: 3000
            //                 });

            //                 setTimeout(function() {
            //                     location.reload();
            //                 }, 3000);

            //             } else {
            //                 loader.style.display = "none";
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Oops...',
            //                     text: response.message
            //                 });
            //             }

            //         },
            //         error: function(xhr, status, error) {
            //             loader.style.display = "none";
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Error',
            //                 text: 'An unexpected error occurred.'
            //             });
            //         }
            //     });
            // });

        });
    </script>
@endpush
