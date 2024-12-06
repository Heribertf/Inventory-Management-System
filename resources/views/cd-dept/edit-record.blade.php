@extends('layouts.department')
@section('sidebar')
    @include('cd-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                <h3>Edit Record</h3>
            </div>
        </div>
        <div class="d-flex col-6 justify-content-end mt-3 mt-md-0">
            <div><a href="{{ route('record.report', ['status' => $requestSource]) }}"
                    class="button button-info button-xs">Back</a></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h3 class="title">Update Record Details Below</h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" enctype="multipart/form-data" id="edit-asset-form">
                        @csrf
                        <div class="row mbn-20">

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <input type="hidden" name="inventory-id" value="{{ $inventory->inventory_id }}">

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Request Date of Collection:</h6>
                                        <input type="date" class="form-control" name="request-date"
                                            value="{{ $inventory->request_collection_date ? \Carbon\Carbon::parse($inventory->request_collection_date)->format('Y-m-d') : '' }}"
                                            placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Delivery/Collection Date:</h6>
                                        <input type="date" class="form-control" name="d-c-date"
                                            value="{{ $inventory->d_c_date ? \Carbon\Carbon::parse($inventory->d_c_date)->format('Y-m-d') : '' }}"
                                            placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Client/Customer Name:</h6>
                                        <input type="text" class="form-control" name="client-name"
                                            value="{{ old('client-name', $inventory->client_name) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Asset Code:</h6>
                                        <input type="text" class="form-control" name="asset-code"
                                            value="{{ old('asset-code', $inventory->asset_code) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Model:</h6>
                                        <input type="text" class="form-control" name="asset-model"
                                            value="{{ old('asset-model', $inventory->model) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Serial Number:</h6>
                                        <input type="text" class="form-control" name="serial-number"
                                            value="{{ old('serial-number', $inventory->serial_number) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Warehouse:</h6>
                                        <input type="text" class="form-control" name="warehouse"
                                            value="{{ old('warehouse', $inventory->warehouse) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Location:</h6>
                                        <input type="text" class="form-control" name="location"
                                            value="{{ old('location', $inventory->location) }}" placeholder="">
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Branches:</h6>
                                        <input type="text" class="form-control" name="branches"
                                            value="{{ old('branches', $inventory->branches) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Status:</h6>
                                        <select class="form-control" name="asset-status" require>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->status_id }}"
                                                    {{ old('asset-status', $inventory->status) == $status->status_id ? 'selected' : '' }}>
                                                    {{ $status->status_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Total Color:</h6>
                                        <input type="text" class="form-control" name="total-color"
                                            value="{{ old('total-color', $inventory->total_color) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Total B/W:</h6>
                                        <input type="text" class="form-control" name="total-bw"
                                            value="{{ old('total-bw', $inventory->total_b_w) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Accessories:</h6>
                                        <input type="text" class="form-control" name="accessories"
                                            value="{{ old('accessories', $inventory->accessories) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">IBT Number/Delivery:</h6>
                                        <input type="text" class="form-control" name="ibt-number"
                                            value="{{ old('ibt-number', $inventory->ibt_number) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Contact:</h6>
                                        <input type="text" class="form-control" name="contact"
                                            value="{{ old('contact', $inventory->contact) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Vehicle:</h6>
                                        <input type="text" class="form-control" name="vehicle"
                                            value="{{ old('vehicle', $inventory->vehicle) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Messenger:</h6>
                                        <input type="text" class="form-control" name="messenger"
                                            value="{{ old('messenger', $inventory->messenger) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">AC Manager:</h6>
                                        <input type="text" class="form-control" name="ac-manager"
                                            value="{{ old('ac-manager', $inventory->ac_manager) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Delivery Note Status:</h6>
                                        <input type="text" class="form-control" name="dn-status"
                                            value="{{ old('dn-status', $inventory->dn_status) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Comments:</h6>
                                        <input type="text" class="form-control" name="comments"
                                            value="{{ old('comments', $inventory->comments) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="col-12 mb-15">
                                    <h6 class="mb-5">Company:</h6>
                                    <select class="form-control" name="inventory-company" required>
                                        @foreach ($companies as $company)
                                            @if (in_array($company->company_id, $companyArray))
                                                <option value="{{ $company->company_id }}"
                                                    {{ old('inventory-company', $inventory->company) == $company->company_id ? 'selected' : '' }}>
                                                    {{ $company->company_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Remarks:</h6>
                                        <textarea name="remarks" id="remarks" cols="30" rows="5">{{ $inventory->remarks }}</textarea>
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
                                    <button type="submit" class="button button-primary" id="submit-form"><span>Update
                                            Record</span></button>

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
        FilePond.registerPlugin(FilePondPluginImageExifOrientation, FilePondPluginImagePreview);
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

                if (selectedStatus == 13 || selectedStatus == 1) {
                    assetFilesInput.prop('required', true);
                    dcDateInput.prop('required', true);
                } else {
                    assetFilesInput.prop('required', false);
                    dcDateInput.prop('required', false);
                }
            });

            $('#edit-asset-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                var filePondFiles = pond.getFiles();

                filePondFiles.forEach(fileItem => {
                    formData.append('asset-files[]', fileItem.file, fileItem.file.name);
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('update-record') }}',
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
