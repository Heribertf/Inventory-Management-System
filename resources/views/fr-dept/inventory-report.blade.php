@extends('layouts.department')

@section('sidebar')
    @include('fr-dept.sidebar')
@endsection
@section('content')
    <div class="row justify-content-between align-items-center mb-10">

        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>All {{ htmlspecialchars($title) }} Machines</h3>
            </div>
        </div>
        <div class="col-12 col-lg-auto mb-20">
            <form method="POST" action="{{ route('export.report') }}" style="display: inline;">
                @csrf
                <button type="submit" name="export_excel" value="{{ htmlspecialchars($inventoryStatus) }}"
                    class="button button-primary button-sm">Export to Excel</button>
            </form>
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
                            @foreach ($inventoryReport as $index => $inventory)
                                <tr>
                                    <td>{{ (int) $index + 1 }}</td>
                                    <td>{{ $inventory->collection_date ?: '' }}</td>
                                    <td>{{ $inventory->collected_from ?: '' }}</td>
                                    <td>{{ $inventory->company ?? '' }}</td>
                                    <td>{{ $inventory->category_name ?? '' }}</td>
                                    <td>{{ $inventory->model ?? '' }}</td>
                                    <td>{{ $inventory->serial_number ?: '' }}</td>
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
                                        @else
                                            <button class="button button-primary button-sm" disabled>
                                                <h5>Edit</h5>
                                            </button>
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
    </script>
@endpush
