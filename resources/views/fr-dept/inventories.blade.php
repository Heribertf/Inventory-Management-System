@extends('layouts.department')

@section('sidebar')
    @include('fr-dept.sidebar')
@endsection
@section('content')
    <div class="row justify-content-between align-items-center mb-10">

        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>All Inventories</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-30">
            <div class="box">
                <div class="box-body">
                    <table class="table table-bordered data-table data-table-default">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>#</th>
                                <th>Collection Date</th>
                                <th>Collected From</th>
                                <th>Company</th>
                                <th>Category</th>
                                <th>Item Group</th>
                                <th>Model</th>
                                <th>Serial No</th>
                                <th>Status</th>
                                <th>DP Model</th>
                                <th>DP Serial</th>
                                <th>CB</th>
                                <th>Color</th>
                                <th>Mono Counter</th>
                                <th>Total</th>
                                <th>FK</th>
                                <th>DK</th>
                                <th>DV</th>
                                <th>Belt</th>
                                <th>Feed</th>
                                <th>Dispatched To</th>
                                <th>Dispatch Date</th>
                                <th>Warehouse</th>
                                <th>DP/PF Out</th>
                                <th>Life Counter</th>
                                <th>Remarks</th>
                                <th>Action</th>
                                <th>Files</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventories as $index => $inventory)
                                <tr>
                                    @php
                                        if ($inventory->filler_date == 1) {
                                            $collectionDate = $inventory->collection_date;
                                        } elseif ($inventory->sage_date == 1) {
                                            $collectionDate = $inventory->sage_collection_date;
                                        } else {
                                            $collectionDate = '';
                                        }
                                    @endphp
                                    <td>{{ (int) $index + 1 }}</td>
                                    <td>{{ $collectionDate }}</td>
                                    <td>{{ $inventory->collected_from ?? '' }}</td>
                                    <td>{{ $inventory->company_name ?? '' }}</td>
                                    <td>{{ $inventory->category_name ?? '' }}</td>
                                    <td>{{ $inventory->item_group ?? '' }}</td>
                                    <td>{{ $inventory->model ?? '' }}</td>
                                    <td>{{ $inventory->serial_number ?? '' }}</td>
                                    <td>{{ $inventory->status_name ?? '' }}</td>
                                    <td>{{ $inventory->dp_model ?? '' }}</td>
                                    <td>{{ $inventory->dp_serial ?? '' }}</td>
                                    <td>{{ $inventory->cb ?? '' }}</td>
                                    <td>{{ $inventory->color ?? '' }}</td>
                                    <td>{{ $inventory->mono_counter ?? '' }}</td>
                                    <td>{{ $inventory->total ?? '' }}</td>
                                    <td>{{ $inventory->fk ?? '' }}</td>
                                    <td>{{ $inventory->dk ?? '' }}</td>
                                    <td>{{ $inventory->dv ?? '' }}</td>
                                    <td>{{ $inventory->belt ?? '' }}</td>
                                    <td>{{ $inventory->feed ?? '' }}</td>
                                    <td>{{ $inventory->dispatched_to ?? '' }}</td>
                                    <td>{{ $inventory->dispatchDate ?? '' }}</td>
                                    <td>{{ $inventory->warehouse ?? '' }}</td>
                                    <td>{{ $inventory->dp_pf_out ?? '' }}</td>
                                    <td>{{ $inventory->life_counter ?? '' }}</td>
                                    <td>{{ $inventory->remarks ?? '' }}</td>
                                    <td>
                                        @if ($canWrite)
                                            <form action="{{ url('/fr-dept/edit-inventory') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="inventory-id"
                                                    value="{{ $inventory->inventory_id ?? '' }}">
                                                <button type="submit" class="button button-primary button-sm">
                                                    <h5>Edit</h5>
                                                </button>
                                            </form>

                                            <button type="submit" data-inventory-id="{{ $inventory->inventory_id ?? '' }}"
                                                class="button button-danger button-sm delete-inventory">
                                                <h5>Delete</h5>
                                            </button>
                                        @else
                                            <h5></h5>
                                        @endif


                                    </td>
                                    <td>
                                        @if (!empty($inventory->files))
                                            @php
                                                $fileNames = explode(',', $inventory->files);
                                            @endphp
                                            <button class="button button-info button-sm"
                                                onclick='viewFiles({{ json_encode($fileNames) }}, "{{ $inventory->serial_number }}")'>
                                                <h5>View/Download Files</h5>
                                            </button>
                                        @else
                                            No files
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        function viewFiles(fileNames, serialNumber) {
            const baseUrl = '/storage/';
            let fileList = '<ul style="list-style-type: none; padding: 0;">';
            fileNames.forEach(function(fileName) {
                const fileUrl = `${baseUrl}${fileName}`;

                fileList += `
            <li style="margin-bottom: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>${fileName}</span>
                    <div>
                        <a href="${fileUrl}" target="_blank" class="button button-primary button-sm" style="margin-right: 5px;">View</a>
                        <a href="/download-file?file=${fileName}" class="button button-success button-sm">Download</a>
                    </div>
                </div>
            </li>`;
            });
            fileList += '</ul>';

            Swal.fire({
                title: 'Files for Inventory SN: ' + serialNumber,
                html: fileList,
                icon: 'info',
                width: 600,
                showCloseButton: true,
                showConfirmButton: false
            });
        }

        $(document).ready(function() {
            // $('.delete-inventory').on('click', function(e) {
            $('body').on('click', '.delete-inventory', function(e) {
                e.preventDefault();

                console.log('button clicked');

                const inventoryId = this.getAttribute('data-inventory-id');

                // const inventoryId = $(this).data('inventory-id');
                console.log('Inventory ID:', inventoryId);

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger me-2'
                    },
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Warning!',
                    text: "Are you sure you want to delete this inventory?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'me-2',
                    confirmButtonText: 'Yes, delete!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: 'POST',
                            url: '{{ route('delete-inventory') }}',
                            data: {
                                inventoryId: inventoryId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message, "Success", {
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
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Deletion aborted',
                            'error'
                        )
                    }
                });
            });

        });
    </script>
@endpush
