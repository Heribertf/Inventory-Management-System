@extends('layouts.department')

@section('sidebar')
    @include('fr-dept.sidebar')
@endsection
@section('content')
    <div class="row align-items-center mb-10">
        <div class="col-6">
            <div class="page-heading">
                <h3>Edit Inventory</h3>
            </div>
        </div>
        <div class="d-flex col-6 justify-content-end mt-3 mt-md-0">
            <div><a href="{{ route('fr.fetchInventories') }}" class="button button-info button-xs">Back</a></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-head">
                    <h3 class="title">Update Inventory Details Below</h3>
                </div>
                <div class="box-body">
                    <form action="" method="post" enctype="multipart/form-data" id="edit-inventory-form">
                        @csrf
                        <div class="row mbn-20">
                            <input type="hidden" name="inventory-id"
                                value="{{ old('inventory-id', $inventory->inventory_id) }}">

                            @php
                                if ($inventory->filler_date == 1) {
                                    $collectionDate = $inventory->collection_date;
                                } elseif ($inventory->sage_date == 1) {
                                    $collectionDate = $inventory->sage_collection_date;
                                } else {
                                    $collectionDate = '';
                                }
                            @endphp

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Collection Date:</h6>
                                        <input type="date" class="form-control" name="collection-date"
                                            id="collection-date"
                                            value="{{ old('collection-date', \Carbon\Carbon::parse($collectionDate)->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Collected From:</h6>
                                        <input type="text" class="form-control" name="collected-from"
                                            value="{{ old('collected-from', $inventory->collected_from) }}" readonly>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Company:</h6>
                                        <select name="inventory-company" class="form-control">
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->company_id }}"
                                                    {{ old('inventory-company', $inventory->company) == $company->company_id ? 'selected' : '' }}
                                                    @disabled(true)>
                                                    {{ $company->company_name }}
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
                                        <select name="inventory-category" class="form-control">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->category_id }}"
                                                    {{ old('inventory-category', $inventory->category) == $category->category_id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Model:</h6>
                                        <input type="text" class="form-control" name="inventory-model"
                                            value="{{ old('inventory-model', $inventory->model) }}"
                                            placeholder="Enter model"readonly>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Serial Number:</h6>
                                        <input type="text" class="form-control" name="serial-number"
                                            value="{{ old('serial-number', $inventory->serial_number) }}"
                                            placeholder="Enter serial number" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-20">
                                        <h6 class="mb-">Status:</h6>
                                        <select name="inventory-status" class="form-control">
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->status_id }}"
                                                    {{ old('inventory-status', $inventory->status) == $status->status_id ? 'selected' : '' }}>
                                                    {{ $status->status_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DP Model:</h6>
                                        <input type="text" class="form-control" name="dp-model"
                                            value="{{ old('dp-model', $inventory->dp_model) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DP Serial:</h6>
                                        <input type="text" class="form-control" name="dp-serial"
                                            value="{{ old('dp-serial', $inventory->dp_serial) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">CB:</h6>
                                        <input type="text" class="form-control" name="inventory-cb"
                                            value="{{ old('inventory-cb', $inventory->cb) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Color:</h6>
                                        <input type="number" class="form-control" name="inventory-color"
                                            value="{{ old('inventory-color', $inventory->color) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Mono Counter:</h6>
                                        <input type="number" class="form-control" name="mono-counter"
                                            value="{{ old('mono-counter', $inventory->mono_counter) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Total:</h6>
                                        <input type="number" class="form-control" name="inventory-total"
                                            value="{{ old('inventory-total', $inventory->total) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">FK:</h6>
                                        <input type="text" class="form-control" name="fk"
                                            value="{{ old('fk', $inventory->fk) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DK:</h6>
                                        <input type="text" class="form-control" name="dk"
                                            value="{{ old('dk', $inventory->dk) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DV:</h6>
                                        <input type="text" class="form-control" name="dv"
                                            value="{{ old('dv', $inventory->dv) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Belt:</h6>
                                        <input type="text" class="form-control" name="belt"
                                            value="{{ old('belt', $inventory->belt) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Feed:</h6>
                                        <input type="text" class="form-control" name="feed"
                                            value="{{ old('feed', $inventory->feed) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Dispatched to:</h6>
                                        <input type="text" class="form-control" name="dispatched-to"
                                            id="dispatched-to"
                                            value="{{ old('dispatched-to', $inventory->dispatched_to) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Dispatch Date:</h6>
                                        <input type="date" class="form-control" name="dispatch-date"
                                            id="dispatch-date"
                                            value="{{ $inventory->dispatch_date ? \Carbon\Carbon::parse($inventory->dispatch_date)->format('Y-m-d') : '' }}"
                                            placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Warehouse:</h6>
                                        <input type="text" class="form-control" name="warehouse"
                                            value="{{ old('warehouse', $inventory->warehouse) }}" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">DP/PF Out:</h6>
                                        <input type="text" class="form-control" name="dp-pf-out"
                                            value="{{ old('dp-pf-out', $inventory->dp_pf_out) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Life Counter:</h6>
                                        <input type="text" class="form-control" name="life-counter"
                                            value="{{ old('life-counter', $inventory->life_counter) }}" placeholder="">
                                    </div>

                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Remarks:</h6>
                                        <textarea name="remarks" id="remarks" cols="30" rows="">{{ $inventory->remarks }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 mb-20" id="toner-fields" style="display: none;">
                                <div class="row mbn-15">
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Toner K:</h6>
                                        <input type="number" class="form-control" name="toner-k" placeholder="">
                                    </div>
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Toner Y:</h6>
                                        <input type="number" class="form-control" name="toner-y" placeholder="">
                                    </div>
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Toner M:</h6>
                                        <input type="number" class="form-control" name="toner-m" placeholder="">
                                    </div>
                                    <div class="col-12 mb-15">
                                        <h6 class="mb-5">Toner C:</h6>
                                        <input type="number" class="form-control" name="toner-c" placeholder="">
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 mb-20">
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
                                    <button type="submit" class="button button-primary" id="submit-form"><span>Update
                                            Inventory</span></button>
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
        FilePond.registerPlugin(FilePondPluginImageExifOrientation, FilePondPluginImagePreview);
        const inputElement = document.querySelector('.file-pond');
        const pond = FilePond.create(inputElement, {
            allowMultiple: true,
            imagePreviewHeight: 140,
        });

        $(document).ready(function() {
            $('select[name="inventory-status"]').change(function() {
                var selectedStatus = $(this).val();

                var tonerFields = $('#toner-fields');
                var inventoryFilesInput = $('#inventory-files');
                var collectioDateInput = $('#collection-date');
                var dispatchDateInput = $('#dispatch-date');
                var dispatchedTo = $('#dispatched-to');

                if (selectedStatus == 2 || selectedStatus == 1) {
                    console.log('selected status = ' + selectedStatus);
                    tonerFields.show();
                    inventoryFilesInput.prop('required', true);
                    tonerFields.find('input').prop('required', true);
                } else {
                    tonerFields.hide();
                    inventoryFilesInput.prop('required', false);
                    tonerFields.find('input').prop('required', false);
                }

                dispatchDateInput.prop('required', selectedStatus == 2);
                dispatchedTo.prop('required', selectedStatus == 2);
                collectionDateInput.prop('required', selectedStatus == 1);
            });

            $('#edit-inventory-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                var filePondFiles = pond.getFiles();

                filePondFiles.forEach(fileItem => {
                    formData.append('inventory-files[]', fileItem.file, fileItem.file.name);
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ route('update-inventory') }}',
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
        });
    </script>
@endpush
