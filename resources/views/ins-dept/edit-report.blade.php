@extends('layouts.department')

@section('sidebar')
    @include('ins-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                <h3>Edit Report</h3>
            </div>
        </div>
        <div class="d-flex col-6 justify-content-end mt-3 mt-md-0">
            <div><a href="{{ route('ins.report', ['type' => $requestSource]) }}" class="button button-info button-xs">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h3 class="title">Edit Report Details</h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" enctype="multipart/form-data" id="edit-report-form">
                        @csrf
                        <div class="row mbn-20">

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <input type="hidden" name="report-id" value="{{ $reportRecord->report_id }}">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Customer:</h6>
                                        <input type="text" class="form-control" name="customer"
                                            value="{{ old('customer', $reportRecord->customer) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Model:</h6>
                                        <input type="text" class="form-control" name="model"
                                            value="{{ old('model', $reportRecord->model) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Serial Number:</h6>
                                        <input type="text" class="form-control" name="serial-number"
                                            value="{{ old('serial-number', $reportRecord->serial_number) }}" placeholder="">
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Asset Code:</h6>
                                        <input type="text" class="form-control" name="asset-code"
                                            value="{{ old('asset-code', $reportRecord->asset_code) }}" placeholder="">
                                    </div>
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Location:</h6>
                                        <input type="text" class="form-control" name="location"
                                            value="{{ old('location', $reportRecord->location) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Installation/Uninstallation Date:</h6>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ $reportRecord->date ? \Carbon\Carbon::parse($reportRecord->date)->format('Y-m-d') : '' }}"
                                            placeholder="">
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">

                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Technician Name:</h6>
                                    <input type="text" class="form-control" name="tech-name"
                                        value="{{ old('tech-name', $reportRecord->technician_name) }}" placeholder="">
                                </div>

                                <div class="col-12 mb-15">
                                    <h6 class="mb-">Report Type:</h6>
                                    <select class="form-control" name="report-type" require>
                                        @foreach ($reportTypes as $report_type)
                                            <option value="{{ $report_type }}"
                                                {{ old('report-type', $reportRecord->report_type) == $report_type ? 'selected' : '' }}>
                                                {{ $report_type }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Company:</h6>
                                    <select class="form-control" name="report-company" required>
                                        @foreach ($companies as $company)
                                            @if (in_array($company->company_id, $companyArray))
                                                <option value="{{ $company->company_id }}"
                                                    {{ old('report-company', $reportRecord->company) == $company->company_id ? 'selected' : '' }}>
                                                    {{ $company->company_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-lg-4 col-12 mb-20">
                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Remarks:</h6>
                                    <textarea name="remarks" id="remarks" cols="30" rows="3">{{ $reportRecord->remarks }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 mb-20">
                                <div class="col-12 row mbn-15 justify-content-center">
                                    <button type="submit" class="button button-primary"
                                        id="submit-form"><span>Update</span></button>

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
            $('#edit-report-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('ins.update-report') }}',
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
